<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * CustomerDataGrid class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerGroupDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('customer_groups')->addSelect('id', 'code', 'name');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('admin::app.datagrid.code'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'title'  => 'Edit Customer Group',
            'method' => 'GET',
            'route'  => 'admin.groups.edit',
            'icon'   => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'title'  => 'Delete Customer Group',
            'method' => 'POST',
            'route'  => 'admin.groups.delete',
            'icon'   => 'icon trash-icon'
        ]);
    }
}