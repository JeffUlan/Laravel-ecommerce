@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.sales.orders.title') }}
@stop

@inject('orderGrid', 'Webkul\Admin\DataGrids\OrderDataGrid')

@section('content')
    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.sales.orders.title') }}</h1>
            </div>

            <div class="page-action">
                <form method="POST" action="{{ route('admin.datagrid.export') }}">
                    @csrf()
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.customers.customers.export') }}
                    </button>
                    <input type="hidden" name="gridData" value="{{serialize($orderGrid)}}">
                </form>
            </div>
        </div>

        <div class="page-content">
            {!! $orderGrid->render() !!}
        </div>
    </div>
@stop