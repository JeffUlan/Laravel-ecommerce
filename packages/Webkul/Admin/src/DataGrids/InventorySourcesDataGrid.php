<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * Inventory Sources DataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class InventorySourcesDataGrid
{
    /**
     * The Data Grid implementation.
     *
     * @var InventorySourcesDataGrid
     * for Inventory Sources
     */

    public function createInventorySourcesDataGrid()
    {
        return DataGrid::make([
            'name' => 'Inventory Sources',
            'table' => 'inventory_sources',
            'select' => 'id',
            'perpage' => 10,
            'aliased' => false, //use this with false as default and true in case of joins

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
                    'type' => 'Edit',
                    'route' => route('admin.datagrid.edit'),
                    'confirm_text' => 'Do you really edit this record?',
                    'icon' => 'icon pencil-lg-icon',
                ], [
                    'type' => 'Delete',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to delete this record?',
                    'icon' => 'icon trash-icon',
                ],
            ],

            'join' => [
                // [
                //     'join' => 'leftjoin',
                //     'table' => 'roles as r',
                //     'primaryKey' => 'u.role_id',
                //     'condition' => '=',
                //     'secondaryKey' => 'r.id',
                // ]
            ],

            //use aliasing on secodary columns if join is performed
            'columns' => [

                [
                    'name' => 'id',
                    'alias' => 'inventoryID',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ], [
                    'name' => 'code',
                    'alias' => 'inventoryCode',
                    'type' => 'string',
                    'label' => 'Code',
                    'sortable' => false,
                ], [
                    'name' => 'name',
                    'alias' => 'inventoryName',
                    'type' => 'string',
                    'label' => 'Name',
                    'sortable' => false,
                ], [
                    'name' => 'priority',
                    'alias' => 'inventoryPriority',
                    'type' => 'string',
                    'label' => 'Priority',
                    'sortable' => true,
                ], [
                    'name' => 'status',
                    'alias' => 'inventoryStatus',
                    'type' => 'string',
                    'label' => 'Status',
                    'sortable' => true,
                    'wrapper' => function ($value) {
                        if($value == 0)
                            return "In Active";
                        else
                            return "Active";
                    },
                ],

            ],

            //don't use aliasing in case of filters

            'filterable' => [
                [
                    'column' => 'id',
                    'alias' => 'inventoryID',
                    'type' => 'number',
                    'label' => 'ID',
                ],
                [
                    'column' => 'code',
                    'alias' => 'inventoryCode',
                    'type' => 'string',
                    'label' => 'Code',
                ],
                [
                    'column' => 'name',
                    'alias' => 'inventoryName',
                    'type' => 'string',
                    'label' => 'Name',
                ],
            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                [
                    'column' => 'name',
                    'type' => 'string',
                    'label' => 'Name',
                ],
                [
                    'column' => 'code',
                    'type' => 'string',
                    'label' => 'Code',
                ],
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
        return $this->createInventorySourcesDataGrid()->render();
    }
}