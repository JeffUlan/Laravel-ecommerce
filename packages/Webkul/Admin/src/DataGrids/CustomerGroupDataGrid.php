<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * Customer Group DataGrid
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class CustomerGroupDataGrid
{
    /**
     * The Customer Group Data
     * Grid implementation.
     *
     * @var CustomerGroupDataGrid
     */
    public function createCustomerGroupDataGrid()
    {

        return DataGrid::make([

            'name' => 'Customer Group',
            'table' => 'customer_groups as cg',
            'select' => 'cg.id',
            'perpage' => 10,
            'aliased' => true, //use this with false as default and true in case of joins

            'massoperations' =>[
                [
                    'route' => route('admin.datagrid.delete'),
                    'method' => 'DELETE',
                    'label' => 'Delete',
                    'type' => 'button',
                ],
                [
                    'route' => route('admin.datagrid.index'),
                    'method' => 'POST',
                    'label' => 'View Grid',
                    'type' => 'select',
                    'options' =>[
                        1 => 'Edit',
                        2 => 'Set',
                        3 => 'Change Status'
                    ]
                ],
            ],
            'actions' => [
                [
                    'type' => 'Edit',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really wanis?',
                    'icon' => 'icon pencil-lg-icon',
                ],
                [
                    'type' => 'Delete',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to do this?',
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
                    'name' => 'cg.id',
                    'alias' => 'ID',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'cg.group_name',
                    'alias' => 'Name',
                    'type' => 'string',
                    'label' => 'Name',
                    'sortable' => true,
                ],
                [
                    'name' => 'cg.is_user_defined',
                    'alias' => 'User Defined',
                    'type' => 'boolean',
                    'label' => 'User Defined',
                    'sortable' => true,
                    'wrapper' => function ($value) {
                        if($value == 1)
                            return "False";
                        else
                            return "True";
                    },
                ],
            ],
            //don't use aliasing in case of filters
            'filterable' => [
                [
                    'column' => 'cg.group_name',
                    'alias' => 'Name',
                    'type' => 'string',
                    'label' => 'Name'
                ],
                [
                    'column' => 'cg.id',
                    'alias' => 'ID',
                    'type' => 'number',
                    'label' => 'ID'
                ],
                [
                    'column' => 'cg.is_user_defined',
                    'alias' => 'User Defined',
                    'type' => 'boolean',
                    'label' => 'User Defined'
                ]
            ],
            //don't use aliasing in case of searchables
            'searchable' => [
                [
                    'column' => 'cg.code',
                    'type' => 'string',
                    'label' => 'Code'
                ],
                [
                    'column' => 'cg.name',
                    'type' => 'string',
                    'label' => 'Name'
                ]
            ],
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

    public function render() {

        return $this->createCustomerGroupDataGrid()->render();

    }
}