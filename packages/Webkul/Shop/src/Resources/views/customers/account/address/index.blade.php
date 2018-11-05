@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.address.index.page-title') }}
@endsection

@section('content-wrapper')

<div class="account-content">

    @include('shop::customers.account.partials.sidemenu')

    <div class="account-layout">

        <div class="account-head">
            <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i class="icon icon-menu-back"></i></a></span>
            <span class="account-heading">{{ __('shop::app.customer.account.address.index.title') }}</span>

            @if(!$addresses->isEmpty())
                <span class="account-action">
                    <a href="{{ route('customer.address.create') }}">{{ __('shop::app.customer.account.address.index.add') }}</a>
                </span>
            @else
                <span></span>
            @endif
            <div class="horizontal-rule"></div>
        </div>

        <div class="account-table-content">
            @if($addresses->isEmpty())
                <div>{{ __('shop::app.customer.account.address.index.empty') }}</div>
                <br/>
                <a href="{{ route('customer.address.create') }}">{{ __('shop::app.customer.account.address.index.add') }}</a>
            @else
                <div class="address-holder">
                    @foreach($addresses as $address)
                        <div class="address-card-1">
                            <div class="details">
                                <span class="bold">{{ auth()->guard('customer')->user()->name }}</span>
                                {{ $address->name }}</br>
                                {{ $address->address1 }}, {{ $address->address2 ? $address->address2 . ',' : '' }}</br>
                                {{ $address->city }}</br>
                                {{ $address->state }}</br>
                                {{ country()->name($address->country) }} {{ $address->postcode }}</br></br>
                                {{ __('shop::app.customer.account.address.index.contact') }} : {{ $address->phone }} 

                                <div class="control-links mt-20">
                                    <span>
                                        <a href="{{ route('customer.address.edit', $address->id) }}">
                                            {{ __('shop::app.customer.account.address.index.edit') }}
                                        </a>
                                    </span>

                                    <span>
                                        <a href="{{ route('address.delete', $address->id) }}">
                                            {{ __('shop::app.customer.account.address.index.delete') }}
                                        </a>
                                    </span>
                                </div>

                                @if($address->default_address)
                                    <span class="default-address badge badge-md badge-success">{{ __('shop::app.customer.account.address.index.default') }}</span>
                                @else
                                    <div class="make-default mt-20">
                                        <a href="{{ route('make.default.address', $address->id) }}" class="btn btn-md btn-primary">{{ __('shop::app.customer.account.address.index.make-default') }}</a>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
