@php
    $newProducts = app('Webkul\Product\Repositories\ProductRepository')->getNewProducts(6)->items();

    $newProducts = array_merge(array_merge($newProducts, $newProducts), $newProducts);

    $showRecentlyViewed = true;
@endphp

@if (! empty($newProducts))
    <div class="container-fluid popular-products no-padding">

        <card-list-header
            heading="{{ __('shop::app.home.new-products') }}"
            view-all="{{
                (sizeof($newProducts) > (isset($cardCount) ? $cardCount : 6))
                ? 'http://localhost/PHP/laravel/Bagisto/bagisto-clone/public/categories/category1'
                : false
            }}"
            scrollable="new-products-carousel"
        ></card-list-header>

        {!! view_render_event('bagisto.shop.new-products.before') !!}

        <div class="row flex-nowrap">
            @if ($showRecentlyViewed)
                @push('css')
                    <style>
                        .recently-viewed {
                            padding-right: 0px;
                        }
                    </style>
                @endpush

                <div class="col-9 no-padding">
                    <carousel-component
                        :slides-count="{{ sizeof($newProducts) }}"
                        slides-per-page="5"
                        id="new-products-carousel"
                        navigation-enabled="hide"
                        pagination-enabled="hide">

                        @foreach ($newProducts as $index => $product)

                            <slide slot="slide-{{ $index }}">
                                @include ('shop::products.list.card', ['product' => $product])
                            </slide>

                        @endforeach

                    </carousel-component>
                </div>

                @include ('shop::products.list.recently-viewed')
            @else
                <carousel-component
                    :slides-count="{{ sizeof($newProducts) }}"
                    slides-per-page="6"
                    id="new-products-carousel">

                    @foreach ($newProducts as $index => $product)

                        <slide slot="slide-{{ $index }}">
                            @include ('shop::products.list.card', ['product' => $product])
                        </slide>

                    @endforeach

                </carousel-component>
            @endif
        </div>

        {!! view_render_event('bagisto.shop.new-products.after') !!}
    </div>
@endif