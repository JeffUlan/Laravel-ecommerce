<div class="product-price">

    @inject ('priceHelper', 'Webkul\Shop\Product\Price')

    @if ($product->type == 'configurable')

        <span class="price-label">{{ __('shop::app.products.price-label') }}</span>

        <span>{{ core()->currency($priceHelper->getMinimalPrice($product)) }}</span>    

    @else

        @if ($priceHelper->haveSpecialPrice($product))

            <span class="regular-price">{{ core()->currency($product->price) }}</span>

            <span class="special-price">{{ core()->currency($priceHelper->getSpecialPrice($product)) }}</span>

        @else

            <span>{{ core()->currency($product->price) }}</span>

        @endif

    @endif

</div>