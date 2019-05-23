@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotion.add-cart-rule') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.cart-rule.store') }}" @submit.prevent="onSubmit">
            @csrf

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.promotion.add-cart-rule') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.promotion.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    <cart-rule></cart-rule>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script type="text/x-template" id="cart-rule-form-template">
            <div>
                @csrf()

                <accordian :active="true" title="Information">
                    <div slot="body">
                        <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                            <label for="name" class="required">{{ __('admin::app.promotion.general-info.name') }}</label>

                            <input type="text" class="control" name="name" v-model="name" v-validate="'required'" value="{{ old('name') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.name') }}&quot;">

                            <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                            <label for="description">{{ __('admin::app.promotion.general-info.description') }}</label>

                            <textarea class="control" name="description" v-model="description" v-validate="'required'" value="{{ old('description') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.description') }}&quot;"></textarea>

                            <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('customer_groups[]') ? 'has-error' : '']">
                            <label for="customer_groups" class="required">{{ __('admin::app.promotion.general-info.cust-groups') }}</label>

                            <select type="text" class="control" name="customer_groups[]" v-model="customer_groups" v-validate="'required'" value="{{ old('customer_groups') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;" multiple="multiple">
                                <option disabled="disabled">Select Customer Groups</option>
                                @foreach(app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $channel)
                                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                @endforeach
                            </select>

                            <span class="control-error" v-if="errors.has('customer_groups[]')">@{{ errors.first('customer_groups[]') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('channels[]') ? 'has-error' : '']">
                            <label for="channels" class="required">{{ __('admin::app.promotion.general-info.channels') }}</label>

                            <select type="text" class="control" name="channels[]" v-model="channels" v-validate="'required'" value="{{ old('channels') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.channels') }}&quot;" multiple="multiple">
                                <option disabled="disabled">Select Channels</option>
                                @foreach(app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                @endforeach
                            </select>

                            <span class="control-error" v-if="errors.has('channels[]')">@{{ errors.first('channels[]') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('end_other_rules') ? 'has-error' : '']">
                            <label for="end_other_rules" class="required">{{ __('admin::app.promotion.general-info.end_other_rules') }}</label>

                            <select type="text" class="control" name="end_other_rules" v-model="end_other_rules" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.end_other_rules') }}&quot;">
                                <option disabled="disabled">Select option</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>

                            <span class="control-error" v-if="errors.has('end_other_rules')">@{{ errors.first('end_other_rules') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                            <label for="status" class="required">{{ __('admin::app.promotion.general-info.status') }}</label>

                            <select type="text" class="control" name="status" v-model="status" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.status') }}&quot;">
                                <option disabled="disabled">Select status</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>

                            <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('is_coupon') ? 'has-error' : '']">
                            <label for="customer_groups" class="required">{{ __('admin::app.promotion.general-info.is-coupon') }}</label>

                            <select type="text" class="control" name="is_coupon" v-model="is_coupon" v-validate="'required'" value="{{ old('is_coupon') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.is-coupon') }}&quot;">
                                <option value="0">{{ __('admin::app.promotion.general-info.is-coupon-yes') }}</option>
                                <option value="1">{{ __('admin::app.promotion.general-info.is-coupon-no') }}</option>
                            </select>

                            <span class="control-error" v-if="errors.has('is_coupon')">@{{ errors.first('is_coupon') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('coupon_code') ? 'has-error' : '']" v-if="uses_coupon && ! auto_generated">
                            <label for="coupon_code" class="required">Specific Coupon</label>

                            <input type="text" class="control" name="coupon_code" v-model="coupon_code" v-validate="'required'" value="{{ old('coupon_code') }}" data-vv-as="&quot;Specific Coupon&quot;">

                            <span class="control-error" v-if="errors.has('coupon_code')">@{{ errors.first('coupon_code') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('coupon_code') ? 'has-error' : '']" v-if="uses_coupon && ! auto_generated">
                            <label for="coupon_code" class="required">Specific Coupon</label>

                            <input type="text" class="control" name="coupon_code" v-model="coupon_code" v-validate="'required'" value="{{ old('coupon_code') }}" data-vv-as="&quot;Specific Coupon&quot;">

                            <span class="control-error" v-if="errors.has('coupon_code')">@{{ errors.first('coupon_code') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('is_autogenerated') ? 'has-error' : '']">
                            <label for="is_autogenerated" class="required">Specific Coupon</label>

                            <input type="checkbox" class="control" name="is_autogenerated" v-model="is_autogenerated" v-validate="'required'" value="{{ old('is_autogenerated') }}" data-vv-as="&quot;Specific Coupon&quot;">

                            <span class="control-error" v-if="errors.has('is_autogenerated')">@{{ errors.first('is_autogenerated') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('uses_per_cust') ? 'has-error' : '']">
                            <label for="uses_per_cust" class="required">{{ __('admin::app.promotion.general-info.uses-per-cust') }}</label>

                            <input type="number" step="1" class="control" name="uses_per_cust" v-model="uses_per_cust" v-validate="'required|numeric|min_value:1'" value="{{ old('uses_per_cust') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.uses-per-cust') }}&quot;">

                            <span class="control-error" v-if="errors.has('uses_per_cust')">@{{ errors.first('uses_per_cust') }}</span>
                        </div>

                        <datetime :name="starts_from">
                            <div class="control-group" :class="[errors.has('starts_from') ? 'has-error' : '']">
                                <label for="starts_from" class="required">{{ __('admin::app.promotion.general-info.starts-from') }}</label>

                                <input type="text" class="control" v-model="starts_from" name="starts_from" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.starts-from') }}&quot;">

                                <span class="control-error" v-if="errors.has('starts_from')">@{{ errors.first('starts_from') }}</span>
                            </div>
                        </datetime>

                        <datetime :name="starts_from">
                            <div class="control-group" :class="[errors.has('ends_till') ? 'has-error' : '']">
                                <label for="ends_till" class="required">{{ __('admin::app.promotion.general-info.ends-till') }}</label>

                                <input type="text" class="control" v-model="ends_till" name="ends_till" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.ends-till') }}&quot;">

                                <span class="control-error" v-if="errors.has('ends_till')">@{{ errors.first('ends_till') }}</span>
                            </div>
                        </datetime>

                        <div class="control-group" :class="[errors.has('priority') ? 'has-error' : '']">
                            <label for="priority" class="required">{{ __('admin::app.promotion.general-info.priority') }}</label>

                            <input type="number" class="control" step="1" name="priority" v-model="priority" v-validate="'required|numeric|min_value:1'" value="{{ old('priority') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.priority') }}&quot;">

                            <span class="control-error" v-if="errors.has('priority')">@{{ errors.first('priority') }}</span>
                        </div>
                    </div>
                </accordian>

                <accordian :active="true" title="Conditions">
                    <div slot="body">
                        <div class="add-condition">
                            <div class="control-group" :class="[errors.has('criteria') ? 'has-error' : '']">
                                <label for="criteria" class="required">{{ __('admin::app.promotion.general-info.add-condition') }}</label>

                                <select type="text" class="control" name="criteria" v-model="criteria" v-validate="'required'" value="{{ old('channels') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;">
                                    <option value="cart">Cart Properties</option>
                                </select>

                                <span class="control-error" v-if="errors.has('criteria')">@{{ errors.first('criteria') }}</span>
                            </div>

                            <span class="btn btn-primary btn-lg" v-on:click="addCondition">Add Condition</span>
                        </div>

                        <div class="condition-set">

                            <!-- Cart Attributes -->
                            <div v-for="(condition, index) in conditions_list" :key="index">
                                <div class="control-container mt-20">
                                    <div class="title-bar">
                                        <span>Cart Attribute is </span>
                                        <span class="icon cross-icon" v-on:click="removeCartAttr(index)"></span>
                                    </div>

                                    <div class="control-group mt-10" :key="index">
                                        <select class="control" name="cart_attributes[]" v-model="conditions_list[index].attribute" v-validate="'required'" title="You Can Make Multiple Selections Here" style="margin-right: 15px;" v-on:change="enableCondition($event, index)">
                                            <option disabled="disabled">Select Option</option>
                                            <option v-for="(cart_ip, index1) in cart_input" :value="cart_ip.code" :key="index1">@{{ cart_ip.name }}</option>
                                        </select>

                                        <div v-if='conditions_list[index].type == "string"'>
                                            <select class="control" name="cart_attributes[]" v-model="conditions_list[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                                <option v-for="(condition, index) in conditions.numeric" :value="index" :key="index">@{{ condition }}</option>
                                            </select>

                                            <div v-if='conditions_list[index].attribute == "shipping_state"'>
                                                <select class="control" v-validate="'required'" v-model="conditions_list[index].value">
                                                    <option disabled="disabled">Select State</option>
                                                    <optgroup v-for='(state, code) in country_and_states.states' :label="code">
                                                        <option v-for="(stateObj, index) in state" :value="stateObj.code">@{{ stateObj.default_name }}</option>
                                                    </optgroup>
                                                </select>
                                            </div>

                                            <div v-if='conditions_list[index].attribute == "shipping_country"'>
                                                <select class="control" v-validate="'required'" v-model="conditions_list[index].value">
                                                    <option disabled="disabled">Select Country</option>
                                                    <option v-for="(country, index) in country_and_states.countries" :value="country.code">@{{ country.name }}</option>
                                                </select>
                                            </div>

                                            <input type="text" class="control" name="cart_attributes[]" v-model="conditions_list[index].value" placeholder="Enter Value" v-if='conditions_list[index].attribute != "shipping_state" && conditions_list[index].attribute != "shipping_country"'>
                                        </div>

                                        <div v-if='conditions_list[index].type == "numeric"'>
                                            <select class="control" name="attributes[]" v-model="conditions_list[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                                <option v-for="(condition, index) in conditions.numeric" :value="index" :key="index">@{{ condition }}</option>
                                            </select>

                                            <input type="number" step="0.1000" class="control" name="cart_attributes[]" v-model="conditions_list[index].value" placeholder="Enter Value">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </accordian>

                <accordian :active="true" title="Actions">
                    <div slot="body">
                        <div class="control-group" :class="[errors.has('apply') ? 'has-error' : '']">
                            <label for="apply" class="required">Apply</label>

                            <select class="control" name="apply" v-model="apply" v-validate="'required'" value="{{ old('apply') }}" data-vv-as="&quot;Apply As&quot;" v-on:change="detectApply">
                                <option v-for="(action, index) in actions" :value="index">@{{ action }}</option>
                            </select>

                            <span class="control-error" v-if="errors.has('apply')">@{{ errors.first('apply') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('disc_amount') ? 'has-error' : '']" v-if="apply_amt">
                            <label for="disc_amount" class="required">{{ __('admin::app.promotion.general-info.disc_amt') }}</label>

                            <input type="number" step="1.0000" class="control" name="disc_amount" v-model="disc_amount" v-validate="'required|decimal|min_value:0.0001'" value="{{ old('disc_amount') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.disc_amt') }}&quot;">

                            <span class="control-error" v-if="errors.has('disc_amount')">@{{ errors.first('disc_amount') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('disc_percent') ? 'has-error' : '']" v-if="apply_prct">
                            <label for="disc_percent" class="required">{{ __('admin::app.promotion.general-info.disc_percent') }}</label>

                            <input type="number" step="0.5000" class="control" name="disc_percent" v-model="disc_percent" v-validate="'required|decimal|min_value:0.0001'" value="{{ old('disc_percent') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.disc_percent') }}&quot;">

                            <span class="control-error" v-if="errors.has('disc_percent')">@{{ errors.first('disc_percent') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('buy_atleast') ? 'has-error' : '']">
                            <label for="buy_atleast" class="required">{{ __('admin::app.promotion.cart.buy-atleast') }}</label>

                            <input type="number" step="1" class="control" name="buy_atleast" v-model="buy_atleast" v-validate="'required|numeric|min_value:1'" value="{{ old('buy_atleast') }}" data-vv-as="&quot;{{ __('admin::app.promotion.cart.buy-atleast') }}&quot;">

                            <span class="control-error" v-if="errors.has('buy_atleast')">@{{ errors.first('buy_atleast') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('apply_to_shipping') ? 'has-error' : '']">
                            <label for="customer_groups" class="required">{{ __('admin::app.promotion.cart.apply-to-shipping') }}</label>

                            <select type="text" class="control" name="apply_to_shipping" v-model="apply_to_shipping" v-validate="'required'" value="{{ old('apply_to_shipping') }}" data-vv-as="&quot;{{ __('admin::app.promotion.cart.apply-to-shipping') }}&quot;">
                                <option value="0">{{ __('admin::app.promotion.general-info.is-coupon-yes') }}</option>

                                <option value="1">{{ __('admin::app.promotion.general-info.is-coupon-no') }}</option>
                            </select>

                            <span class="control-error" v-if="errors.has('apply_to_shipping')">@{{ errors.first('apply_to_shipping') }}</span>
                        </div>
                    </div>
                </accordian>
            </div>
        </script>

        <script>
            Vue.component('cart-rule', {
                template: '#cart-rule-form-template',

                inject: ['$validator'],

                data () {
                    return {
                        type: [],
                        apply: null,
                        apply_amt: false,
                        apply_prct: false,
                        apply_to_shipping: null,
                        buy_atleast: null,
                        conditions_list: [],
                        channels: [],
                        criteria: null,
                        customer_groups: [],
                        description: null,
                        disc_amount: 0.0,
                        disc_percent: 0.0,
                        ends_till: null,
                        end_other_rules: null,
                        is_coupon: null,
                        name: null,
                        priority: 0,
                        starts_from: null,
                        uses_per_cust: 0,
                        status: null,
                        conditions: @json($cart_rule[0]).conditions,
                        cart_input: @json($cart_rule[0]).attributes,
                        actions: @json($cart_rule[0]).actions,
                        conditions_list:[],
                        cart_object: {
                            criteria: null,
                            attribute: null,
                            condition: null,
                            value: []
                        },
                        country_and_states: @json($cart_rule[2])
                    }
                },

                mounted () {
                    console.log(this.actions);
                },

                methods: {
                    addCondition () {
                        if (this.criteria == 'product_subselection' || this.criteria == 'cart') {
                            this.condition_on = this.criteria;
                        } else {
                            alert('please try again');

                            return false;
                        }

                        if (this.condition_on == 'cart') {
                            this.conditions_list.push(this.cart_object);

                            this.cart_object = {
                                criteria: null,
                                attribute: null,
                                condition: null,
                                value: []
                            };
                        }
                    },

                    detectApply() {
                        if (this.apply == 'percent_of_product' || this.apply == 'buy_a_get_b') {
                            this.apply_prct = true;
                            this.apply_amt = false;
                        } else if (this.apply == 'fixed_amount' || this.apply == 'fixed_amount_cart') {
                            this.apply_prct = false;
                            this.apply_amt = true;
                        }
                    },

                    enableCondition(event, index) {
                        selectedIndex = event.target.selectedIndex - 1;

                        for (i in this.cart_input) {
                            if (i == selectedIndex) {
                                this.conditions_list[index].type = this.cart_input[i].type;
                                console.log(this.conditions_list[index]);
                            }
                        }
                    },

                    removeCartAttr(index) {
                        this.conditions_list.splice(index, 1);
                    },

                    removeCat(index) {
                        this.cats.splice(index, 1);
                    }
                }
            });
        </script>
    @endpush
@stop