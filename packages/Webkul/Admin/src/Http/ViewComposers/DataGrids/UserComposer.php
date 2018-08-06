<?php

namespace Webkul\Admin\Http\ViewComposers\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

// use App\Repositories\UserRepository;

class UserComposer
{
    /**
     * The Data Grid implementation.
     *
     * @var UserComposer
     * for admin
     */
    protected $users;


    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        $datagrid = DataGrid::make([
            'name' => 'Admins',
            'table' => 'admins',
            'select' => 'id',
            'aliased' => false, //use this with false as default and true in case of joins

            //don't use aliasing in case of filters
            'filterable' => [
                [
                    'column' => 'name',
                    'type' => 'string',
                    'label' => 'Admin Name'
                ]
            ],
            //don't use aliasing in case of searchables
            'searchable' => [
                [
                    'column' => 'email',
                    'type' => 'string',
                    'label' => 'Admin E-Mail'
                ], [
                    'column' => 'name',
                    'type' => 'string',
                    'label' => 'Admin Name'
                ]
            ],
            'massoperations' =>[
                [
                    'route' => route('admin.datagrid.delete'),
                    'method' => 'DELETE',
                    'label' => 'Delete',
                    'type' => 'button',
                ],
                // [
                //     'route' => route('admin.datagrid.index'),
                //     'method' => 'POST',
                //     'label' => 'View Grid',
                //     'type' => 'select',
                //     'options' =>[
                //         1 => 'Edit',
                //         2 => 'Set',
                //         3 => 'Change Status'
                //     ]
                // ],
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
                    'type' => 'string',
                    'label' => 'Admin ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'name',
                    'type' => 'string',
                    'label' => 'Admin Name',
                    'sortable' => true,
                ],
                [
                    'name' => 'email',
                    'type' => 'string',
                    'label' => 'Admin E-Mail',
                    'sortable' => true,
                ],
                // [
                //     'name' => 'a.first_name',
                //     'type' => 'string',
                //     'label' => 'Admin Name',
                //     'sortable' => true,
                //     'filterable' => true,
                //     // will create on run time query
                //     // 'filter' => [
                //     //     'function' => 'where', // orwhere
                //     //     'condition' => ['name', '=', 'Admin'] // multiarray
                //     // ],
                //     'attributes' => [
                //         'class' => 'class-a class-b',
                //         'data-attr' => 'whatever you want',
                //         'onclick' => "window.alert('alert from datagrid column')"
                //      ],
                //     'wrapper' => function ($value, $object) {
                //         return '<a href="'.$value.'">' . $object->first_name . '</a>';
                //     },
                // ],

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
        // $datagrid = DataGrid::make([
        //     'name' => 'Admins',
        //     'table' => 'admins as u',
        //     'select' => 'u.id',
        //     'aliased' => true, //use this with false as default and true in case of joins

        //     //don't use aliasing in case of filters
        //     'filterable' => [
        //         [
        //             'column' => 'u.name',
        //             'type' => 'string',
        //             'label' => 'Admin Name'
        //         ], [
        //             'column' => 'u.id',
        //             'type' => 'number',
        //             'label' => 'Admin ID'
        //         ],
        //         [
        //             'column' => 'r.id',
        //             'type' => 'number',
        //             'label' => 'Role ID'
        //         ]
        //     ],
        //     //don't use aliasing in case of searchables
        //     'searchable' => [
        //         [
        //             'column' => 'u.email',
        //             'type' => 'string',
        //             'label' => 'Admin E-Mail'
        //         ], [
        //             'column' => 'u.name',
        //             'type' => 'string',
        //             'label' => 'Admin Name'
        //         ]
        //     ],
        //     'massoperations' =>[
        //         [
        //             'route' => route('admin.datagrid.delete'),
        //             'method' => 'DELETE',
        //             'label' => 'Delete',
        //             'type' => 'button',
        //         ],
        //         // [
        //         //     'route' => route('admin.datagrid.index'),
        //         //     'method' => 'POST',
        //         //     'label' => 'View Grid',
        //         //     'type' => 'select',
        //         //     'options' =>[
        //         //         1 => 'Edit',
        //         //         2 => 'Set',
        //         //         3 => 'Change Status'
        //         //     ]
        //         // ],
        //     ],
        //     'join' => [
        //         [
        //             'join' => 'leftjoin',
        //             'table' => 'roles as r',
        //             'primaryKey' => 'u.role_id',
        //             'condition' => '=',
        //             'secondaryKey' => 'r.id',
        //         ]
        //     ],

        //     //use aliasing on secodary columns if join is performed
        //     'columns' => [
        //         [
        //             'name' => 'u.id',
        //             'type' => 'string',
        //             'label' => 'Admin ID',
        //             'sortable' => true,
        //         ],
        //         [
        //             'name' => 'u.name',
        //             'type' => 'string',
        //             'label' => 'Admin Name',
        //             'sortable' => true,
        //         ],
        //         [
        //             'name' => 'u.email',
        //             'type' => 'string',
        //             'label' => 'Admin E-Mail',
        //             'sortable' => true,
        //         ],
        //         [
        //             'name' => 'r.name as Role Name',
        //             'type' => 'string',
        //             'label' => 'Role Name',
        //             'sortable' => true,
        //         ],
        //         [
        //             'name' => 'r.id as Role ID',
        //             'type' => 'string',
        //             'label' => 'Role ID',
        //             'sortable' => true,
        //         ],
        //         // [
        //         //     'name' => 'a.first_name',
        //         //     'type' => 'string',
        //         //     'label' => 'Admin Name',
        //         //     'sortable' => true,
        //         //     'filterable' => true,
        //         //     // will create on run time query
        //         //     // 'filter' => [
        //         //     //     'function' => 'where', // orwhere
        //         //     //     'condition' => ['name', '=', 'Admin'] // multiarray
        //         //     // ],
        //         //     'attributes' => [
        //         //         'class' => 'class-a class-b',
        //         //         'data-attr' => 'whatever you want',
        //         //         'onclick' => "window.alert('alert from datagrid column')"
        //         //      ],
        //         //     'wrapper' => function ($value, $object) {
        //         //         return '<a href="'.$value.'">' . $object->first_name . '</a>';
        //         //     },
        //         // ],

        //     ],
        //     'operators' => [
        //         'eq' => "=",
        //         'lt' => "<",
        //         'gt' => ">",
        //         'lte' => "<=",
        //         'gte' => ">=",
        //         'neqs' => "<>",
        //         'neqn' => "!=",
        //         'like' => "like",
        //         'nlike' => "not like",
        //     ],
        //     // 'css' => []

        // ]);

        $view->with('datagrid', $datagrid);
        // $view->with('count', $this->users->count());
    }
}
