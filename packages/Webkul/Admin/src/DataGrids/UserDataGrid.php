<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * UserDataGrid Class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class UserDataGrid extends DataGrid
{
    protected $index = 'user_id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('admins as u')->addSelect('u.id as user_id', 'u.name as user_name', 'u.status as user_status', 'u.email as user_email', 'ro.name as role_name')->leftJoin('roles as ro', 'u.role_id', '=', 'ro.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'user_id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'user_name',
            'label' => trans('admin::app.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'user_status',
            'label' => trans('admin::app.datagrid.status'),
            'type' => 'boolean',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px',
            'wrapper' => function($value) {
                if ($value == 1) {
                    return 'Active';
                } else {
                    return 'Inactive';
                }
            }
        ]);

        $this->addColumn([
            'index' => 'user_email',
            'label' => trans('admin::app.datagrid.email'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'type' => 'Edit',
            'route' => 'admin.roles.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);
    }

    public function prepareMassActions() {
        // $this->prepareMassAction([
        //     'type' => 'delete',
        //     'action' => route('admin.catalog.products.massdelete'),
        //     'method' => 'DELETE'
        // ]);

        // $this->prepareMassAction([
        //     'type' => 'update',
        //     'action' => route('admin.catalog.products.massupdate'),
        //     'method' => 'PUT',
        //     'options' => [
        //         0 => true,
        //         1 => false,
        //     ]
        // ]);
    }
}