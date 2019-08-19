<?php

namespace Webkul\Product\Type;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Helpers\Price;
use Webkul\Product\Helpers\ProductImage;
use Illuminate\Support\Facades\Storage;
use Cart;

/**
 * Abstract class Type
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
abstract class AbstractType
{
    /**
     * AttributeRepository instance
     *
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * ProductRepository instance
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * ProductAttributeValueRepository instance
     *
     * @var ProductAttributeValueRepository
     */
    protected $attributeValueRepository;

    /**
     * ProductInventoryRepository instance
     *
     * @var ProductInventoryRepository
     */
    protected $productInventoryRepository;

    /**
     * ProductImageRepository instance
     *
     * @var ProductImageRepository
     */
    protected $productImageRepository;

    /**
     * Product price helper instance
     * 
     * @var Price
    */
    protected $priceHelper;

    /**
     * Product Image helper instance
     * 
     * @var ProductImage
    */
    protected $productImageHelper;

    /**
     * Product model instance
     *
     * @var Product
     */
    protected $product;

    /**
     * Create a new product type instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository           $attributeRepository
     * @param  Webkul\Product\Repositories\ProductRepository               $productRepository
     * @param  Webkul\Product\Repositories\ProductAttributeValueRepository $attributeValueRepository
     * @param  Webkul\Product\Repositories\ProductInventoryRepository      $productInventoryRepository
     * @param  Webkul\Product\Repositories\ProductImageRepository          $productImageRepository
     * @param  Webkul\Product\Helpers\Price                                $priceHelper
     * @param  Webkul\Product\Helpers\ProductImage                         $productImageHelper
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,
        ProductInventoryRepository $productInventoryRepository,
        ProductImageRepository $productImageRepository,
        Price $priceHelper,
        ProductImage $productImageHelper
    )
    {
        $this->attributeRepository = $attributeRepository;

        $this->productRepository = $productRepository;

        $this->attributeValueRepository = $attributeValueRepository;

        $this->productInventoryRepository = $productInventoryRepository;

        $this->productImageRepository = $productImageRepository;

        $this->priceHelper = $priceHelper;

        $this->productImageHelper = $productImageHelper;
    }

    /**
     * @param array $data
     * @return Product
     */
    public function create(array $data)
    {
        return $this->productRepository->getModel()->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return Product
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $product = $this->productRepository->find($id);

        $product->update($data);

        foreach ($product->attribute_family->custom_attributes as $attribute) {
            if (! isset($data[$attribute->code]) || (in_array($attribute->type, ['date', 'datetime']) && ! $data[$attribute->code]))
                continue;

            if ($attribute->type == 'multiselect')
                $data[$attribute->code] = implode(",", $data[$attribute->code]);

            if ($attribute->type == 'image' || $attribute->type == 'file') {
                $data[$attribute->code] = gettype($data[$attribute->code]) == 'object'
                        ? request()->file($attribute->code)->store('product/' . $product->id)
                        : NULL;
            }

            $attributeValue = $this->attributeValueRepository->findOneWhere([
                    'product_id' => $product->id,
                    'attribute_id' => $attribute->id,
                    'channel' => $attribute->value_per_channel ? $data['channel'] : null,
                    'locale' => $attribute->value_per_locale ? $data['locale'] : null
                ]);

            if (! $attributeValue) {
                $this->attributeValueRepository->create([
                    'product_id' => $product->id,
                    'attribute_id' => $attribute->id,
                    'value' => $data[$attribute->code],
                    'channel' => $attribute->value_per_channel ? $data['channel'] : null,
                    'locale' => $attribute->value_per_locale ? $data['locale'] : null
                ]);
            } else {
                $this->attributeValueRepository->update([
                    ProductAttributeValue::$attributeTypeFields[$attribute->type] => $data[$attribute->code]
                    ], $attributeValue->id
                );

                if ($attribute->type == 'image' || $attribute->type == 'file')
                    Storage::delete($attributeValue->text_value);
            }
        }

        if (request()->route()->getName() != 'admin.catalog.products.massupdate') {
            if  (isset($data['categories']))
                $product->categories()->sync($data['categories']);

            $product->up_sells()->sync($data['up_sell'] ?? []);

            $product->cross_sells()->sync($data['cross_sell'] ?? []);

            $product->related_products()->sync($data['related_products'] ?? []);

            $this->productInventoryRepository->saveInventories($data, $product);

            $this->productImageRepository->uploadImages($data, $product);
        }

        return $product;
    }

    /**
     * Specify type instance product
     *
     * @param  Product $product
     * @return AbstractType
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Return true if this product type is saleable
     *
     * @return boolean
     */
    public function isSaleable()
    {
        if (! $this->product->status)
            return false;
            
        return true;
    }

    /**
     * Return true if this product can have inventory
     *
     * @return boolean
     */
    public function isStockable()
    {
        return false;
    }

    /**
     * @param integer $qty
     * @return bool
     */
    public function haveSufficientQuantity($qty)
    {
        return true;
    }

    /**
     * @param CartItem $cartItem
     * @return bool
     */
    public function isItemHaveQuantity($cartItem)
    {
        return $cartItem->product->getTypeInstance()->haveSufficientQuantity($cartItem->quantity);
    }

    /**
     * Return true if item can be moved to cart from wishlist
     *
     * @return boolean
     */
    public function canBeMovedFromWishlistToCart($item)
    {
        return true;
    }

    /**
     * Retrieve product attributes
     *
     * @param Group $group
     * @param bool  $skipSuperAttribute
     * @return Collection
     */
    public function getEditableAttributes($group = null, $skipSuperAttribute = true)
    {
        if ($skipSuperAttribute)
            $this->skipAttributes = array_merge($this->product->super_attributes->pluck('code')->toArray(), $this->skipAttributes);

        if (! $group)
            return $this->product->attribute_family->custom_attributes()->whereNotIn('attributes.code', $this->skipAttributes)->get();

        return $group->custom_attributes()->whereNotIn('code', $this->skipAttributes)->get();
    }

    /**
     * Returns additional views
     *
     * @return array
     */
    public function getAdditionalViews()
    {
        return $this->additionalViews;
    }

    /**
     * Returns validation rules
     *
     * @return array
     */
    public function getTypeValidationRules()
    {
        return [];
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param array $data
     * @return array
     */
    public function prepareForCart($data)
    {
        $data = $this->getQtyRequest($data);

        if ($this->isStockable() && ! $this->haveSufficientQuantity($data['quantity']))
            return trans('shop::app.checkout.cart.quantity.inventory_warning');

        $price = $this->priceHelper->getMinimalPrice($this->product);

        $products = [
            [
                'product_id' => $this->product->id,
                'sku' => $this->product->sku,
                'quantity' => $data['quantity'],
                'name' => $this->product->name,
                'price' => $convertedPrice = core()->convertPrice($price),
                'base_price' => $price,
                'total' => $convertedPrice * $data['quantity'],
                'base_total' => $price * $data['quantity'],
                'weight' => $this->product->weight ?? 0,
                'total_weight' => ($this->product->weight ?? 0) * $data['quantity'],
                'base_total_weight' => ($this->product->weight ?? 0) * $data['quantity'],
                'type' => $this->product->type,
                'additional' => $this->getAdditionalOptions($data)
            ]
        ];

        return $products;
    }

    /**
     * Get request quantity
     *
     * @param Product $product
     * @param array   $data
     * @return CartItem|void
     */
    public function getQtyRequest($data)
    {
        if ($item = Cart::getItemByProduct(['additional' => $data]))
            $data['quantity'] += $item->quantity;

        return $data;
    }
    
    /**
     * Check if product can be configured
     * 
     * @return boolean
     */
    public function canConfigure()
    {
        return false;
    }
    
    /**
     *
     * @param array $options1
     * @param array $options2
     * @return boolean
     */
    public function compareOptions($options1, $options2)
    {
        return $this->product->id == $options2['product_id'];
    }
    
    /**
     * Returns additional information for items
     *
     * @param array $data
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        return $data;
    }

    /**
     * Get actual ordered item
     *
     * @param CartItem $item
     * @return CartItem|OrderItem|InvoiceItem|ShipmentItem|Wishlist
     */
    public function getOrderedItem($item)
    {
        return $item;
    }

    /**
     * Get product base image
     *
     * @param Wishlist|CartItem $item
     * @return array
     */
    public function getBaseImage($item)
    {
        return $this->productImageHelper->getProductBaseImage($item->product);
    }

    /**
     * Get product base image
     *
     * @param CartItem $item
     * @return float
     */
    public function getMinimalPrice($item)
    {
        return $this->priceHelper->getMinimalPrice($item->product);
    }
}