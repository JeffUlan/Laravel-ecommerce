@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.addresses.edit-title') }}
@stop


@section('content-wrapper')

    <div class="content full-page">
        {!! view_render_event('admin.customer.addresses.edit.before', ['address' => $address]) !!}

            <form method="post" action="{{ route('admin.customer.addresses.update', $address->id) }}" @submit.prevent="onSubmit">
                <div class="page-header">
                    <div class="page-title">
                        <h1>{{ __('admin::app.customers.addresses.edit-title') }}</h1>
                    </div>
                    
                    <div class="page-action">
                        <button type="submit" class="btn btn-primary btn-lg">
                            {{ __('admin::app.customers.addresses.save-btn-title') }}
                        </button>
                    </div>
                </div>
    
                <div class="page-content">
                    @csrf()
    
                    <input type="hidden" name="_method" value="PUT">

                    <input type="hidden" name="customer_id" value="{{ $address->customer_id }}">

                    <accordian :title="'{{ __('admin::app.customers.addresses.general') }}'" :active="true">
                        <div slot="body">

                            <?php $addresses = explode(PHP_EOL, $address->address1); ?>

                            <div class="control-group" :class="[errors.has('address1[]') ? 'has-error' : '']">
                                <label for="address_0" class="required">{{ __('shop::app.customer.account.address.edit.street-address') }}</label>
                                <input type="text" class="control" name="address1[]" id="address_0" v-validate="'required'" value="{{ isset($addresses[0]) ? $addresses[0] : '' }}" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.street-address') }}&quot;">
                                <span class="control-error" v-if="errors.has('address1[]')">@{{ errors.first('address1[]') }}</span>
                            </div>

                            @if (core()->getConfigData('customer.settings.address.street_lines') && core()->getConfigData('customer.settings.address.street_lines') > 1)
                                <div class="control-group" style="margin-top: -25px;">
                                    @for ($i = 1; $i < core()->getConfigData('customer.settings.address.street_lines'); $i++)
                                        <input type="text" class="control" name="address1[{{ $i }}]" id="address_{{ $i }}" value="{{ isset($addresses[$i]) ? $addresses[$i] : '' }}">
                                    @endfor
                                </div>
                            @endif

                            <div class="control-group" :class="[errors.has('city') ? 'has-error' : '']">
                                <label for="city" class="required">{{ __('shop::app.customer.account.address.create.city') }}</label>
                                <input type="text" class="control" name="city" v-validate="'required|alpha_spaces'" value="{{ $address->city }}" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.city') }}&quot;">
                                <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
                            </div>

                            @include ('shop::customers.account.address.country-state', ['countryCode' => old('country') ?? $address->country, 'stateCode' => old('state') ?? $address->state])

                            <div class="control-group" :class="[errors.has('postcode') ? 'has-error' : '']">
                                <label for="postcode" class="required">{{ __('shop::app.customer.account.address.create.postcode') }}</label>
                                <input type="text" class="control" name="postcode" v-validate="'required'" value="{{ $address->postcode }}" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.postcode') }}&quot;">
                                <span class="control-error" v-if="errors.has('postcode')">@{{ errors.first('postcode') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                                <label for="phone" class="required">{{ __('shop::app.customer.account.address.create.phone') }}</label>
                                <input type="text" class="control" name="phone" v-validate="'required'" value="{{ $address->phone }}" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.phone') }}&quot;">
                                <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                            </div>

                            <div class="control-group">
                                <span class="checkbox">
                                    <input type="checkbox" class="control" id="default_address" name="default_address" {{ $address->default_address ? 'checked' : '' }} >

                                    <label class="checkbox-view" for="default_address"></label>
                                    {{ __('admin::app.customers.addresses.default-address') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </accordian>
            </form>


        {!! view_render_event('admin.customer.addresses.edit.after', ['address' => $address]) !!}
    </div>
@stop