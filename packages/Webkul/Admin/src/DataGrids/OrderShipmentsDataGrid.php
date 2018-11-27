<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * OrderShipmentsDataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class OrderShipmentsDataGrid
{
    /**
     * The Order Shipments Data Grid implementation.
     *
     * @var OrderShipmentsDataGrid
     * for shipments of orders
     */
    public function createOrderShipmentsDataGrid()
    {
        return DataGrid::make([
            'name' => 'shipments',
            'table' => 'shipments as ship',
            'select' => 'ship.id',
            'perpage' => 10,
            'aliased' => true,

            'massoperations' =>[
                // [
                //     'route' => route('admin.datagrid.delete'),
                //     'method' => 'DELETE',
                //     'label' => 'Delete',
                //     'type' => 'button',
                // ],
            ],

            'actions' => [
                [
                    'type' => 'View',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to do this?',
                    'icon' => 'icon pencil-lg-icon',
                ],
            ],

            'join' => [
                [
                    'join' => 'leftjoin',
                    'table' => 'orders as ors',
                    'primaryKey' => 'ship.order_id',
                    'condition' => '=',
                    'secondaryKey' => 'ors.id',
                ]
            ],

            //use aliasing on secodary columns if join is performed
            'columns' => [
                [
                    'name' => 'ship.id',
                    'alias' => 'shipID',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true
                ], [
                    'name' => 'ship.order_id',
                    'alias' => 'order_id',
                    'type' => 'number',
                    'label' => 'Order ID',
                    'sortable' => true
                ], [
                    'name' => 'ship.total_qty',
                    'alias' => 'total_qty',
                    'type' => 'number',
                    'label' => 'Total Quantity',
                    'sortable' => true
                ], [
                    'name' => 'ors.customer_first_name',
                    'alias' => 'order_customer_first_name',
                    'type' => 'string',
                    'label' => 'Customer Name',
                    'sortable' => false
                ], [
                    'name' => 'ors.created_at',
                    'alias' => 'orscreated',
                    'type' => 'date',
                    'label' => 'Order Date',
                    'sortable' => true
                ], [
                    'name' => 'ship.status',
                    'alias' => 'shipstatus',
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
                        else
                            return 'Un-Attended';
                    },
                ], [
                    'name' => 'ship.created_at',
                    'alias' => 'ship_date',
                    'type' => 'string',
                    'label' => 'Shipment Date',
                    'sortable' => false
                ]
            ],

            'filterable' => [
                [
                    'column' => 'ship.id',
                    'alias' => 'shipID',
                    'type' => 'number',
                    'label' => 'ID',
                ]
            ],
            //don't use aliasing in case of searchables

            'searchable' => [
                // [
                //     'column' => 'or.id',
                //     'alias' => 'orderid',
                //     'type' => 'number',
                //     'label' => 'ID',
                // ]
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
        return $this->createOrderShipmentsDataGrid()->render();
    }
}