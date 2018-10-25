@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.categories.edit-title') }}
@stop

@section('content')
    <div class="content">
        <?php $locale = request()->get('locale') ?: app()->getLocale(); ?>

        <form method="POST" action="" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.catalog.categories.edit-title') }}</h1>

                    <div class="control-group">
                        <select class="control" id="locale-switcher" onChange="window.location.href = this.value">
                            @foreach(core()->getAllLocales() as $localeModel)

                                <option value="{{ route('admin.catalog.categories.update', $category->id) . '?locale=' . $localeModel->code }}" {{ ($localeModel->code) == $locale ? 'selected' : '' }}>
                                    {{ $localeModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.catalog.categories.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()
                    <input name="_method" type="hidden" value="PUT">

                    <accordian :title="'{{ __('admin::app.catalog.categories.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('{{$locale}}[name]') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.catalog.categories.name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="name" name="{{$locale}}[name]" value="{{ old($locale)['name'] ?: $category->translate($locale)['name'] }}"/>
                                <span class="control-error" v-if="errors.has('{{$locale}}[name]')">@{{ errors.first('{!!$locale!!}[name]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                                <label for="status" class="required">{{ __('admin::app.catalog.categories.visible-in-menu') }}</label>
                                <select class="control" v-validate="'required'" id="status" name="status">
                                    <option value="1" {{ $category->status ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.categories.yes') }}
                                    </option>
                                    <option value="0" {{ $category->status ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.categories.no') }}
                                    </option>
                                </select>
                                <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('position') ? 'has-error' : '']">
                                <label for="position">{{ __('admin::app.catalog.categories.position') }}</label>
                                <input type="text" v-validate="'required|numeric'" class="control" id="position" name="position" value="{{ old('position') ?: $category->position }}"/>
                                <span class="control-error" v-if="errors.has('position')">@{{ errors.first('position') }}</span>
                            </div>

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.catalog.categories.description-and-images') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('{{$locale}}[description]') ? 'has-error' : '']">
                                <label for="description" class="required">{{ __('admin::app.catalog.categories.description') }}</label>
                                <textarea v-validate="'required'" class="control" id="description" name="{{$locale}}[description]">{{ old($locale)['description'] ?: $category->translate($locale)['description'] }}</textarea>
                                <span class="control-error" v-if="errors.has('{{$locale}}[description]')">@{{ errors.first('{!!$locale!!}[description]') }}</span>
                            </div>

                            <div class="control-group">
                                <label>{{ __('admin::app.catalog.categories.image') }}

                                <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="image" :multiple="false"></image-wrapper>

                            </div>

                        </div>
                    </accordian>

                    @if($categories->count())
                    <accordian :title="'{{ __('admin::app.catalog.categories.parent-category') }}'" :active="true">
                        <div slot="body">

                            <tree-view value-field="id" name-field="parent_id" input-type="radio" items='@json($categories)' value='@json($category->parent_id)'></tree-view>

                        </div>
                    </accordian>
                    @endif

                    <accordian :title="'{{ __('admin::app.catalog.categories.seo') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group">
                                <label for="meta_title">{{ __('admin::app.catalog.categories.meta_title') }}</label>
                                <input type="text" class="control" id="meta_title" name="{{$locale}}[meta_title]" value="{{ old($locale)['meta_title'] ?: $category->translate($locale)['meta_title'] }}"/>
                            </div>

                            <div class="control-group" :class="[errors.has('{{$locale}}[slug]') ? 'has-error' : '']">
                                <label for="slug" class="required">{{ __('admin::app.catalog.categories.slug') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="slug" name="{{$locale}}[slug]" value="{{ old($locale)['slug'] ?: $category->translate($locale)['slug'] }}" v-slugify/>
                                <span class="control-error" v-if="errors.has('{{$locale}}[slug]')">@{{ errors.first('{!!$locale!!}[slug]') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="meta_description">{{ __('admin::app.catalog.categories.meta_description') }}</label>
                                <textarea class="control" id="meta_description" name="{{$locale}}[meta_description]">{{ old($locale)['meta_description'] ?: $category->translate($locale)['meta_description'] }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="meta_keywords">{{ __('admin::app.catalog.categories.meta_keywords') }}</label>
                                <textarea class="control" id="meta_keywords" name="{{$locale}}[meta_keywords]">{{ old($locale)['meta_keywords'] ?: $category->translate($locale)['meta_keywords'] }}</textarea>
                            </div>

                        </div>
                    </accordian>

                </div>
            </div>

        </form>
    </div>
@stop