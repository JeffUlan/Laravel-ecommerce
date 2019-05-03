@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotion.add-catalog-rule') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.catalog-rule.store') }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.promotion.add-catalog-rule') }}
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
                    <catalog-rule></catalog-rule>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script type="text/x-template" id="catalog-rule-form-template">
            <div>
                @csrf()
                {{-- <accordian :active="true" title="Information"> --}}
                    <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                        <label for="name" class="required">{{ __('admin::app.promotion.general-info.name') }}</label>
                        <input type="text" class="control" name="name" v-model="name" v-validate="'required'" value="{{ old('name') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.firstname') }}&quot;">
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
                        <span class="control-error" v-if="errors.has('customer_groups[]')">@{{ errors.first('customer_groups') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('channels') ? 'has-error' : '']">
                        <label for="channels" class="required">{{ __('admin::app.promotion.general-info.channels') }}</label>
                        <select type="text" class="control" name="channels" v-model="channels" v-validate="'required'" value="{{ old('channels') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;" multiple="multiple">
                            <option disabled="disabled">Select Channels</option>
                            @foreach(app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                                <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                            @endforeach
                        </select>
                        <span class="control-error" v-if="errors.has('channels')">@{{ errors.first('channels') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('starts_from') ? 'has-error' : '']">
                        <label for="starts_from" class="required">{{ __('admin::app.promotion.general-info.starts-from') }}</label>
                        <input type="text" class="control" name="starts_from" v-model="starts_from" v-validate="'required'" value="{{ old('starts_from') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.starts-from') }}&quot;">
                        <span class="control-error" v-if="errors.has('starts_from')">@{{ errors.first('starts_from') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('ends_till') ? 'has-error' : '']">
                        <label for="ends_till" class="required">{{ __('admin::app.promotion.general-info.ends-till') }}</label>
                        <input type="text" class="control" name="ends_till" v-model="ends_till" v-validate="'required'" value="{{ old('ends_till') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.ends_till') }}&quot;">
                        <span class="control-error" v-if="errors.has('ends_till')">@{{ errors.first('ends_till') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('priority') ? 'has-error' : '']">
                        <label for="priority" class="required">{{ __('admin::app.promotion.general-info.priority') }}</label>
                        <input type="text" class="control" name="priority" v-model="priority" v-validate="'required|numeric|max:1'" value="{{ old('priority') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.priority') }}&quot;">
                        <span class="control-error" v-if="errors.has('priority')">@{{ errors.first('priority') }}</span>
                    </div>
                {{-- </accordian> --}}

                <h2>Conditions</h2>

                <div class="add-condition">
                    <div class="control-group" :class="[errors.has('criteria') ? 'has-error' : '']">
                        <label for="criteria" class="required">{{ __('admin::app.promotion.general-info.add-condition') }}</label>
                        <select type="text" class="control" name="criteria" v-model="criteria" v-validate="'required'" value="{{ old('channels') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;">
                            <option disabled="disabled">Select Criteria</option>
                            <option value="attribute">Attribute</option>
                            <option value="attribute_family">Attribute Family</option>
                            <option value="cart_attribute">Cart Attribute</option>
                        </select>
                        <span class="control-error" v-if="errors.has('criteria')">@{{ errors.first('criteria') }}</span>
                    </div>

                    <span class="btn btn-primary btn-lg" v-on:click="addCondition">Select Condition</span>
                </div>

                <div class="selectors-set">
                    <div class="control-group" :class="[errors.has('attribute') ? 'has-error' : '']" v-if="enableAttribute">
                        <label for="attribute" class="required">{{ __('admin::app.promotion.select-attr') }}</label>
                        <select type="text" class="control" name="attribute" v-model="attribute" v-validate="'required'" value="{{ old('attribute') }}" data-vv-as="&quot;{{ __('admin::app.promotion.attribute') }}&quot;">
                            <option disabled="disabled">Select attribute</option>
                            <option v-for="attribute in attributes" value="attribute.id">@{{ attribute.name }}</option>
                        </select>
                        <span class="control-error" v-if="errors.has('attribute')">@{{ errors.first('attribute') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('attribute_family') ? 'has-error' : '']" v-if="enableAttributeFamily">
                        <label for="attribute_family" class="required">{{ __('admin::app.promotion.select-attr-fam') }}</label>
                        <select type="text" class="control" v-model="attribute_family" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotion.attribute') }}&quot;">
                            <option disabled="disabled">Select Attribute Family</option>
                            <option v-for="attribute_family in attribute_families" value="attribute_family.id">@{{ attribute_family.name }}</option>
                        </select>
                        <span class="control-error" v-if="errors.has('attribute_family')">@{{ errors.first('attribute_family') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('cart_attribute') ? 'has-error' : '']" v-if="enableCartAttribute">
                        <label for="cart_attribute" class="required">{{ __('admin::app.promotion.select-cart-attr') }}</label>
                        <select type="text" class="control" name="cart_attribute" v-model="cart_attribute" v-validate="'required'" value="{{ old('cart_attribute') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;">
                            <option disabled="disabled">Select Cart Attribute</option>
                            <option v-for="(cartAttribute, index) in this.criteria.cart" value="cartAttribute[index]">@{{ cartAttribute[index] }}</option>
                        </select>
                        <span class="control-error" v-if="errors.has('cart_attribute')">@{{ errors.first('cart_attribute') }}</span>
                    </div>
                </div>

                <div class="condition-set">
                    <div class="control-group" :class="[errors.has('cart_attr') ? 'has-error' : '']" v-if="enableCartAttrLine">
                        <label for="cart_attr" class="required">{{ __('admin::app.promotion.select-cart-attr') }}</label>
                        <input type="text" class="control" v-model="cart_attr.cart_attribute">
                        <input type="text" class="control" v-model="cart_attr.condition_one">
                        <input type="text" class="control" v-model="cart_attr.value_one">
                        <input type="text" class="control" v-model="cart_attr.condition_two">
                        <input type="text" class="control" v-model="cart_attr.value_two">
                        <span class="control-error" v-if="errors.has('cart_attr')">@{{ errors.first('cart_attr') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('attr') ? 'has-error' : '']" v-if="enableAttrLine">
                        <label for="attr" class="required">{{ __('admin::app.promotion.select-attr') }}</label>
                        <input type="text" class="control" v-model="attr.attribute">
                        <input type="text" class="control" v-model="attr.condition_one">
                        <input type="text" class="control" v-model="attr.value_one">
                        <input type="text" class="control" v-model="attr.condition_two">
                        <input type="text" class="control" v-model="attr.value_two">
                        <span class="control-error" v-if="errors.has('attr')">@{{ errors.first('attr') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('attr_fam') ? 'has-error' : '']" v-if="enableAttrFamLine">
                        <label for="attr_fam" class="required">{{ __('admin::app.promotion.select-attr') }}</label>
                        <input type="text" class="control" v-model="attr_fam.attribute">
                        <input type="text" class="control" v-model="attr_fam.condition_one">
                        <input type="text" class="control" v-model="attr_fam.value_one">
                        <input type="text" class="control" v-model="attr_fam.condition_two">
                        <input type="text" class="control" v-model="attr_fam.value_two">
                        <span class="control-error" v-if="errors.has('attr_fam')">@{{ errors.first('attr_fam') }}</span>
                    </div>
                </div>
            </div>
        </script>

        <script>
            Vue.component('catalog-rule', {
                template: '#catalog-rule-form-template',

                inject: ['$validator'],

                data () {
                    return {
                        attributes: @json($criteria[1]),
                        attribute: null,
                        attribute_families: @json($criteria[2]),
                        attribute_family: null,
                        cart_attribute: null,
                        channels: [],
                        criteria: @json($criteria[0]),
                        conditions: [],
                        cart_attr: {
                            cart_attribute : null,
                            condition_one: null,
                            value_one: null,
                            condition_two: null,
                            value_two: null
                        },
                        attr_family: {
                            attribute_family : null,
                            condition_one: null,
                            value_one: null,
                            condition_two: null,
                            value_two: null
                        },
                        attr: {
                            attribute : null,
                            condition_one: null,
                            value_one: null,
                            condition_two: null,
                            value_two: null
                        },
                        customer_groups: [],
                        description: null,
                        ends_till: null,
                        enableAttribute: false,
                        enableCartAttribute: false,
                        enableAttributeFamily: false,
                        enableCartAttrLine: false,
                        enableAttrLine: false,
                        enableAttrFamLine: false,
                        name: null,
                        priority: 0,
                        starts_from: null
                    }
                },

                mounted () {
                    console.log(this.criteria.cart);

                    for(index in this.criteria.cart) {
                        console.log(this.criteria.cart[index]);
                    }
                },

                methods: {
                    addCondition () {
                        if (this.criteria == "attribute" || this.criteria == "attribute_family" || this.criteria == "cart_attribute") {
                            this.condition_on = this.criteria;
                        } else {
                            alert('please try again');
                        }

                        if (this.condition_on == "attribute") {
                            this.attr.push();

                            this.enableAttribute = true;
                            this.enableCartAttribute = false;
                            this.enableAttributeFamily = false;
                        } else if (this.condition_on == "attribute_family") {
                            this.enableAttributeFamily = true;
                            this.enableAttribute = false;
                            this.enableCartAttribute = false;
                        } else {
                            this.enableCartAttribute = true;
                            this.enableAttributeFamily = false;
                            this.enableAttribute = false;
                        }
                    }
                }
            });
        </script>
    @endpush
@stop