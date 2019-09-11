@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotion.edit-catalog-rule') }}
@stop

@section('content')
    <div class="content">
        <catalog-rule></catalog-rule>
    </div>

    @push('scripts')
        <script type="text/x-template" id="catalog-rule-form-template">
            <div>
                <form method="POST" action="{{ route('admin.catalog-rule.update', $catalog_rule[5]->id) }}" @submit.prevent="onSubmit">
                    @csrf

                    <div class="page-header">
                        <div class="page-title">
                            <h1>
                                <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                                {{ __('admin::app.promotion.edit-catalog-rule') }}
                            </h1>
                        </div>

                        <div class="page-action">
                            <button type="submit" class="btn btn-lg btn-primary">
                                {{ __('admin::app.promotion.edit-btn-title') }}
                            </button>
                        </div>
                    </div>

                    <div class="page-content">
                        <div class="form-container">
                            @csrf()

                            <accordian :active="true" title="{{ __('admin::app.promotion.information') }}">
                                <div slot="body">
                                    <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                        <label for="name" class="required">{{ __('admin::app.promotion.general-info.name') }}</label>

                                        <input type="text" class="control" name="name" v-model="name" v-validate="'required'" value="{{ old('name') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.name') }}&quot;">

                                        <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                                        <label for="description" class="required">{{ __('admin::app.promotion.general-info.description') }}</label>

                                        <textarea class="control" name="description" v-model="description" v-validate="'required'" value="{{ old('description') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.description') }}&quot;"></textarea>

                                        <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('customer_groups[]') ? 'has-error' : '']">
                                        <label for="customer_groups" class="required">{{ __('admin::app.promotion.general-info.cust-groups') }}</label>

                                        <select type="text" class="control" name="customer_groups[]" v-model="customer_groups" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;" multiple="multiple">
                                            <option disabled="disabled">Select Customer Groups</option>
                                            @foreach(app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $channel)
                                                <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                            @endforeach
                                        </select>

                                        <span class="control-error" v-if="errors.has('customer_groups[]')">@{{ errors.first('customer_groups[]') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('channels[]') ? 'has-error' : '']">
                                        <label for="channels" class="required">{{ __('admin::app.promotion.general-info.channels') }}</label>

                                        <select type="text" class="control" name="channels[]" v-model="channels" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.channels') }}&quot;" multiple="multiple">
                                            <option disabled="disabled">Select Channels</option>
                                            @foreach(app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                                                <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                            @endforeach
                                        </select>

                                        <span class="control-error" v-if="errors.has('channels[]')">@{{ errors.first('status') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                                        <label for="status" class="required">{{ __('admin::app.promotion.general-info.status') }}</label>

                                        <select type="text" class="control" name="status" v-model="status" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.status') }}&quot;">
                                            <option disabled="disabled">{{ __('admin::app.promotion.select-attribute', ['attribute' => 'Option']) }}</option>
                                            <option value="1">{{ __('admin::app.promotion.yes') }}</option>
                                            <option value="0">{{ __('admin::app.promotion.no') }}</option>
                                        </select>

                                        <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
                                    </div>

                                    @php
                                        $now = new \Carbon\Carbon();
                                    @endphp

                                    <date :name="starts_from">
                                        <div class="control-group" :class="[errors.has('starts_from') ? 'has-error' : '']">
                                            <label for="starts_from">{{ __('admin::app.promotion.general-info.starts-from') }}</label>

                                            <input type="text" class="control" v-model="starts_from" name="starts_from" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.starts-from') }}&quot;">

                                            <span class="control-error" v-if="errors.has('starts_from')">@{{ errors.first('starts_from') }}</span>
                                        </div>
                                    </date>

                                    <date :name="starts_from">
                                        <div class="control-group" :class="[errors.has('ends_till') ? 'has-error' : '']">
                                            <label for="ends_till">{{ __('admin::app.promotion.general-info.ends-till') }}</label>

                                            <input type="text" class="control" v-model="ends_till" name="ends_till" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.ends-till') }}&quot;">

                                            <span class="control-error" v-if="errors.has('ends_till')">@{{ errors.first('ends_till') }}</span>
                                        </div>
                                    </date>
                                </div>
                            </accordian>

                            <accordian :active="false" title="{{ __('admin::app.promotion.conditions') }}">
                                <div slot="body">
                                    <input type="hidden" name="all_conditions" v-model="all_conditions">

                                    <!--    Categories selection input block     -->
                                    <div class="control-group" :class="[errors.has('category_values') ? 'has-error' : '']">
                                        <label class="mb-10" for="categories">{{ __('admin::app.promotion.select-category') }}</label>

                                        <multiselect v-model="category_values" :close-on-select="false" :options="category_options" :searchable="false" :custom-label="categoryLabel" :show-labels="true" placeholder="Select Categories" track-by="slug" :multiple="true"></multiselect>
                                    </div>

                                    <label class="mb-10" for="attributes">{{ __('admin::app.promotion.select-attribute', ['attribute' => 'Attribute']) }}</label>

                                    <br/>

                                    <!--    Product attributes conditions block     -->
                                    <div class="control-container mt-20" v-for="(condition, index) in attribute_values" :key="index">
                                        <select class="control" v-model="attribute_values[index].attribute" style="margin-right: 15px; width: 30%;" v-on:change="enableAttributeCondition($event, index)">
                                            <option disabled="disabled">{{ __('admin::app.promotion.select-attribute', ['attribute' => 'Option']) }}</option>

                                            <option v-for="(attr_ip, index1) in attribute_input" :value="attr_ip.code" :key="index1">@{{ attr_ip.name }}</option>
                                        </select>

                                        <select v-show='attribute_values[index].type == "select" || attribute_values[index].type == "multiselect"' class="control" v-model="attribute_values[index].condition" style="margin-right: 15px;">
                                            <option v-for="(condition, index) in conditions.select" :value="index" :key="index">@{{ condition }}</option>
                                        </select>

                                        <select v-show='attribute_values[index].type == "text" || attribute_values[index].type == "textarea" || attribute_values[index].type == "price"' class="control" v-model="attribute_values[index].condition" style="margin-right: 15px;">
                                            <option v-for="(condition, index) in conditions.string" :value="index" :key="index">@{{ condition }}</option>
                                        </select>

                                        <div v-show='attribute_values[index].type == "select" || attribute_values[index].type == "multiselect"' style="display: flex;">
                                            <select class="control" v-model="attribute_values[index].value" style="margin-right: 15px; height: 100px" :multiple="true">
                                                <option :disabled="true">
                                                    {{ __('ui::form.select-attribute', ['attribute' => 'Values']) }}
                                                </option>

                                                <option v-for="(label, index2) in attribute_values[index].options" :value="index2" :key="index2">@{{ label.admin_name }}</option>
                                            </select>

                                            {{-- <multiselect v-model="attribute_values[index].value" :close-on-select="false" :options="attribute_values[index].options" :searchable="false" :track-by="admin_name" :custom-label="attributeListLabel" :multiple="true" ></multiselect> --}}
                                        </div>

                                        <div v-show='attribute_values[index].type == "text" || attribute_values[index].type == "textarea" || attribute_values[index].type == "price" || attribute_values[index].type == "textarea"' style="display: flex">
                                            <input class="control" v-model="attribute_values[index].value" type="text" placeholder="{{ __('ui::form.enter-attribute', ['attribute' => 'Text']) }}">
                                        </div>

                                        <span class="icon trash-icon" v-on:click="removeAttr(index)"></span>
                                    </div>

                                    <span class="btn btn-primary btn-lg mt-20" v-on:click="addAttributeCondition">{{ __('admin::app.promotion.add-attr-condition') }}</span>
                                </div>
                            </accordian>

                            <accordian :active="false" title="{{ __('admin::app.promotion.actions') }}">
                                <div slot="body">
                                    <div class="control-group" :class="[errors.has('action_type') ? 'has-error' : '']">
                                        <label for="action_type" class="required">{{ __('admin::app.promotion.general-info.apply') }}</label>

                                        <select class="control" name="action_type" v-model="action_type" v-validate="'required'" value="{{ old('action_type') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.apply') }}&quot;" v-on:change="detectApply">
                                            <option v-for="(action, index) in actions" :value="index">@{{ action }}</option>
                                        </select>

                                        <span class="control-error" v-if="errors.has('action_type')">@{{ errors.first('action_type') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('disc_amount') ? 'has-error' : '']">
                                        <label for="disc_amount" class="required">{{ __('admin::app.promotion.general-info.disc_amt') }}</label>

                                        <input type="number" step="0.5000" class="control" name="disc_amount" v-model="disc_amount" v-validate="'required|min_value:0.0001'" value="{{ old('disc_amount') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.disc_amt') }}&quot;">

                                        <span class="control-error" v-if="errors.has('disc_amount')">@{{ errors.first('disc_amount') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('end_other_rules') ? 'has-error' : '']">
                                        <label for="end_other_rules" class="required">{{ __('admin::app.promotion.general-info.end-other-rules') }}</label>

                                        <select type="text" class="control" name="end_other_rules" v-model="end_other_rules" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.end-other-rules') }}&quot;">
                                            <option disabled="disabled">{{ __('admin::app.promotion.select-attribute', ['attribute' => 'Option']) }}</option>
                                            <option value="1">{{ __('admin::app.promotion.yes') }}</option>
                                            <option value="0">{{ __('admin::app.promotion.no') }}</option>
                                        </select>

                                        <span class="control-error" v-if="errors.has('end_other_rules')">@{{ errors.first('end_other_rules') }}</span>
                                    </div>
                                </div>
                            </accordian>
                        </div>
                    </div>
                </form>
            </div>
        </script>

        <script>
            Vue.component('catalog-rule', {
                template: '#catalog-rule-form-template',

                inject: ['$validator'],

                data () {
                    return {
                        name: '{{ $catalog_rule[5]->name }}',
                        description: '{{ $catalog_rule[5]->description }}',
                        conditions_list: [],
                        channels: [],
                        customer_groups: [],
                        ends_till: '{{ $catalog_rule[5]->ends_till }}',
                        starts_from: '{{ $catalog_rule[5]->starts_from }}',
                        status: '{{ $catalog_rule[5]->status }}',

                        actions: @json($catalog_rule[3]).actions,
                        action_type: '{{ $catalog_rule[5]->action_code }}',
                        disc_amount: null,
                        end_other_rules: '{{ $catalog_rule[5]->end_other_rules }}',

                        all_conditions: [],

                        all_attributes: {
                            'categories' : [],
                            'attributes' : []
                        },

                        criteria: 'cart',

                        category_options: @json($catalog_rule[1]),
                        category_values: [],
                        conditions: @json($catalog_rule[3]).conditions,
                        attribute_values: [],
                        attr_object: {
                            attribute: null,
                            condition: null,
                            value: [],
                            options: []
                        },
                        attribute_input: @json($catalog_rule[0])
                    }
                },

                mounted () {
                    catalog_rule = @json($catalog_rule[5]);
                    channels = @json($catalog_rule[5]->channels);

                    this.channels = [];
                    for (i in channels) {
                        this.channels.push(channels[i].channel_id);
                    }

                    customer_groups = @json($catalog_rule[5]->customer_groups);

                    for (i in customer_groups) {
                        this.customer_groups.push(customer_groups[i].customer_group_id);
                    }

                    data = @json($catalog_rule[5]->conditions);

                    if (JSON.parse(JSON.parse(data))) {
                        this.category_values = JSON.parse(JSON.parse(data)).categories;

                        this.attribute_values = JSON.parse(JSON.parse(data)).attributes;

                        // creating options and has option param on the frontend
                        for (i in this.attribute_values) {
                            for (j in this.attribute_input) {
                                if (this.attribute_input[j].code == this.attribute_values[i].attribute) {
                                    if (this.attribute_input[j].has_options == true) {
                                        this.attribute_values[i].has_options = true;

                                        this.attribute_values[i].options = this.attribute_input[j].options;
                                    } else {
                                        this.attribute_values[i].has_options = false;

                                        this.attribute_values[i].options = null;
                                    }
                                }
                            }
                        }
                    }

                    this.action_type = '{{ $catalog_rule[5]->action_code }}';
                    this.disc_amount = catalog_rule.discount_amount;
                    this.end_other_rules = '{{ $catalog_rule[5]->end_other_rules }}';
                },

                methods: {
                    created() {
                        VeeValidate.Validator.extend('is_time', {
                            getMessage: field => `The format must be HH:MM:SS`,
                            validate: (value) => new Promise(resolve => {
                                let regex = new RegExp("([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])");
                                resolve({
                                    valid: value && regex.test(value)
                                });
                            })
                        });
                    },

                    categoryLabel (option) {
                        return option.name + ' [ ' + option.slug + ' ]';
                    },

                    attributeListLabel(option) {
                        return option.label;
                    },

                    addAttributeCondition() {
                        this.attribute_values.push(this.attr_object);

                        this.attr_object = {
                            attribute: null,
                            condition: null,
                            value: [],
                            options: []
                        };
                    },

                    enableAttributeCondition (event, index) {
                        selectedIndex = event.target.selectedIndex - 1;

                        for(i in this.attribute_input) {
                            if (i == selectedIndex) {
                                if (this.attribute_input[i].has_options == true) {
                                    this.attribute_values[index].options = this.attribute_input[i].options;
                                }

                                this.attribute_values[index].type = this.attribute_input[i].type;
                            }
                        }
                    },

                    detectApply() {
                        if (this.apply == 0 || this.apply == 2) {
                            this.apply_prct = true;
                            this.apply_amt = false;
                        } else if (this.apply == 1 || this.apply == 3) {
                            this.apply_prct = false;
                            this.apply_amt = true;
                        }
                    },

                    removeAttr(index) {
                        this.attribute_values.splice(index, 1);
                    },

                    removeCat(index) {
                        this.cats.splice(index, 1);
                    },

                    onSubmit: function (e) {
                        if (this.attribute_values.length > 0 || this.category_values.length > 0) {
                            for (i in this.attribute_values) {
                                delete this.attribute_values[i].options;
                            }

                            if (this.category_values.length > 0) {
                                this.all_attributes.categories = this.category_values;
                            }

                            this.all_attributes.attributes = this.attribute_values;
                        }

                        this.all_conditions = JSON.stringify(this.all_attributes);

                        // this.all_conditions = JSON.stringify(this.conditions_list);

                        // if (this.conditions_list.length != 0) {
                        //     this.conditions_list.push({'criteria': this.match_criteria});

                        //     this.all_conditions = JSON.stringify(this.conditions_list);
                        // }

                        // return false;

                        this.$validator.validateAll().then(result => {
                            if (result) {
                                e.target.submit();
                            }
                        });
                    },

                    genericGroupCondition() {
                        this.generic_condition = false;
                    },

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