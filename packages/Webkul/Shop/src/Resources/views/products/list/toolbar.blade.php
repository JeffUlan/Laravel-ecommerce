@inject ('toolbarHelper', 'Webkul\Product\Product\Toolbar')

<div class="top-toolbar">

    <div class="page-info">
        <span>
            {{ __('shop::app.products.pager-info', ['showing' => $products->firstItem() . '-' . $products->lastItem(), 'total' => $products->total()]) }}
        </span>

        <span>Men</span>
    </div>

    <div class="pager">

        <div class="view-mode">
            @if ($toolbarHelper->isModeActive('grid'))
                <span class="grid-view">
                    <i class="icon grid-view-icon"></i>
                </span>
            @else
                <a href="{{ $toolbarHelper->getModeUrl('grid') }}" class="grid-view">
                    <i class="icon grid-view-icon"></i>
                </a>
            @endif

            @if ($toolbarHelper->isModeActive('list'))
                <span class="list-view">
                    <i class="icon list-view-icon"></i>
                </span>
            @else
                <a href="{{ $toolbarHelper->getModeUrl('list') }}" class="list-view">
                    <i class="icon list-view-icon"></i>
                </a>
            @endif

            <div class="sort-filter">

                <i class="icon sort-icon"></i>

                <i class="icon filter-icon"></i>

            </div>

        </div>

        <div class="sorter">
            <label>{{ __('shop::app.products.sort-by') }}</label>

            <select onchange="window.location.href = this.value">

                @foreach ($toolbarHelper->getAvailableOrders() as $key => $order)

                    <option value="{{ $toolbarHelper->getOrderUrl($key) }}" {{ $toolbarHelper->isOrderCurrent($key) ? 'selected' : '' }}>
                        {{ __('shop::app.products.' . $order) }}
                    </option>

                @endforeach

            </select>
        </div>

        <div class="limiter">
            <label>{{ __('shop::app.products.show') }}</label>

            <select onchange="window.location.href = this.value">

                @foreach ($toolbarHelper->getAvailableLimits() as $limit)

                    <option value="{{ $toolbarHelper->getLimitUrl($limit) }}" {{ $toolbarHelper->isLimitCurrent($limit) ? 'selected' : '' }}>
                        {{ $limit }}
                    </option>

                @endforeach

            </select>
        </div>

    </div>

</div>