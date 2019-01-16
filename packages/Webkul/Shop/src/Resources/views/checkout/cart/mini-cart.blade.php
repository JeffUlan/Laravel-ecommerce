@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

<?php $cart = cart()->getCart(); ?>

@if ($cart)
    <?php $items = $cart->items; ?>

    <div class="dropdown-toggle">
        <div style="display: inline-block; cursor: pointer;">
            <span class="name">
                Cart
                <span class="count"> ({{ $cart->items->count() }})</span>
            </span>
        </div>

        <i class="icon arrow-down-icon active"></i>
    </div>

    <div class="dropdown-list" style="display: none; top: 50px; right: 0px">
        <div class="dropdown-container">
            <div class="dropdown-cart">
                <div class="dropdown-header">
                    <p class="heading">
                        {{ __('shop::app.checkout.cart.cart-subtotal') }} -
                        {{ core()->currency($cart->base_sub_total) }}
                    </p>
                </div>

                <div class="dropdown-content">
                    @foreach ($items as $item)
                        {{-- @if ($item->type == "configurable") --}}
                            <div class="item">
                                <div class="item-image" >
                                    <?php
                                        if ($item->type == "configurable")
                                            $images = $productImageHelper->getProductBaseImage($item->child->product);
                                        else
                                            $images = $productImageHelper->getProductBaseImage($item->product);
                                    ?>
                                    <img src="{{ $images['small_image_url'] }}" />
                                </div>

                                <div class="item-details">
                                    {{-- @if ($item->type == "configurable")
                                        <div class="item-name">{{ $item->child->name }}</div>
                                    @else --}}
                                        <div class="item-name">{{ $item->name }}</div>
                                    {{-- @endif --}}

                                    @if ($item->type == "configurable")
                                        <div class="item-options">
                                            {{ trim(Cart::getProductAttributeOptionDetails($item->child->product)['html']) }}
                                        </div>
                                    @endif

                                    <div class="item-price">{{ core()->currency($item->base_total) }}</div>

                                    <div class="item-qty">Quantity - {{ $item->quantity }}</div>
                                </div>
                            </div>
                    @endforeach
                </div>

                <div class="dropdown-footer">
                    <a href="{{ route('shop.checkout.cart.index') }}">{{ __('shop::app.minicart.view-cart') }}</a>

                    <a class="btn btn-primary btn-lg" style="color: white;" href="{{ route('shop.checkout.onepage.index') }}">{{ __('shop::app.minicart.checkout') }}</a>
                </div>
            </div>
        </div>
    </div>

@else

    <div class="dropdown-toggle">
        <div style="display: inline-block; cursor: pointer;">

            <span class="name">{{ __('shop::app.minicart.cart') }}<span class="count"> ({{ __('shop::app.minicart.zero') }}) </span></span>
        </div>
    </div>
@endif