@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.marketing.templates.add-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.email-templates.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = history.length > 1 ? document.referrer : '{{ route('admin.dashboard.index') }}'"></i>

                        {{ __('admin::app.marketing.templates.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.marketing.templates.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    {!! view_render_event('bagisto.admin.marketing.templates.create.before') !!}

                    <accordian :title="'{{ __('admin::app.marketing.templates.general') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.marketing.templates.name') }}</label>
                                <input v-validate="'required'" class="control" id="name" name="name" value="{{ old('name') }}" data-vv-as="&quot;{{ __('admin::app.marketing.templates.name') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                                <label for="status" class="required">{{ __('admin::app.marketing.templates.status') }}</label>
                                <select class="control" v-validate="'required'" id="status" name="status" data-vv-as="&quot;{{ __('admin::app.marketing.templates.display-mode') }}&quot;">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                        {{ __('admin::app.marketing.templates.active') }}
                                    </option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                        {{ __('admin::app.marketing.templates.inactive') }}
                                    </option>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                                        {{ __('admin::app.marketing.templates.draft') }}
                                    </option>
                                </select>
                                <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('content') ? 'has-error' : '']">
                                <label for="content" class="required">{{ __('admin::app.marketing.templates.content') }}</label>
                                <textarea v-validate="'required'" class="control" id="content" name="content" data-vv-as="&quot;{{ __('admin::app.marketing.templates.content') }}&quot;">{{ old('content') }}</textarea>
                                <span class="control-error" v-if="errors.has('content')">@{{ errors.first('content') }}</span>
                            </div>
                        </div>
                    </accordian>
                            
                    {!! view_render_event('bagisto.admin.marketing.templates.create.after') !!}

                </div>
            </div>
        </form>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            tinymce.init({
                selector: 'textarea#content',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code table lists link hr',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor link hr | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent  | removeformat | code | table',
                image_advtab: true
            });
        });
    </script>

@endpush