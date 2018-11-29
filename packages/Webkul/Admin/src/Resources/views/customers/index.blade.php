@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.customers.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.customers.customers.title') }}</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('admin.customer.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.customers.customers.add-title') }}
                </a>


                <a href="{{ route('admin.datagrid.export') }}" class="btn btn-lg btn-primary">
                    Export
                </a>

            </div>
        </div>


        <div class="page-content">
            @inject('customer','Webkul\Admin\DataGrids\CustomerDataGrid')
            {!! $customer->render() !!}
        </div>
    </div>

@stop

