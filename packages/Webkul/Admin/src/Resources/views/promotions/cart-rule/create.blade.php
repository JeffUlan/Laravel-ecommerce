@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotion.add-cart-rule') }}
@stop

@section('content')

    <div class="content">
        <cart-rule></cart-rule>
    </div>

    @push('scripts')
        <script type="text/x-template" id="cart-rule-form-template">
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
                        <div>
                            @csrf()

                            <accordian :active="true" title="Information">
                                <div slot="body">
                                    <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                        <label for="name" class="required">{{ __('admin::app.promotion.general-info.name') }}</label>

                                        <input type="text" class="control" name="name" v-model="name" v-validate="'required|alpha_spaces'" value="{{ old('name') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.name') }}&quot;">

                                        <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                                        <label for="description">{{ __('admin::app.promotion.general-info.description') }}</label>

                                        <textarea class="control" name="description" v-model="description" v-validate="'alpha_spaces'" value="{{ old('description') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.description') }}&quot;"></textarea>

                                        <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                                    </div>

                                    <datetime :name="starts_from">
                                        <div class="control-group" :class="[errors.has('starts_from') ? 'has-error' : '']">
                                            <label for="starts_from">{{ __('admin::app.promotion.general-info.starts-from') }}</label>

                                            <input type="text" class="control" v-model="starts_from" name="starts_from" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.starts-from') }}&quot;">

                                            <span class="control-error" v-if="errors.has('starts_from')">@{{ errors.first('starts_from') }}</span>
                                        </div>
                                    </datetime>

                                    <datetime :name="starts_from">
                                        <div class="control-group" :class="[errors.has('ends_till') ? 'has-error' : '']">
                                            <label for="ends_till">{{ __('admin::app.promotion.general-info.ends-till') }}</label>

                                            <input type="text" class="control" v-model="ends_till" name="ends_till" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.ends-till') }}&quot;">

                                            <span class="control-error" v-if="errors.has('ends_till')">@{{ errors.first('ends_till') }}</span>
                                        </div>
                                    </datetime>

                                    <div class="control-group" :class="[errors.has('customer_groups[]') ? 'has-error' : '']">
                                        <label for="customer_groups" class="required">{{ __('admin::app.promotion.general-info.cust-groups') }}</label>

                                        <select type="text" class="control" name="customer_groups[]" v-model="customer_groups" v-validate="'required'" value="{{ old('customer_groups[]') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;" multiple="multiple">
                                            <option disabled="disabled">Select Customer Groups</option>
                                            @foreach(app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $channel)
                                                <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                            @endforeach
                                        </select>

                                        <span class="control-error" v-if="errors.has('customer_groups')">@{{ errors.first('customer_groups') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('channels[]') ? 'has-error' : '']">
                                        <label for="channels" class="required">{{ __('admin::app.promotion.general-info.channels') }}</label>

                                        <select type="text" class="control" name="channels[]" v-model="channels" v-validate="'required'" value="{{ old('channels[]') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.channels') }}&quot;" multiple="multiple">
                                            <option disabled="disabled">Select Channels</option>
                                            @foreach(app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                                                <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                            @endforeach
                                        </select>

                                        <span class="control-error" v-if="errors.has('channels')">@{{ errors.first('channels') }}</span>
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

                                    <div class="control-group" :class="[errors.has('use_coupon') ? 'has-error' :
                                    '']">
                                        <label for="customer_groups" class="required">{{ __('admin::app.promotion.general-info.is-coupon') }}</label>

                                        <select type="text" class="control" name="use_coupon" v-model="use_coupon" v-validate="'required'" value="{{ old('use_coupon')}}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.is-coupon') }}&quot;" v-on:change="useCoupon">
                                            <option value="1" :selected="use_coupon == 1">{{ __('admin::app.promotion.general-info.is-coupon-yes') }}</option>
                                            <option value="0" :selected="use_coupon == 0">{{ __('admin::app.promotion.general-info.is-coupon-no') }}</option>
                                        </select>

                                        <span class="control-error" v-if="errors.has('use_coupon')">@{{ errors.first('use_coupon') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('auto_generation') ? 'has-error' : '']" v-if="use_coupon == 1">
                                        <label for="auto_generation" class="required">{{ __('admin::app.promotion.general-info.specific-coupon') }}</label>

                                        <input type="checkbox" class="control" name="auto_generation" v-model="auto_generation" value="{{ old('auto_generation') }}" data-vv-as="&quot;Specific Coupon&quot;" v-on:change="checkAutogen">

                                        <span class="control-error" v-if="errors.has('auto_generation')">@{{ errors.first('auto_generation') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('per_customer') ? 'has-error' : '']">
                                        <label for="per_customer" class="required">{{ __('admin::app.promotion.general-info.uses-per-cust') }}</label>

                                        <input type="number" step="1" class="control" name="per_customer" v-model="per_customer" v-validate="'required|numeric|min_value:0'" value="{{ old('per_customer') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.uses-per-cust') }}&quot;">

                                        <span class="control-error" v-if="errors.has('per_customer')">@{{ errors.first('per_customer') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('is_guest') ? 'has-error' : '']">
                                        <label for="is_guest" class="required">{{ __('admin::app.promotion.general-info.is-guest') }}</label>

                                        <select type="text" class="control" name="is_guest" v-model="is_guest" v-validate="'required'" value="{{ old('is_guest')}}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.is-guest') }}&quot;">
                                            <option value="1" :selected="is_guest == 1">{{ __('admin::app.promotion.general-info.is-coupon-yes') }}</option>
                                            <option value="0" :selected="is_guest == 0">{{ __('admin::app.promotion.general-info.is-coupon-no') }}</option>
                                        </select>

                                        <span class="control-error" v-if="errors.has('is_guest')">@{{ errors.first('is_guest') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('usage_limit') ? 'has-error' : '']">
                                        <label for="usage_limit" class="required">{{ __('admin::app.promotion.general-info.limit') }}</label>

                                        <input type="number" step="1" class="control" name="usage_limit" v-model="usage_limit" v-validate="'required|numeric|min_value:0'" value="{{ old('usage_limit') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.uses-per-cust') }}&quot;">

                                        <span class="control-error" v-if="errors.has('usage_limit')">@{{ errors.first('usage_limit') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('priority') ? 'has-error' : '']">
                                        <label for="priority" class="required">{{ __('admin::app.promotion.general-info.priority') }}</label>

                                        <input type="number" class="control" step="1" name="priority" v-model="priority" v-validate="'required|numeric|min_value:1'" value="{{ old('priority') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.priority') }}&quot;">

                                        <span class="control-error" v-if="errors.has('priority')">@{{ errors.first('priority') }}</span>
                                    </div>
                                </div>
                            </accordian>

                            <accordian :active="false" title="Conditions">
                                <div slot="body">
                                    <div class="add-condition">
                                        <div class="control-group">
                                            <label for="criteria" class="required">{{ __('admin::app.promotion.general-info.add-condition') }}</label>

                                            <select type="text" class="control" name="criteria" v-model="criteria">
                                                <option value="cart">Cart Properties</option>
                                            </select>
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
                                                    <select class="control" name="cart_attributes[]" v-model="conditions_list[index].attribute" title="You Can Make Multiple Selections Here" style="margin-right: 15px;" v-on:change="enableCondition($event, index)">
                                                        <option disabled="disabled">Select Option</option>
                                                        <option v-for="(cart_ip, index1) in cart_input" :value="cart_ip.code" :key="index1">@{{ cart_ip.name }}</option>
                                                    </select>

                                                    <div v-if='conditions_list[index].type == "string"'>
                                                        <select class="control" name="cart_attributes[]" v-model="conditions_list[index].condition" style="margin-right: 15px;">
                                                            <option v-for="(condition, index) in conditions.numeric" :value="index" :key="index">@{{ condition }}</option>
                                                        </select>

                                                        <div v-if='conditions_list[index].attribute == "shipping_state"'>
                                                            <select class="control" v-model="conditions_list[index].value">
                                                                <option disabled="disabled">Select State</option>
                                                                <optgroup v-for='(state, code) in country_and_states.states' :label="code">
                                                                    <option v-for="(stateObj, index) in state" :value="stateObj.code">@{{ stateObj.default_name }}</option>
                                                                </optgroup>
                                                            </select>
                                                        </div>

                                                        <div v-if='conditions_list[index].attribute == "shipping_country"'>
                                                            <select class="control" v-model="conditions_list[index].value">
                                                                <option disabled="disabled">Select Country</option>
                                                                <option v-for="(country, index) in country_and_states.countries" :value="country.code">@{{ country.name }}</option>
                                                            </select>
                                                        </div>

                                                        <input type="text" class="control" name="cart_attributes[]" v-model="conditions_list[index].value" placeholder="Enter Value" v-if='conditions_list[index].attribute != "shipping_state" && conditions_list[index].attribute != "shipping_country"'>
                                                    </div>

                                                    <div v-if='conditions_list[index].type == "numeric"'>
                                                        <select class="control" name="attributes[]" v-model="conditions_list[index].condition" style="margin-right: 15px;">
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

                            <accordian :active="false" title="Actions">
                                <div slot="body">
                                    <div class="control-group" :class="[errors.has('action_type') ? 'has-error' : '']">
                                        <label for="action_type" class="required">Apply</label>

                                        <select class="control" name="action_type" v-model="action_type" v-validate="'required'" value="{{ old('action_type') }}" data-vv-as="&quot;Apply As&quot;" v-on:change="detectApply">
                                            <option v-for="(action, index) in actions" :value="index">@{{ action }}</option>
                                        </select>

                                        <span class="control-error" v-if="errors.has('action_type')">@{{ errors.first('action_type') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('disc_amount') ? 'has-error' : '']">
                                        <label for="disc_amount" class="required">{{ __('admin::app.promotion.general-info.disc_amt') }}</label>

                                        <input type="number" step="0.5000" class="control" name="disc_amount" v-model="disc_amount" v-validate="'required|decimal|min_value:0.0001'" value="{{ old('disc_amount') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.disc_amt') }}&quot;">

                                        <span class="control-error" v-if="errors.has('disc_amount')">@{{ errors.first('disc_amount') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('disc_threshold') ? 'has-error' : '']">
                                        <label for="disc_threshold" class="required">{{ __('admin::app.promotion.cart.buy-atleast') }}</label>

                                        <input type="number" step="1" class="control" name="disc_threshold" v-model="disc_threshold" v-validate="'required|numeric|min_value:1'" value="{{ old('disc_threshold') }}" data-vv-as="&quot;{{ __('admin::app.promotion.cart.buy-atleast') }}&quot;">

                                        <span class="control-error" v-if="errors.has('disc_threshold')">@{{ errors.first('disc_threshold') }}</span>
                                    </div>

                                    <div class="boolean-control-container">
                                        <div class="control-group" :class="[errors.has('free_shipping') ? 'has-error' : '']">
                                            <label for="free_shipping" class="required">{{ __('admin::app.promotion.general-info.free-shipping') }}</label>

                                            <select type="text" class="control" name="free_shipping" v-model="free_shipping" v-validate="'required'" value="{{ old('free_shipping') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.free-shipping') }}&quot;">
                                                <option value="0" :selected="free_shipping == 0">{{ __('admin::app.promotion.general-info.is-coupon-yes') }}</option>

                                                <option value="1" :selected="free_shipping == 1">{{ __('admin::app.promotion.general-info.is-coupon-no') }}</option>
                                            </select>

                                            <span class="control-error" v-if="errors.has('free_shipping')">@{{ errors.first('free_shipping') }}</span>
                                        </div>

                                        <div class="control-group" :class="[errors.has('apply_to_shipping') ? 'has-error' : '']">
                                            <label for="customer_groups" class="required">{{ __('admin::app.promotion.cart.apply-to-shipping') }}</label>

                                            <select type="text" class="control" name="apply_to_shipping" v-model="apply_to_shipping" v-validate="'required'" value="{{ old('apply_to_shipping') }}" data-vv-as="&quot;{{ __('admin::app.promotion.cart.apply-to-shipping') }}&quot;">
                                                <option value="0" :selected="apply_to_shipping == 0">{{ __('admin::app.promotion.general-info.is-coupon-yes') }}</option>

                                                <option value="1" :selected="apply_to_shipping == 1">{{ __('admin::app.promotion.general-info.is-coupon-no') }}</option>
                                            </select>

                                            <span class="control-error" v-if="errors.has('apply_to_shipping')">@{{ errors.first('apply_to_shipping') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </accordian>

                            <accordian :active="false" title="Coupons" v-if="auto_generation != null">
                                <div slot="body">

                                    <div v-if="!auto_generation">
                                        <div class="control-group" :class="[errors.has('prefix') ? 'has-error' : '']">
                                            <label for="prefix" class="required">Prefix</label>

                                            <input type="text" class="control" name="prefix" v-model="prefix" v-validate="'alpha'" value="{{ old('prefix') }}" data-vv-as="&quot;Prefix&quot;">

                                            <span class="control-error" v-if="errors.has('prefix')">@{{ errors.first('prefix') }}</span>
                                        </div>

                                        <div class="control-group" :class="[errors.has('suffix') ? 'has-error' : '']"">
                                            <label for="suffix" class="required">Suffix</label>

                                            <input type="text" class="control" name="suffix" v-model="suffix" v-validate="'alpha'" value="{{ old('suffix') }}" data-vv-as="&quot;suffix&quot;">

                                            <span class="control-error" v-if="errors.has('suffix')">@{{ errors.first('suffix') }}</span>
                                        </div>
                                    </div>

                                    <div v-if="auto_generation != 0">
                                        <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                            <label for="code" class="required">Code</label>

                                            <input type="text" class="control" name="code" v-model="code" v-validate="'required'" value="{{ old('code') }}" data-vv-as="&quot;Code&quot;">

                                            <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </accordian>

                            <accordian :active="false" title="labels">
                                <div slot="body">
                                    <input type="hidden" name="all_conditions[]" v-model="all_conditions">
                                    <div class="control-group" :class="[errors.has('label') ? 'has-error' : '']" v-if="dedicated_label">
                                        <label for="label" class="required">Global Label</label>

                                        <input type="text" class="control" name="label[global]" v-model="label.global" v-validate="'required'" data-vv-as="&quot;label&quot;">

                                        <span class="control-error" v-if="errors.has('label')">@{{ errors.first('label') }}</span>
                                    </div>

                                    <div v-if="label.global == null || label.global == ''">
                                    @foreach(core()->getAllChannels() as $channel)
                                        <span>[{{ $channel->code }}]</span>
                                        @foreach($channel->locales as $locale)
                                            <div class="control-group" :class="[errors.has('label') ? 'has-error' : '']">
                                                <label for="code">{{ $locale->code }}</label>

                                                <input type="text" class="control" name="label[{{ $channel->code }}][{{ $locale->code }}]" v-model="label.{{ $channel->code }}.{{ $locale->code }}" v-validate="'alpha'" data-vv-as="&quot;Label&quot;">

                                                <span class="control-error" v-if="errors.has('label')">@{{ errors.first('label') }}</span>
                                            </div>
                                        @endforeach
                                    @endforeach
                                    </div>
                                </div>
                            </accordian>
                        </div>
                    </div>
                </div>
            </form>
        </script>

        <script>
            Vue.component('cart-rule', {
                template: '#cart-rule-form-template',

                inject: ['$validator'],

                data () {
                    return {
                        name: null,
                        description: null,
                        conditions_list: [],
                        channels: [],
                        customer_groups: [],
                        ends_till: null,
                        starts_from: null,
                        priority: 0,
                        per_customer: 0,
                        status: null,
                        use_coupon: null,
                        auto_generation: 1,
                        usage_limit: 0,
                        is_guest: 0,

                        action_type: null,
                        apply: null,
                        apply_amt: false,
                        apply_prct: false,
                        apply_to_shipping: null,
                        buy_atleast: null,
                        disc_amount: 0.0,
                        disc_threshold: 0,
                        disc_quantity: 0,
                        end_other_rules: null,
                        coupon_type: null,
                        free_shipping: null,

                        all_conditions: null,

                        code: null,
                        suffix: null,
                        prefix: null,
                        dedicated_label: true,

                        label: {
                            global: null,
                            @foreach(core()->getAllChannels() as $channel)
                                @foreach($channel->locales as $locale)
                                    {{ trim($channel->code) }} : {
                                        {{ trim($locale->code) }}: ''
                                    },
                                @endforeach
                            @endforeach
                        },

                        criteria: null,
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

                    checkAutogen() {
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

                    useCoupon() {
                        // if (this.use_coupon == 1) {
                        //     this.auto_generation = 1;
                        // }
                    },

                    removeCartAttr(index) {
                        this.conditions_list.splice(index, 1);
                    },

                    removeCat(index) {
                        this.cats.splice(index, 1);
                    },

                    onSubmit: function (e) {
                        this.$validator.validateAll().then(result => {
                            if (result) {
                                e.target.submit();
                            }
                        });

                        // for (index in this.conditions_list) {
                        //     if (this.conditions_list[index].condition == null || this.conditions_list[index].condition == "" || this.conditions_list[index].condition == undefined) {
                        //         window.flashMessages = [{'type': 'alert-error', 'message': "{{ __('admin::app.promotion.catalog.condition-missing') }}" }];

                        //         this.$root.addFlashMessages();

                        //         return false;
                        //     } else if (this.conditions_list[index].value == null || this.conditions_list[index].value == "" || this.conditions_list[index].value == undefined) {
                        //         window.flashMessages = [{'type': 'alert-error', 'message': "{{ __('admin::app.promotion.catalog.condition-missing') }}" }];

                        //         this.$root.addFlashMessages();

                        //         return false;
                        //     }
                        // }

                        // if (this.conditions_list.length == 0) {
                        //     window.flashMessages = [{'type': 'alert-error', 'message': "{{ __('admin::app.promotion.catalog.condition-missing') }}" }];

                        //     this.$root.addFlashMessages();

                        //     return false;
                        // }

                        this.all_conditions = JSON.stringify(this.conditions_list);
                    },

                    // genericGroupCondition() {
                    //     this.generic_condition = false;
                    // },

                    addFlashMessages() {
                        const flashes = this.$refs.flashes;

                        flashMessages.forEach(function(flash) {
                            flashes.addFlash(flash);
                        }, this);
                    }
                }
            });
        </script>
    @endpush
@stop