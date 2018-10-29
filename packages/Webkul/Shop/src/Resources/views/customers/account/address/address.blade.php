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
                        <div class="address-card">
                            {{-- <div class="control-group">
                                <label class="radio-container">
                                    <input class="control" type="radio" name="radio">
                                    <span class="checkmark"></span>
                                </label>
                            </div> --}}

                            <div class="details">
                                <span class="bold">{{ auth()->guard('customer')->user()->name }}</span>
                                <span>{{ $address['address1'] }}</span>
                                <span>{{ $address['address2'] }}</span>
                                <span>{{ $address['country'] }}</span>
                                <span>{{ $address['state'] }}</span>
                                <span>{{ $address['city'] }}</span>
                                <span>{{ $address['postcode'] }}</span>

                                <div class="control-links mt-20">
                                    <span>
                                        <a href="{{ route('customer.address.edit', $address['id']) }}"><i class="icon pencil-lg-icon"></i></a>
                                    </span>

                                    <span>
                                        <a href="{{ route('address.delete', $address['id']) }}"><i class="icon trash-icon"></i></a>
                                    </span>
                                </div>

                                @if($address['default_address'] == 1)
                                    <span class="default-address badge badge-md badge-success">{{ __('shop::app.customer.account.address.index.default') }}</span>
                                @else
                                    <div class="make-default mt-20">
                                        <a href="{{ route('make.default.address', $address['id']) }}" class="btn btn-md btn-primary">{{ __('shop::app.customer.account.address.index.make-default') }}</a>
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
