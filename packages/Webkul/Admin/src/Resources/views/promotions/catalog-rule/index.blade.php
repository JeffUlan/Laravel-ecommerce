@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotion.catalog-rule') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.promotion.catalog-rule') }}</h1>
            </div>

            <div class="page-action">
                <button class="btn btn-lg btn-primary">
                    {{ __('Apply Rules') }}
                </button>

                <a href="{{ route('admin.catalog-rule.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.promotion.add-catalog-rule') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('customerGrid','Webkul\Admin\DataGrids\CustomerDataGrid')
            {!! $customerGrid->render() !!}
        </div>
    </div>
@endsection