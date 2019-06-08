@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.onepage.title') }}
@stop

@section('content-wrapper')
    <checkout></checkout>
@endsection

@push('scripts')
    <script type="text/x-template" id="discount-template">
        <div class="discount">
            <div class="discount-group" v-if="!end_rule_present">
                <form class="coupon-form" method="post" @submit.prevent="onSubmit">
                    <div class="control-group mt-20" :class="[errors.has('code') ? 'has-error' : '']">
                        <input v-model="code" type="text" class="control" value="" name="code" placeholder="Enter Coupon Code" v-on:change="codeChange" style="width: 100%">

                        <span class="control-error" v-if="errors.has('code')">
                            @{{ errors.first('code') }}
                        </span>

                        <span class="coupon-message mt-5" style="display: block; color: #ff5656; margin-bottom: 5px;" v-if="message != 'Success' && message != 'success'">@{{ message }}</span>

                        <span class="coupon-message mt-5" style="display: block; margin-bottom: 5px;" v-if="message == 'Success' || message == 'success'">@{{ message }}</span>

                        <button class="btn btn-lg btn-black">{{ __('shop::app.checkout.onepage.apply-coupon') }}</button>
                    </div>
                </form>
            </div>

            <div class="discount-details-group" v-if="non_coupon_able">
                <div class="item-detail" v-if="free_shipping">
                    <label>Free Shipping</label>
                    <label class="right">Yes</label>
                </div>

                <div class="item-detail">
                    <label>{{ __('shop::app.checkout.total.disc-amount') }}</label>
                    <label class="right">@{{ discount_amount }}</label>
                </div>

                <div class="item-detail" style="font-weight: bold;">
                    <label>{{ __('shop::app.checkout.total.new-grand-total') }}</label>
                    <label class="right">@{{ new_grand_total }}</label>
                </div>
            </div>

            <div class="discount-details-group" v-if="coupon_able">
                <div class="item-detail">
                    <label>{{ __('shop::app.checkout.total.coupon-applied') }}</label>
                    <label class="right">@{{ discount_code }}</label>
                </div>
                <div class="item-detail" v-if="free_shipping">
                    <label>Free Shipping</label>
                    <label class="right">Yes</label>
                </div>

                <div class="item-detail">
                    <label>{{ __('shop::app.checkout.total.disc-amount') }}</label>
                    <label class="right">@{{ discount_amount }} <span class="icon cross-icon" v-on:click="removeCoupon"></span></label>
                </div>

                <div class="item-detail" style="font-weight: bold;">
                    <label>{{ __('shop::app.checkout.total.new-grand-total') }}</label>
                    <label class="right">@{{ new_grand_total }}</label>
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="checkout-template">
        <div id="checkout" class="checkout-process">
            <div class="col-main">
                <ul class="checkout-steps">
                    <li class="active" :class="[completedStep >= 0 ? 'active' : '', completedStep > 0 ? 'completed' : '']" @click="navigateToStep(1)">
                        <div class="decorator address-info"></div>
                        <span>{{ __('shop::app.checkout.onepage.information') }}</span>
                    </li>

                    <div class="line mb-25"></div>

                    <li :class="[currentStep == 2 || completedStep > 1 ? 'active' : '', completedStep > 1 ? 'completed' : '']" @click="navigateToStep(2)">
                        <div class="decorator shipping"></div>
                        <span>{{ __('shop::app.checkout.onepage.shipping') }}</span>
                    </li>

                    <div class="line mb-25"></div>

                    <li :class="[currentStep == 3 || completedStep > 2 ? 'active' : '', completedStep > 2 ? 'completed' : '']" @click="navigateToStep(3)">
                        <div class="decorator payment"></div>
                        <span>{{ __('shop::app.checkout.onepage.payment') }}</span>
                    </li>

                    <div class="line mb-25"></div>

                    <li :class="[currentStep == 4 ? 'active' : '']">
                        <div class="decorator review"></div>
                        <span>{{ __('shop::app.checkout.onepage.complete') }}</span>
                    </li>
                </ul>

                <div class="step-content information" v-show="currentStep == 1" id="address-section">
                    @include('shop::checkout.onepage.customer-info')

                    <div class="button-group">
                        <button type="button" class="btn btn-lg btn-primary" @click="validateForm('address-form')" :disabled="disable_button" id="checkout-address-continue-button">
                            {{ __('shop::app.checkout.onepage.continue') }}
                        </button>
                    </div>
                </div>

                <div class="step-content shipping" v-show="currentStep == 2" id="shipping-section">
                    <shipping-section v-if="currentStep == 2" @onShippingMethodSelected="shippingMethodSelected($event)"></shipping-section>

                    <div class="button-group">
                        <button type="button" class="btn btn-lg btn-primary" @click="validateForm('shipping-form')" :disabled="disable_button" id="checkout-shipping-continue-button">
                            {{ __('shop::app.checkout.onepage.continue') }}
                        </button>

                    </div>
                </div>

                <div class="step-content payment" v-show="currentStep == 3" id="payment-section">
                    <payment-section v-if="currentStep == 3" @onPaymentMethodSelected="paymentMethodSelected($event)"></payment-section>

                    <div class="button-group">
                        <button type="button" class="btn btn-lg btn-primary" @click="validateForm('payment-form')" :disabled="disable_button" id="checkout-payment-continue-button">
                            {{ __('shop::app.checkout.onepage.continue') }}
                        </button>
                    </div>
                </div>

                <div class="step-content review" v-show="currentStep == 4" id="summary-section">
                    <review-section v-if="currentStep == 4"></review-section>

                    <div class="button-group">
                        <button type="button" class="btn btn-lg btn-primary" @click="placeOrder()" :disabled="disable_button" id="checkout-place-order-button">
                            {{ __('shop::app.checkout.onepage.place-order') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-right" v-if="resetSummary" v-show="currentStep != 4">
                <summary-section></summary-section>
            </div>
        </div>
    </script>

    <script>
        var shippingHtml = '';
        var paymentHtml = '';
        var reviewHtml = '';
        var summaryHtml = '';
        var customerAddress = null;

        @auth('customer')
            @if(auth('customer')->user()->addresses)
                customerAddress = @json(auth('customer')->user()->addresses);
                customerAddress.email = "{{ auth('customer')->user()->email }}";
                customerAddress.first_name = "{{ auth('customer')->user()->first_name }}";
                customerAddress.last_name = "{{ auth('customer')->user()->last_name }}";
            @endif
        @endauth

        Vue.component('checkout', {
            template: '#checkout-template',
            inject: ['$validator'],

            data: function() {
                return {
                    currentStep: 1,
                    completedStep: 0,
                    address: {
                        billing: {
                            address1: [''],

                            use_for_shipping: true,
                        },

                        shipping: {
                            address1: ['']
                        },
                    },
                    selected_shipping_method: '',
                    selected_payment_method: '',
                    disable_button: false,
                    new_shipping_address: false,
                    new_billing_address: false,
                    allAddress: {},
                    countryStates: @json(core()->groupedStatesByCountries()),
                    country: @json(core()->countries()),
                    resetSummary: true
                }
            },

            created: function() {
                this.getOrderSummary();

                if(! customerAddress) {
                    this.new_shipping_address = true;
                    this.new_billing_address = true;
                } else {
                    if (customerAddress.length < 1) {
                        this.new_shipping_address = true;
                        this.new_billing_address = true;
                    } else {
                        this.allAddress = customerAddress;

                        for (var country in this.country) {
                            for (var code in this.allAddress) {
                                if (this.allAddress[code].country) {
                                    if (this.allAddress[code].country == this.country[country].code) {
                                        this.allAddress[code]['country'] = this.country[country].name;
                                    }
                                }
                            }
                        }
                    }
                }
            },

            methods: {
                navigateToStep: function(step) {
                    if (step <= this.completedStep) {
                        this.currentStep = step
                        this.completedStep = step - 1;
                    }
                },

                haveStates: function(addressType) {
                    if (this.countryStates[this.address[addressType].country] && this.countryStates[this.address[addressType].country].length)
                        return true;

                    return false;
                },

                validateForm: function(scope) {
                    var this_this = this;

                    this.$validator.validateAll(scope).then(function (result) {
                        if (result) {
                            if (scope == 'address-form') {
                                this_this.saveAddress();
                            } else if (scope == 'shipping-form') {
                                this_this.saveShipping();
                            } else if (scope == 'payment-form') {
                                this_this.savePayment();
                            }
                        }
                    });
                },

                getOrderSummary () {
                    var this_this = this;

                    this.$http.get("{{ route('shop.checkout.summary') }}")
                        .then(function(response) {
                            this_this.resetSummary = false;

                            summaryHtml = Vue.compile(response.data.html)

                            setTimeout(function() {
                                this_this.resetSummary = true;
                            }, 0);
                        })
                        .catch(function (error) {})
                },

                saveAddress: function() {
                    var this_this = this;

                    this.disable_button = true;

                    this.$http.post("{{ route('shop.checkout.save-address') }}", this.address)
                        .then(function(response) {
                            this_this.disable_button = false;

                            if (response.data.jump_to_section == 'shipping') {
                                shippingHtml = Vue.compile(response.data.html)
                                this_this.completedStep = 1;
                                this_this.currentStep = 2;

                                this_this.getOrderSummary();
                            }
                        })
                        .catch(function (error) {
                            this_this.disable_button = false;

                            this_this.handleErrorResponse(error.response, 'address-form')
                        })
                },

                saveShipping: function() {
                    var this_this = this;

                    this.disable_button = true;

                    this.$http.post("{{ route('shop.checkout.save-shipping') }}", {'shipping_method': this.selected_shipping_method})
                        .then(function(response) {
                            this_this.disable_button = false;

                            if (response.data.jump_to_section == 'payment') {
                                paymentHtml = Vue.compile(response.data.html)
                                this_this.completedStep = 2;
                                this_this.currentStep = 3;

                                this_this.getOrderSummary();
                            }
                        })
                        .catch(function (error) {
                            this_this.disable_button = false;

                            this_this.handleErrorResponse(error.response, 'shipping-form')
                        })
                },

                savePayment: function() {
                    var this_this = this;

                    this.disable_button = true;

                    this.$http.post("{{ route('shop.checkout.save-payment') }}", {'payment': this.selected_payment_method})
                    .then(function(response) {
                        this_this.disable_button = false;

                        if (response.data.jump_to_section == 'review') {
                            reviewHtml = Vue.compile(response.data.html)
                            this_this.completedStep = 3;
                            this_this.currentStep = 4;

                            this_this.getOrderSummary();
                        }
                    })
                    .catch(function (error) {
                        this_this.disable_button = false;

                        this_this.handleErrorResponse(error.response, 'payment-form')
                    });
                },

                placeOrder: function() {
                    var this_this = this;

                    axios.post('{{ route('shop.checkout.save.discount') }}').then(function (response) {
                        console.log(response.data);
                    }).catch(function (error) {
                        console.log('error in saving discount');
                    });

                    this.disable_button = true;

                    this.$http.post("{{ route('shop.checkout.save-order') }}", {'_token': "{{ csrf_token() }}"})
                        .then(function(response) {
                            if (response.data.success) {
                                if (response.data.redirect_url) {
                                    window.location.href = response.data.redirect_url;
                                } else {
                                    window.location.href = "{{ route('shop.checkout.success') }}";
                                }
                            }
                        })
                        .catch(function (error) {
                            this_this.disable_button = true;

                            window.flashMessages = [{'type': 'alert-error', 'message': "{{ __('shop::app.common.error') }}" }];

                            this_this.$root.addFlashMessages()
                        })
                },

                handleErrorResponse: function(response, scope) {
                    if (response.status == 422) {
                        serverErrors = response.data.errors;
                        this.$root.addServerErrors(scope)
                    } else if (response.status == 403) {
                        if (response.data.redirect_url) {
                            window.location.href = response.data.redirect_url;
                        }
                    }
                },

                shippingMethodSelected: function(shippingMethod) {
                    this.selected_shipping_method = shippingMethod;
                },

                paymentMethodSelected: function(paymentMethod) {
                    this.selected_payment_method = paymentMethod;
                },

                newBillingAddress: function() {
                    this.new_billing_address = true;
                },

                newShippingAddress: function() {
                    this.new_shipping_address = true;
                }
            }
        })

        var summaryTemplateRenderFns = [];
        Vue.component('summary-section', {
            inject: ['$validator'],

            data: function() {
                return {
                    templateRender: null
                }
            },

            staticRenderFns: summaryTemplateRenderFns,

            mounted: function() {
                this.templateRender = summaryHtml.render;

                for (var i in summaryHtml.staticRenderFns) {
                    summaryTemplateRenderFns.unshift(summaryHtml.staticRenderFns[i]);
                }
            },

            render: function(h) {
                return h('div', [
                    (this.templateRender ?
                        this.templateRender() :
                        '')
                    ]);
            }
        })

        var shippingTemplateRenderFns = [];
        Vue.component('shipping-section', {
            inject: ['$validator'],

            data: function() {
                return {
                    templateRender: null,
                    selected_shipping_method: '',
                }
            },

            staticRenderFns: shippingTemplateRenderFns,

            mounted: function() {
                this.templateRender = shippingHtml.render;
                for (var i in shippingHtml.staticRenderFns) {
                    shippingTemplateRenderFns.push(shippingHtml.staticRenderFns[i]);
                }

                eventBus.$emit('after-checkout-shipping-section-added');
            },

            render: function(h) {
                return h('div', [
                    (this.templateRender ?
                        this.templateRender() :
                        '')
                    ]);
            },

            methods: {
                methodSelected: function() {
                    this.$emit('onShippingMethodSelected', this.selected_shipping_method)

                    eventBus.$emit('after-shipping-method-selected');
                }
            }
        })

        var paymentTemplateRenderFns = [];
        Vue.component('payment-section', {
            inject: ['$validator'],

            data: function() {
                return {
                    templateRender: null,

                    payment: {
                        method: ""
                    },
                }
            },

            staticRenderFns: paymentTemplateRenderFns,

            mounted: function() {
                this.templateRender = paymentHtml.render;

                for (var i in paymentHtml.staticRenderFns) {
                    paymentTemplateRenderFns.push(paymentHtml.staticRenderFns[i]);
                }

                eventBus.$emit('after-checkout-payment-section-added');
            },

            render: function(h) {
                return h('div', [
                    (this.templateRender ?
                        this.templateRender() :
                        '')
                    ]);
            },

            methods: {
                methodSelected: function() {
                    this.$emit('onPaymentMethodSelected', this.payment)

                    eventBus.$emit('after-payment-method-selected');
                }
            }
        })

        var reviewTemplateRenderFns = [];
        Vue.component('review-section', {
            data: function() {
                return {
                    templateRender: null
                }
            },
            staticRenderFns: reviewTemplateRenderFns,
            mounted: function() {
                this.templateRender = reviewHtml.render;
                for (var i in reviewHtml.staticRenderFns) {
                    reviewTemplateRenderFns.push(reviewHtml.staticRenderFns[i]);
                }
            },
            render: function(h) {
                return h('div', [
                    (this.templateRender ?
                        this.templateRender() :
                        '')
                    ]);
            }
        });

        Vue.component('discount', {
            template: '#discount-template',
            inject: ['$validator'],

            data: function() {
                return {
                    code: null,
                    message: null,
                    end_rule_present: false,
                    discount_amount: 0,
                    rule_name: null,
                    free_shipping: null,
                    new_grand_total: null,
                    non_coupon_able: false,
                    coupon_able: false,
                    discount_code: null
                }
            },

            mounted: function() {
                // shop.checkout.fetch.non-coupon
                this.checkNonCouponAble();
            },

            methods: {
                onSubmit: function() {
                    var this_this = this;

                    axios.post('{{ route('shop.checkout.check.coupons') }}', {
                        code: this_this.code
                    }).then(function(response) {
                        this_this.end_rule_present = response.data.end_other_rules;
                        this_this.discount_amount = response.data.formatted_discount;
                        this_this.rule_name = response.data.rule.name;
                        this_this.free_shipping = response.data.rule.free_shipping;
                        this_this.new_grand_total = response.data.formatted_new_grand_total;
                        this_this.non_coupon_able = false;
                        this_this.coupon_able = true;
                        this_this.discount_code = this_this.code;
                    }).catch(function(error) {
                    });
                },

                checkNonCouponAble: function () {
                    var this_this = this;

                    axios.post('{{ route('shop.checkout.fetch.non-coupon') }}').then(function(response) {
                        this_this.end_rule_present = response.data.end_other_rules;
                        this_this.discount_amount = response.data.formatted_discount;
                        this_this.rule_name = response.data.rule.name;
                        this_this.free_shipping = response.data.rule.free_shipping;
                        this_this.new_grand_total = response.data.formatted_new_grand_total;
                        this_this.non_coupon_able = true;
                    }).catch(function (error) {
                    });
                },

                codeChange: function() {
                    if (this.code.length == 0 || this.code.length == 1)
                    {
                        this.message = null;
                    }
                },

                removeCoupon: function() {
                    var this_this = this;

                    axios.post('{{ route('shop.checkout.remove.coupon') }}', {
                        code: this_this.code
                    }).then(function(response) {
                        console.log(response.data);
                    }).catch(function(error) {
                        console.log(error.data);
                    });
                }
            }
        });

    </script>

@endpush