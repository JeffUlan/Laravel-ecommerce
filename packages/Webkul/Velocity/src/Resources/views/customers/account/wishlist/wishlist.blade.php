@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.review.index.page-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head">
        <span class="account-heading">{{ __('shop::app.customer.account.wishlist.title') }}</span>

        @if (count($items))
            <div class="account-action pull-right">
                <a
                    class="remove-decoration theme-btn light"
                    href="{{ route('customer.wishlist.removeall') }}">
                    {{ __('shop::app.customer.account.wishlist.deleteall') }}
                </a>
            </div>
        @endif
    </div>

    {!! view_render_event('bagisto.shop.customers.account.wishlist.list.before', ['wishlist' => $items]) !!}

    <div class="filters-container">
        @include ('shop::products.list.toolbar')
    </div>

    <div class="account-items-list row col-12 wishlist-container">

        @if ($items->count())
            @foreach ($items as $item)
                @php
                    $currentMode = $toolbarHelper->getCurrentMode();
                @endphp

                @if ($currentMode == "grid")
                    <div class="col-3">
                @endif
                    @include ('shop::products.list.card', [
                        'checkmode' => true,
                        'product' => $item->product
                    ])
                @if ($currentMode == "grid")
                    </div>
                @endif
            @endforeach
        @else
            <div class="empty">
                {{ __('customer::app.wishlist.empty') }}
            </div>
        @endif
    </div>

    {!! view_render_event('bagisto.shop.customers.account.wishlist.list.after', ['wishlist' => $items]) !!}
@endsection
