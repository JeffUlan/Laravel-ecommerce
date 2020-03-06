<div class="customer-sidebar row no-margin no-padding">
    <div class="account-details col-12">
        <div class="customer-name col-12 text-uppercase">
            {{ substr(auth('customer')->user()->first_name, 0, 1) }}
        </div>
        <div class="col-12 customer-name-text text-capitalize text-break">{{ auth('customer')->user()->first_name . ' ' . auth('customer')->user()->last_name}}</div>
        <div class="customer-email col-12 text-break">{{ auth('customer')->user()->email }}</div>
    </div>

    @foreach ($menu->items as $menuItem)
        <ul type="none" class="navigation">
            {{-- rearrange menu items --}}
            @php
                $subMenuCollection = [];

                try {
                    $subMenuCollection['profile'] = $menuItem['children']['profile'];
                    $subMenuCollection['orders'] = $menuItem['children']['orders'];
                    $subMenuCollection['downloadables'] = $menuItem['children']['downloadables'];
                    $subMenuCollection['wishlist'] = $menuItem['children']['wishlist'];
                    $subMenuCollection['compare'] = [
                        'key'   => 'account.compare',
                        'url'   => route('velocity.product.compare'),
                        'name'  => 'velocity::app.customer.compare.text',
                    ];
                    $subMenuCollection['reviews'] = $menuItem['children']['reviews'];
                    $subMenuCollection['address'] = $menuItem['children']['address'];
                } catch (\Exception $exception) {
                    $subMenuCollection = $menuItem['children'];
                }
            @endphp

            @foreach ($subMenuCollection as $index => $subMenuItem)
                <li class="{{ $menu->getActive($subMenuItem) }}" title="{{ trans($subMenuItem['name']) }}">
                    <a class="unset fw6 full-width" href="{{ $subMenuItem['url'] }}">
                        <i class="icon {{ $index }} text-down-3"></i>
                        <span>{{ trans($subMenuItem['name']) }}<span>
                        <i class="rango-arrow-right pull-right text-down-3"></i>
                    </a>
                </li>
            @endforeach
        </ul>
    @endforeach
</div>

@push('css')
    <style type="text/css">
        .main-content-wrapper {
            margin-bottom: 0px;
            min-height: 100vh;
        }
    </style>
@endpush
