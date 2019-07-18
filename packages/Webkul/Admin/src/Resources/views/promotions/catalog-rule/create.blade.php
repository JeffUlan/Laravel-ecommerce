@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotion.add-catalog-rule') }}
@stop

@section('content')
    <div class="content">
        <catalog-rule></catalog-rule>
    </div>

    @push('scripts')
        <script type="text/x-template" id="catalog-rule-form-template">
            <div>
                <form method="POST" action="{{ route('admin.catalog-rule.store') }}" @submit.prevent="onSubmit">
                    @csrf

                    <div class="page-header">
                        <div class="page-title">
                            <h1>
                                <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                                {{ __('admin::app.promotion.add-catalog-rule') }}
                            </h1>
                        </div>

                        <div class="page-action fixed-action">
                            <button type="submit" class="btn btn-lg btn-primary">
                                {{ __('admin::app.promotion.save-btn-title') }}
                            </button>
                        </div>
                    </div>

                    <div class="page-content">
                        <div class="form-container">
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
                                            <option disabled="disabled">Select status</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>

                                        <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
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

                            <accordian :active="false" title="Conditions">
                                <div slot="body">
                                    <input type="hidden" name="all_conditions" v-model="all_conditions">

                                    <div class="control-group">
                                        {{ __('admin::app.promotion.general-info.test-mode') }}
                                        <select class="control" v-model="match_criteria" style="margin-right: 15px;">
                                            {{ $i = 0 }}
                                            @foreach(config('pricerules.test_mode') as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                                {{ $i++ }}
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="control-group" :class="[errors.has('category_values') ? 'has-error' : '']">
                                        <label class="mb-10" for="categories">{{ __('admin::app.promotion.select-category') }}</label>

                                        <multiselect v-model="category_values" :close-on-select="false" :options="category_options" :searchable="false" :custom-label="categoryLabel" :show-labels="true" placeholder="Select Categories" track-by="slug" :multiple="true"></multiselect>
                                    </div>

                                    <label class="mb-10" for="attributes">{{ __('admin::app.promotion.select-attribute') }}</label>

                                    <br/>

                                    <div class="control-container mt-20" v-for="(condition, index) in attribute_values" :key="index">
                                        <select class="control" v-model="attribute_values[index].attribute" title="You Can Make Multiple Selections Here" style="margin-right: 15px; width: 30%;" v-on:change="enableAttributeCondition($event, index)">
                                            <option disabled="disabled">Select Option</option>

                                            <option v-for="(attr_ip, index1) in attribute_input" :value="attr_ip.code" :key="index1">@{{ attr_ip.name }}</option>
                                        </select>

                                        <select class="control" v-model="attribute_values[index].condition" style="margin-right: 15px;">
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

                                    <span class="btn btn-primary btn-lg mt-20" v-on:click="addAttributeCondition">Add Attribute Condition</span>
                                </div>
                            </accordian>

                            <accordian :active="false" title="Actions">
                                <div slot="body">
                                    <div class="control-group" :class="[errors.has('apply') ? 'has-error' : '']">
                                        <label for="apply" class="required">Apply</label>

                                        <select class="control" name="apply" v-model="apply" v-validate="'required'" value="{{ old('apply') }}" data-vv-as="&quot;Apply As&quot;" v-on:change="detectApply">
                                            @foreach($catalog_rule[3]['actions'] as $key => $value)
                                                <option value="{{ $key }}">{{ __($value) }}</option>
                                            @endforeach
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
                        all_conditions: [],
                        apply: null,
                        apply_amt: false,
                        apply_prct: false,
                        applied_config: @json($catalog_rule[3]),
                        conditions_list: [],
                        attr_families: @json($catalog_rule[5]),
                        attrs_input: @json($catalog_rule[0]),
                        attrs_options: @json($catalog_rule[2]),
                        global_condition: {
                            allorany: false,
                            alltrueorfalse: true
                        },
                        attr_object: {
                            criteria: 'attribute',
                            attribute: null,
                            condition: null,
                            type: null,
                            value: null
                        },
                        cat_object: {
                            criteria: 'category',
                            category: 'category',
                            condition: null,
                            value: []
                        },
                        fam_object: {
                            criteria: 'attribute_family',
                            family: 'attribute_family',
                            condition: null,
                            value: null
                        },
                        categories: @json($catalog_rule[1]),
                        cats_count: 0,
                        channels: [],
                        conditions: [],
                        condition_groups: [],
                        criteria: null,
                        customer_groups: [],
                        description: null,
                        disc_amount: 0.0,
                        disc_percent: 0.0,
                        ends_till: null,
                        end_other_rules: null,
                        generic_condition: true,
                        name: null,
                        priority: 0,
                        starts_from: null,
                        status: null
                    }
                },

                mounted () {
                },

                methods: {
                    addCondition () {
                        if (this.criteria == 'attribute' || this.criteria == 'attribute_family' || this.criteria == 'category') {
                            this.condition_on = this.criteria;
                        } else {
                            alert('please try again');

                            return false;
                        }

                        if (this.condition_on == 'attribute') {
                            this.conditions_list.push(this.attr_object);

                            this.attr_object = {
                                criteria: this.condition_on,
                                attribute: null,
                                condition: null,
                                value: null,
                                type: null,
                                options: null
                            };
                        } else if (this.condition_on == 'category') {
                            this.conditions_list.push(this.cat_object);

                            this.cat_object = {
                                criteria: this.condition_on,
                                category: 'category',
                                condition: null,
                                value: []
                            };
                        } else if (this.condition_on == 'attribute_family') {
                            this.conditions_list.push(this.fam_object);

                            this.fam_object = {
                                criteria: this.condition_on,
                                family: 'attribute_family',
                                condition: null,
                                value: null
                            };
                        }
                    },

                    enableCondition(event, index) {
                        this.conditions_list[index].type = this.attrs_input[event.target.selectedIndex - 1].type;
                        var this_this = this;

                        if (this.attrs_input[event.target.selectedIndex - 1].type == 'select' || this.attrs_input[event.target.selectedIndex - 1].type == 'multiselect') {

                            this.conditions_list[index].options = this.attrs_options[this.attrs_input[event.target.selectedIndex - 1].code];
                            this.conditions_list[index].value = [];
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

                        for (index in this.conditions_list) {
                            if (this.conditions_list[index].condition == null || this.conditions_list[index].condition == "" || this.conditions_list[index].condition == undefined) {
                                window.flashMessages = [{'type': 'alert-error', 'message': "{{ __('admin::app.promotion.catalog.condition-missing') }}" }];

                                this.$root.addFlashMessages();

                                return false;
                            } else if (this.conditions_list[index].value == null || this.conditions_list[index].value == "" || this.conditions_list[index].value == undefined) {
                                window.flashMessages = [{'type': 'alert-error', 'message': "{{ __('admin::app.promotion.catalog.condition-missing') }}" }];

                                this.$root.addFlashMessages();

                                return false;
                            }
                        }

                        if (this.conditions_list.length == 0) {
                            window.flashMessages = [{'type': 'alert-error', 'message': "{{ __('admin::app.promotion.catalog.condition-missing') }}" }];

                            this.$root.addFlashMessages();

                            return false;
                        }

                        this.all_conditions = JSON.stringify(this.conditions_list);
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