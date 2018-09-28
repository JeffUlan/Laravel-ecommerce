<?php

namespace Webkul\Checkout;

use Carbon\Carbon;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Checkout\Repositories\CartAddressRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Checkout\Models\CartPayment;
use Cookie;

/**
 * Facade for all the methods to be implemented in Cart.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Cart {

    /**
     * CartRepository model
     *
     * @var mixed
     */
    protected $cart;

    /**
     * CartItemRepository model
     *
     * @var mixed
     */
    protected $cartItem;

    /**
     * CustomerRepository model
     *
     * @var mixed
     */
    protected $customer;

    /**
     * CartAddressRepository model
     *
     * @var mixed
     */
    protected $cartAddress;

    /**
     * ProductRepository model
     *
     * @var mixed
     */
    protected $product;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Checkout\Repositories\CartRepository $cart
     * @param  Webkul\Checkout\Repositories\CartItemRepository $cartItem
     * @param  Webkul\Checkout\Repositories\CartAddressRepository $cartAddress
     * @param  Webkul\Customer\Repositories\CustomerRepository $customer
     * @param  Webkul\Product\Repositories\ProductRepository $product
     * @return void
     */
    public function __construct(
        CartRepository $cart,
        CartItemRepository $cartItem,
        CartAddressRepository $cartAddress,
        CustomerRepository $customer,
        ProductRepository $product)
    {
        $this->customer = $customer;

        $this->cart = $cart;

        $this->cartItem = $cartItem;

        $this->cartAddress = $cartAddress;

        $this->product = $product;
    }

     /**
     * Method to check if the product is available and its required quantity
     * is available or not in the inventory sources.
     *
     * @param integer $id
     *
     * @return Array
     */
    public function canCheckOut($id) {
        $cart = $this->cart->findOneByField('id', 144);

        $items = $cart->items;

        $allProdQty = array();

        $allProdQty1 = array();

        $totalQty = 0;

        foreach($items as $item) {
            $inventories = $item->product->inventories;

            $inventory_sources = $item->product->inventory_sources;

            $totalQty = 0;

            foreach($inventory_sources as $inventory_source) {
                if($inventory_source->status!=0) {
                    foreach($inventories as $inventory) {
                        $totalQty = $totalQty + $inventory->qty;
                    }

                    array_push($allProdQty1, $totalQty);

                    $allProdQty[$item->product->id] = $totalQty;
                }
            }
        }

        foreach ($items as $item) {
            $inventories = $item->product->inventory_sources->where('status', '=', '1');

            foreach($inventories as $inventory) {
                dump($inventory->status);
            }
        }

        dd($allProdQty);

        dd([true, false]);
    }

    /**
     * Create new cart instance with the current item added.
     *
     * @param integer $id
     * @param array $data
     *
     * @return Response
     */
    public function createNewCart($id, $data)
    {
        $itemData = $this->prepareItemData($id, $data);

        // dd($itemData);

        $cartData['channel_id'] = core()->getCurrentChannel()->id;

        // this will auto set the customer id for the cart instances if customer is authenticated
        if(auth()->guard('customer')->check()) {
            $cartData['customer_id'] = auth()->guard('customer')->user()->id;

            $cartData['is_guest'] = 1;

            $cartData['customer_full_name'] = auth()->guard('customer')->user()->first_name .' '. auth()->guard('customer')->user()->last_name;
        }

        $cartData['items_count'] = 1;

        $cartData['items_quantity'] = $data['quantity'];

        if($cart = $this->cart->create($cartData)) {
            $itemData['parent']['cart_id'] = $cart->id;

            if ($data['is_configurable'] == "true") {
                //parent product entry
                $itemData['parent']['additional'] = json_encode($data);
                if($parent = $this->cartItem->create($itemData['parent'])) {

                    $itemData['child']['parent_id'] = $parent->id;
                    if($child = $this->cartItem->create($itemData['child'])) {
                        session()->put('cart', $cart);

                        session()->flash('success', 'Item Added To Cart Successfully');

                        return redirect()->back();
                    }
                }

            } else if($data['is_configurable'] == "false") {
                if($result = $this->cartItem->create($itemData['parent'])) {
                    session()->put('cart', $cart);

                    session()->flash('success', 'Item Added To Cart Successfully');

                    return redirect()->back();
                }
            }
        }

        session()->flash('error', 'Some Error Occured');

        return redirect()->back();
    }

    /**
     * Prepare the other data for the product to be added.
     *
     * @param integer $id
     * @param array $data
     *
     * @return array
     */
    public function prepareItemData($productId, $data)
    {
        $product = $this->product->findOneByField('id', $productId);

        unset($data['_token']);

        //Check if the product is salable
        if(!isset($data['product']) ||!isset($data['quantity'])) {
            session()->flash('error', 'Cart System Integrity Violation, Some Required Fields Missing.');

            dd('Missing Essential Parameters, Cannot Proceed Further');

            return redirect()->back();
        } else {
            if($product->type == 'configurable' && !isset($data['super_attribute'])) {
                session()->flash('error', 'Cart System Integrity Violation, Configurable Options Not Found In Request.');

                dd('Super Attributes Missing From the Request Parameters.');

                return redirect()->back();
            }
        }

        if($product->type == 'configurable') {
            //Check if the product is salable
            $child = $this->product->findOneByField('id', $data['selected_configurable_option']);

            $parentData = [
                'sku' => $product->sku,
                'product_id' => $productId,
                'quantity' => $data['quantity'],
                'type' => 'configurable',
                'name' => $product->name,
                'price' => ($price = $child->price), //This shoulf final price
                'base_price' => $price,
                'item_total' => $price * $data['quantity'],
                'base_item_total' => $price * $data['quantity'],
                'weight' => ($weight = $child->weight),
                'item_weight' => $weight * $parentData['quantity'],
                'base_item_weight' => $weight * $parentData['quantity'],
            ];

            

            $parentData['base_item_weight'] = $parentData['weight'] * $parentData['quantity'];

            //child row data
            $childData['product_id'] = $data['selected_configurable_option'];

            $childData['quantity'] = 1;

            $childData['sku'] = $this->product->findOneByField('id', $data['selected_configurable_option'])->sku;

            $childData['type'] = $this->product->findOneByField('id', $data['selected_configurable_option'])->type;

            $childData['name'] = $this->product->findOneByField('id', $data['selected_configurable_option'])->name;

            return ['parent' => $parentData, 'child' => $childData];
        } else {
            $data['product_id'] = $productId;
            unset($data['product']);

            $data['type'] = 'simple';

            $data['name'] = $this->product->findOneByField('id', $productId)->name;

            $data['price'] = $this->product->findOneByField('id', $productId)->price;

            $data['base_price'] = $data['price'];

            $data['item_total'] = $data['price'] * $data['quantity'];

            $data['base_item_total'] = $data['price'] * $data['quantity'];

            $data['weight'] = $this->product->findOneByField('id', $productId)->weight;

            $data['item_weight'] = $data['weight'] * $data['quantity'];

            $data['base_item_weight'] = $data['weight'] * $data['quantity'];

            return ['parent' => $data, 'child' => null];
        }
    }

    /**
     * Add Items in a cart with some cart and item details.
     *
     * @param @id
     * @param $data
     *
     * @return void
     */
    public function add($id, $data)
    {
        // session()->forget('cart');

        // return redirect()->back();

        $itemData = $this->prepareItemData($id, $data);

        if(session()->has('cart')) {
            $cart = session()->get('cart');

            $cartItems = $cart->items()->get();

            if(isset($cartItems)) {
                foreach($cartItems as $cartItem) {
                    if($data['is_configurable'] == "false") {

                        if($cartItem->product_id == $id) {
                            $prevQty = $cartItem->quantity;

                            $newQty = $data['quantity'];

                            $cartItem->update(['quantity' => $prevQty + $newQty]);

                            session()->flash('success', "Product Quantity Successfully Updated");

                            return redirect()->back();
                        }
                    } else if($data['is_configurable'] == "true") {

                        //check the parent and child records that holds info abt this product.
                        if($cartItem->product_id == $data['selected_configurable_option']) {
                            $child = $cartItem;

                            $parentId = $child->parent_id;

                            $parent = $this->cartItem->findOneByField('id', $parentId);

                            $parentPrice = $parent->price;

                            $prevQty = $parent->quantity;

                            $newQty = $data['quantity'];

                            $parent->update(['quantity' => $prevQty + $newQty, 'item_total' => $parentPrice * ($prevQty + $newQty)]);

                            session()->flash('success', "Product Quantity Successfully Updated");

                            return redirect()->back();
                        }
                    }
                }

                $parent = $cart->items()->create($itemData['parent']);

                $itemData['child']['parent_id'] = $parent->id;

                $cart->items()->create($itemData['child']);

                session()->flash('success', 'Item Successfully Added To Cart');

                return redirect()->back();
            } else {
                if(isset($cart)) {
                    $this->cart->delete($cart->id);
                } else {
                    $this->createNewCart($id, $data);
                }
            }
        } else {
            $this->createNewCart($id, $data);
        }
    }

    /**
     * Use detach to remove the current product from cart tables
     *
     * @param Integer $id
     * @return Mixed
     */
    public function remove($id)
    {

        dd("Removing Item from Cart");
    }

    /**
     * This function handles when guest has some of cart products and then logs in.
     *
     * @return Response
     */
    public function mergeCart()
    {
        if(session()->has('cart')) {
            $cart = session()->get('cart');

            $cartItems = $cart->items;

            $customerCart = $this->cart->findOneByField('customer_id', auth()->guard('customer')->user()->id);

            if(isset($customerCart)) {
                $customerCartItems = $this->cart->items($customerCart['id']);

                if(isset($customerCart)) {
                    foreach($cartItems as $key => $cartItem) {
                        // foreach($customerCartItems as $customerCartItem) {

                        //     // dd($customerCartItems, $cartItems[0]->parent_id);

                        //     if($cartItem->type == "simple" && $cartItem->parent_id == "null") {
                        //         if($customerCartItem->type == "simple" && $customerCartItem->parent_id == "null") {
                        //             //update the customer cart item details and delete the guest instance

                        //             if($customerCartItem->product_id == $cartItem->productId) {

                        //                 $prevQty = $cartItem->quantity;
                        //                 $newQty = $customerCartItem->quantity;

                        //                 $customerCartItem->update([
                        //                     'quantity' => $prevQty + $newQty,
                        //                     'item_total' => $customerCartItem->price * ($prevQty + $newQty),
                        //                     'base_item_total' => $customerCartItem->price * ($prevQty + $newQty),
                        //                     'item_total_weight' => $customerCartItem->weight * ($prevQty + $newQty),
                        //                     'base_item_total_weight' => $customerCartItem->weight * ($prevQty + $newQty)
                        //                 ]);

                        //                 $cartItems->forget($key);
                        //             }
                        //         }

                        //     } else if($cartItem->type == "simple" && $cartItem->parent_id != "null") {

                        //         if($customerCartItem->type == "simple" && $customerCartItem->parent_id != "null") {
                        //             //guest cartParent
                        //             $cartItemParentId = $cartItem->parent_id;
                        //             $cartItemParent = $this->cartItem->findOneByField('id', $cartItemParentId);

                        //             //customer cartParent
                        //             $customerItemParentId = $customerCartItem->parent_id;
                        //             $customerItemParent = $this->cartItem->findOneByField('id', $customerItemParentId);

                        //             if($cartItem->product_id == $customerCartItem->product_id) {
                        //                 $cartItemQuantity = $cartItemParent->quantity;

                        //                 $customerCartItemQuantity = $customerItemParent->quantity;

                        //                 $customerCartItem->update([
                        //                     'quantity' => $cartItemQuantity + $customerCartItemQuantity,
                        //                     'item_total' => $customerItemParent->price * ($cartItemQuantity + $customerCartItemQuantity),
                        //                     'base_item_total' => $customerItemParent->price * ($cartItemQuantity + $customerCartItemQuantity),
                        //                     'item_total_weight' => $customerItemParent->weight * ($cartItemQuantity + $customerCartItemQuantity),
                        //                     'base_item_total_weight' => $customerItemParent->weight * ($cartItemQuantity + $customerCartItemQuantity),
                        //                 ]);

                        //                 $cartItems->forget($key);
                        //             }
                        //         }
                        //     }
                        // }
                    }

                    foreach($cartItems as $cartItem) {
                        $cartItem->update(['cart_id' => $customerCart['id']]);
                    }
                    $this->cart->delete($cart->id);

                    return redirect()->back();
                }
            } else {
                foreach($cartItems as $cartItem) {
                    $this->cart->update(['customer_id' => auth()->guard('customer')->user()->id], $cart->id);
                }

                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    /**
     * Destroys the session
     * maintained for cart
     * on customer logout.
     *
     * @return Mixed
     */
    public function destroyCart() {
        if(session()->has('cart')) {
            session()->forget('cart');
            return redirect()->back();
        }
    }

    /**
     * Returns cart
     *
     * @return Mixed
     */
    public function getCart()
    {
        if(!$cart = session()->get('cart'))
            return false;

        return $this->cart->find($cart->id);
    }

    /**
     * Returns cart
     *
     * @return Mixed
     */
    public function getItemAttributeOptionDetails($item)
    {
        $data = [];

        foreach($item->product->super_attributes as $attribute) {
            $option = $attribute->options()->where('id', $item->child->{$attribute->code})->first();

            $data['attributes'][$attribute->code] = [
                'attribute_name' => $attribute->name,
                'option_label' => $option->label,
            ];
        }

        return $data;
    }

    /**
     * Save customer address
     *
     * @return Mixed
     */
    public function saveCustomerAddress($data)
    {
        if(!$cart = $this->getCart())
            return false;

        $billingAddress = $data['billing'];
        $shippingAddress = $data['shipping'];
        $billingAddress['cart_id'] = $shippingAddress['cart_id'] = $cart->id;

        if($billingAddressModel = $cart->biling_address) {
            $this->cartAddress->update($billingAddress, $billingAddressModel->id);

            if($shippingAddress = $cart->shipping_address) {
                if(isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping']) {
                    $this->cartAddress->update($billingAddress, $shippingAddress->id);
                } else {
                    $this->cartAddress->update($shippingAddress, $shippingAddress->id);
                }
            } else {
                if(isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping']) {
                    $this->cartAddress->create(array_merge($billingAddress, ['address_type' => 'shipping']));
                } else {
                    $this->cartAddress->create(array_merge($shippingAddress, ['address_type' => 'shipping']));
                }
            }
        } else {
            $this->cartAddress->create(array_merge($billingAddress, ['address_type' => 'billing']));

            if(isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping']) {
                $this->cartAddress->create(array_merge($billingAddress, ['address_type' => 'shipping']));
            } else {
                $this->cartAddress->create(array_merge($shippingAddress, ['address_type' => 'shipping']));
            }
        }

        return true;
    }

    /**
     * Save shipping method for cart
     *
     * @param string $shippingMethodCode
     * @return Mixed
     */
    public function saveShippingMethod($shippingMethodCode)
    {
        if(!$cart = $this->getCart())
            return false;

        $cart->shipping_method = $shippingMethodCode;
        $cart->save();

        // foreach($cart->shipping_rates as $rate) {
        //     if($rate->method != $shippingMethodCode) {
        //         $rate->delete();
        //     }
        // }

        return true;
    }

    /**
     * Save payment method for cart
     *
     * @param string $payment
     * @return Mixed
     */
    public function savePaymentMethod($payment)
    {
        if(!$cart = $this->getCart())
            return false;

        if($cartPayment = $cart->payment)
            $cartPayment->delete();

        $cartPayment = new CartPayment;

        $cartPayment->method = $payment['method'];
        $cartPayment->cart_id = $cart->id;
        $cartPayment->save();

        return $cartPayment;
    }

    /**
     * Updates cart totals
     *
     * @return void
     */
    public function collectTotals()
    {
        if(!$cart = $this->getCart())
            return false;

        $cart->grand_total = 0;
        $cart->base_grand_total = 0;
        $cart->sub_total = 0;
        $cart->base_sub_total = 0;
        $cart->sub_total_with_discount = 0;
        $cart->base_sub_total_with_discount = 0;

        foreach ($cart->items()->get() as $item) {
            $cart->grand_total = (float) $cart->grand_total + $item->total;
            $cart->base_grand_total = (float) $cart->base_grand_total + $item->base_total;

            $cart->sub_total = (float) $cart->sub_total + $item->total;
            $cart->base_sub_total = (float) $cart->base_sub_total + $item->base_total;
        }

        if($shipping = $cart->selected_shipping_rate) {
            $cart->grand_total = (float) $cart->grand_total + $shipping->price;
            $cart->base_grand_total = (float) $cart->base_grand_total + $shipping->base_price;
        }

        $cart->save();
    }
}