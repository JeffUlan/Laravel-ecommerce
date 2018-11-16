<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * orderDataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class OrderDataGrid
{
    /**
     * The Data Grid implementation.
     *
     * @var orderDataGrid
     * for orders
     */
    public function createOrderDataGrid()
    {

            return DataGrid::make([
            'name' => 'orders',
            'table' => 'orders as or',
            'select' => 'or.id',
            'perpage' => 10,
            'aliased' => false,
            //True in case of joins else aliasing key required on all cases

            'massoperations' =>[
                [
                    'route' => route('admin.datagrid.delete'),
                    'method' => 'DELETE',
                    'label' => 'Delete',
                    'type' => 'button',
                ],
            ],

            'actions' => [
                [
                    'type' => 'View',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to do this?',
                    'icon' => 'icon pencil-lg-icon',
                ],
                [
                    'type' => 'Delete',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to do this?',
                    'icon' => 'icon trash-icon',
                ],
            ],

            'join' => [],

            //use aliasing on secodary columns if join is performed

            'columns' => [
                [
                    'name' => 'or.id',
                    'alias' => 'orderid',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ], [
                    'name' => 'or.customer_first_name',
                    'alias' => 'oafirstname',
                    'type' => 'string',
                    'label' => 'Billed To',
                    'sortable' => false,
                ], [
                    'name' => 'or.base_grand_total',
                    'alias' => 'orbasegrandtotal',
                    'type' => 'string',
                    'label' => 'Base Total',
                    'sortable' => true,
                    'wrapper' => function ($value) {
                        return core()->currency($value);
                    }
                ], [
                    'name' => 'or.grand_total',
                    'alias' => 'oagrandtotal',
                    'type' => 'string',
                    'label' => 'Grand Total',
                    'sortable' => false,
                    'wrapper' => function ($value) {
                        return core()->currency($value);
                    }
                ], [
                    'name' => 'or.created_at',
                    'alias' => 'created_at',
                    'type' => 'string',
                    'label' => 'Order Date',
                    'sortable' => false,
                ], [
                    'name' => 'or.status',
                    'alias' => 'orstatus',
                    'type' => 'string',
                    'label' => 'Status',
                    'sortable' => true,
                    'wrapper' => function ($value) {
                        if($value == 'processing')
                            return '<span class="badge badge-md badge-success">Processing</span>';
                        else if($value == 'completed')
                            return '<span class="badge badge-md badge-success">Completed</span>';
                        else if($value == "canceled")
                            return '<span class="badge badge-md badge-danger">Canceled</span>';
                        else if($value == "closed")
                            return '<span class="badge badge-md badge-info">Closed</span>';
                        else if($value == "pending")
                            return '<span class="badge badge-md badge-warning">Pending</span>';
                        else if($value == "pending_payment")
                            return '<span class="badge badge-md badge-warning">Pending Payment</span>';
                        else if($value == "fraud")
                            return '<span class="badge badge-md badge-danger">Fraud</span>';
                    },
                ],
            ],

            'filterable' => [
                [
                    'column' => 'or.id',
                    'alias' => 'orderid',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'name' => 'or.status',
                    'alias' => 'orstatus',
                    'type' => 'string',
                    'label' => 'Status'
                ]
            ],
            //don't use aliasing in case of searchables

            'searchable' => [
                [
                    'column' => 'or.id',
                    'alias' => 'orderid',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'name' => 'or.status',
                    'alias' => 'orstatus',
                    'type' => 'string',
                    'label' => 'Status'
                ]
            ],

            //list of viable operators that will be used
            'operators' => [
                'eq' => "=",
                'lt' => "<",
                'gt' => ">",
                'lte' => "<=",
                'gte' => ">=",
                'neqs' => "<>",
                'neqn' => "!=",
                'like' => "like",
                'nlike' => "not like",
            ],
            // 'css' => []
        ]);
    }

    public function render()
    {
        return $this->createOrderDataGrid()->render();
    }
}