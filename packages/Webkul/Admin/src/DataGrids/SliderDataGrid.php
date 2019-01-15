<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * SliderDataGrid Class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SliderDataGrid extends DataGrid
{
    protected $index = 'id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('sliders as sl')->addSelect('sl.id', 'sl.title', 'ch.name')->leftJoin('channels as ch', 'sl.channel_id', '=', 'ch.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'sl.id',
            'identifier' => 'slider_id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'sl.title',
            'identifier' => 'slider_title',
            'label' => trans('admin::app.datagrid.title'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'ch.name',
            'identifier' => 'channel_name',
            'label' => trans('admin::app.datagrid.channel-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'type' => 'Edit',
            'route' => 'admin.sliders.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'route' => 'admin.sliders.delete',
            'icon' => 'icon trash-icon'
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