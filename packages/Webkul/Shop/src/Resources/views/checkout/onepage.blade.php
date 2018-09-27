@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.onepage.title') }}
@stop

@section('content-wrapper')

    <checkout></checkout>

@endsection

@push('scripts')

    <script type="text/x-template" id="checkout-template">
        <div id="checkout" class="checkout-process">

            <div class="col-main">

                <ul class="checkout-steps">
                    <li class="active" :class="[completedStep >= 0 ? 'active' : '', completedStep > 0 ? 'completed' : '']" @click="navigateToStep(1)">
                        <div class="decorator address-info"></div>
                        <span>{{ __('shop::app.checkout.onepage.information') }}</span>
                    </li>
                    
                    <li :class="[currentStep == 2 || completedStep > 1 ? 'active' : '', completedStep > 1 ? 'completed' : '']" @click="navigateToStep(2)">
                        <div class="decorator shipping"></div>
                        <span>{{ __('shop::app.checkout.onepage.shipping') }}</span>
                    </li>
                    
                    <li :class="[currentStep == 3 || completedStep > 2 ? 'active' : '', completedStep > 2 ? 'completed' : '']" @click="navigateToStep(3)">
                        <div class="decorator payment"></div>
                        <span>{{ __('shop::app.checkout.onepage.payment') }}</span>
                    </li>
                    
                    <li :class="[currentStep == 4 ? 'active' : '']">
                        <div class="decorator review"></div>
                        <span>{{ __('shop::app.checkout.onepage.complete') }}</span>
                    </li>
                </ul>

                <div class="step-content information" v-show="currentStep == 1">

                    @include('shop::checkout.onepage.customer-info')

                    <div class="button-group">

                        <button type="button" class="btn btn-lg btn-primary" @click="validateForm('address-form')">
                            {{ __('shop::app.checkout.onepage.continue') }}
                        </button>

                    </div>

                </div>

                <div class="step-content shipping" v-show="currentStep == 2">

                    <shipping-section v-if="currentStep == 2" @onShippingMethodSelected="shippingMethodSelected($event)"></shipping-section>

                    <div class="button-group">

                        <button type="button" class="btn btn-lg btn-primary" @click="validateForm('shipping-form')">
                            {{ __('shop::app.checkout.onepage.continue') }}
                        </button>

                    </div>

                </div>

                <div class="step-content payment" v-show="currentStep == 3">

                    <payment-section v-if="currentStep == 3" @onPaymentMethodSelected="paymentMethodSelected($event)"></payment-section>

                    <div class="button-group">

                        <button type="button" class="btn btn-lg btn-primary" @click="validateForm('payment-form')">
                            {{ __('shop::app.checkout.onepage.continue') }}
                        </button>

                    </div>

                </div>

                <div class="step-content review" v-show="currentStep == 4">

                    <review-section v-if="currentStep == 4"></review-section>

                    <div class="button-group">

                        <button type="button" class="btn btn-lg btn-primary" @click="placeOrder()">
                            {{ __('shop::app.checkout.onepage.place-order') }}
                        </button>

                    </div>

                </div>

            </div>

            <div class="col-right" v-show="currentStep != 4">

                <summary-section></summary-section>

            </div>

        </div>
    </script>

    <script>
        var shippingHtml = '';
        var paymentHtml = '';
        var reviewHtml = '';
        var summaryHtml = Vue.compile(`<?php echo view('shop::checkout.total.summary', ['cart' => $cart])->render(); ?>`);

        Vue.component('checkout', {

            template: '#checkout-template',

            inject: ['$validator'],

            data: () => ({
                currentStep: 1,

                completedStep: 0,

                address: {
                    billing: {
                        use_for_shipping: true
                    },

                    shipping: {},
                },

                selected_shipping_method: '',

                selected_payment_method: '',
            }),

            methods: {
                navigateToStep (step) {
                    if(step <= this.completedStep) {
                        this.currentStep = step
                        this.completedStep = step - 1;
                    }
                },

                validateForm: function (scope) {
                    this.$validator.validateAll(scope).then((result) => {
                        if(result) {
                            if(scope == 'address-form') {
                                this.saveAddress()
                            } else if(scope == 'shipping-form') {
                                this.saveShipping()
                            } else if(scope == 'payment-form') {
                                this.savePayment()
                            }
                        }
                    });
                },

                saveAddress () {
                    var this_this = this;
                    this.$http.post("{{ route('shop.checkout.save-address') }}", this.address)
                        .then(function(response) {
                            if(response.data.jump_to_section == 'shipping') {
                                shippingHtml = Vue.compile(response.data.html)
                                this_this.completedStep = 1;
                                this_this.currentStep = 2;
                            }
                        })
                        .catch(function (error) {
                            this_this.handleErrorResponse(error.response, 'address-form')
                        })
                },

                saveShipping () {
                    var this_this = this;
                    this.$http.post("{{ route('shop.checkout.save-shipping') }}", {'shipping_method': this.selected_shipping_method})
                        .then(function(response) {
                            if(response.data.jump_to_section == 'payment') {
                                paymentHtml = Vue.compile(response.data.html)
                                this_this.completedStep = 2;
                                this_this.currentStep = 3;
                            }
                        })
                        .catch(function (error) {
                            this_this.handleErrorResponse(error.response, 'shipping-form')
                        })
                },

                savePayment () {
                    var this_this = this;
                    this.$http.post("{{ route('shop.checkout.save-payment') }}", {'payment': this.selected_payment_method})
                        .then(function(response) {
                            if(response.data.jump_to_section == 'review') {
                                reviewHtml = Vue.compile(response.data.html)
                                this_this.completedStep = 3;
                                this_this.currentStep = 4;
                            }
                        })
                        .catch(function (error) {
                            this_this.handleErrorResponse(error.response, 'payment-form')
                        })
                },

                placeOrder () {

                },

                handleErrorResponse (response, scope) {
                    if(response.status == 422) {
                        serverErrors = response.data.errors;
                        this.$root.addServerErrors(scope)
                    } else if(response.status == 403) {
                        if(response.data.redirect_url) {
                            window.location.href = response.data.redirect_url;
                        }
                    }
                },

                shippingMethodSelected (shippingMethod) {
                    this.selected_shipping_method = shippingMethod;
                },

                paymentMethodSelected (paymentMethod) {
                    this.selected_payment_method = paymentMethod;
                }
            }
        })

        var summaryTemplateRenderFns = [];
        Vue.component('summary-section', {

            inject: ['$validator'],

            data: () => ({
                templateRender: null
            }),

            staticRenderFns: summaryTemplateRenderFns,

            mounted() {
                this.templateRender = summaryHtml.render;

                for (var i in summaryHtml.staticRenderFns) {
                    summaryTemplateRenderFns.push(summaryHtml.staticRenderFns[i]);
                }
            },

            render(h) {
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

            data: () => ({
                templateRender: null,

                selected_shipping_method: '',
            }),

            staticRenderFns: shippingTemplateRenderFns,

            mounted() {
                this.templateRender = shippingHtml.render;
                for (var i in shippingHtml.staticRenderFns) {
                    shippingTemplateRenderFns.unshift(shippingHtml.staticRenderFns[i]);
                }
            },

            render(h) {
                return h('div', [
                    (this.templateRender ?
                        this.templateRender() :
                        '')
                    ]);
            },

            methods: {
                methodSelected () {
                    this.$emit('onShippingMethodSelected', this.selected_shipping_method)
                }
            }
        })

        var paymentTemplateRenderFns = [];
        Vue.component('payment-section', {

            inject: ['$validator'],

            data: () => ({
                templateRender: null,

                payment: {
                    method: ""
                },
            }),

            staticRenderFns: paymentTemplateRenderFns,

            mounted() {
                this.templateRender = paymentHtml.render;

                for (var i in paymentHtml.staticRenderFns) {
                    paymentTemplateRenderFns.unshift(paymentHtml.staticRenderFns[i]);
                }
            },

            render(h) {
                return h('div', [
                    (this.templateRender ?
                        this.templateRender() :
                        '')
                    ]);
            },

            methods: {
                methodSelected () {
                    this.$emit('onPaymentMethodSelected', this.payment)
                }
            }
        })

        var reviewTemplateRenderFns = [];
        Vue.component('review-section', {

            data: () => ({
                templateRender: null
            }),

            staticRenderFns: reviewTemplateRenderFns,

            mounted() {
                this.templateRender = reviewHtml.render;

                for (var i in reviewHtml.staticRenderFns) {
                    reviewTemplateRenderFns.unshift(reviewHtml.staticRenderFns[i]);
                }
            },

            render(h) {
                return h('div', [
                    (this.templateRender ?
                        this.templateRender() :
                        '')
                    ]);
            }
        })
    </script>

@endpush