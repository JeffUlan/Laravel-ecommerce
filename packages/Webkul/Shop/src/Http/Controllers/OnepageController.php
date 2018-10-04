<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Webkul\Checkout\Facades\Cart;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Payment\Facades\Payment;
use Webkul\Checkout\Http\Requests\CustomerAddressForm;
use Webkul\Sales\Repositories\OrderRepository;

/**
 * Chekout controller for the customer and guest for placing order
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OnepageController extends Controller
{
    /**
     * OrderRepository object
     *
     * @var array
     */
    protected $orderRepository;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * @return void
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        if(!$cart = Cart::getCart())
            return redirect()->route('shop.checkout.cart.index');

        return view($this->_config['view'])->with('cart', $cart);
    }

    /**
     * Saves customer address.
     *
     * @param  \Webkul\Checkout\Http\Requests\CustomerAddressForm $request
     * @return \Illuminate\Http\Response
    */
    public function saveAddress(CustomerAddressForm $request)
    {
        if(!Cart::saveCustomerAddress(request()->all()) || !$rates = Shipping::collectRates())
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);

        return response()->json($rates);
    }

    /**
     * Saves shipping method.
     *
     * @return \Illuminate\Http\Response
    */
    public function saveShipping()
    {
        $shippingMethod = request()->get('shipping_method');

        if(!$shippingMethod || !Cart::saveShippingMethod($shippingMethod))
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);

        Cart::collectTotals();

        return response()->json(Payment::getSupportedPaymentMethods());
    }

    /**
     * Saves payment method.
     *
     * @return \Illuminate\Http\Response
    */
    public function savePayment()
    {
        $payment = request()->get('payment');

        if(!$payment || !Cart::savePaymentMethod($payment))
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);

        $cart = Cart::getCart();

        return response()->json([
                'jump_to_section' => 'review',
                'html' => view('shop::checkout.onepage.review', compact('cart'))->render()
            ]);
    }

    /**
     * Saves order.
     *
     * @return \Illuminate\Http\Response
    */
    public function saveOrder()
    {
        Cart::collectTotals();

        $this->validateOrder();

        $order = $this->orderRepository->create([]);

        session()->flash('order', $order);

        return response()->json([
                'success' => true
            ]);
    }

    /**
     * Order success page
     *
     * @return \Illuminate\Http\Response
    */
    public function success()
    {
        if(!$order = session('order'))
            return redirect()->route('shop.checkout.cart.index');

        return view($this->_config['view'], compact('order'));
    }

    /**
     * Validate order before creation
     *
     * @return mixed
     */
    public function validateOrder()
    {
    }
}