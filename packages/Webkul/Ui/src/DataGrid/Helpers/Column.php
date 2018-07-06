<?php
namespace Webkul\Ui\DataGrid\Helpers;

use Illuminate\Http\Request;

class Column extends AbstractFillable
{   
    const SORT = 'sort';
    const ORDER_DESC = 'DESC';
    const ORDER_ASC = 'ASC';

    private $request = null;
    private $readableName = false;
    private $value = false;
    private $sortHtml = '<a href="%s">%s</a>';

    // protected $name;
    // protected $type;
    // protected $label;
    // protected $filterable;
    // protected $sortable;
    // protected $attributes;
    // protected $wrapper;
    // protected $callback;


    /**
     * Without Array it will treat it like string 
     * 
     * [
     *      'name',
     *      'Name',
     *       true,
     *       filter => [
     *          'function' => 'where', // andwhere
     *          'condition' => ['u.user_id', '=', '1'] // multiarray
     *       ],
     *       attributes => [
     *           'class' => 'class-a, class-b',
     *           'data-attr' => 'whatever you want',
     *           'onlclick' => "window.alert('alert from datagrid column')"
     *       ],
     *       wrapper => function($value){
     *          return '<a href="'.$value.'">Nikhil</a>';
     *       },
     * ]
     */
    protected function setFillable(){
        $this->fillable = [
            'name',
            'type',
            'label',
            'sortable',
            'filterable',
            'filter' => [
                'allowed' => 'array',
            ],
            'attributes' => [
                'allowed' => 'array',
                'validation' => 'FUTURE'
            ],
            'wrapper' => [
                // 'allowed' => 'function'
                'allowed' => 'object'
            ],
            'callback' => [
                'allowed' => 'function'
            ]
        ];
    }

    public function __construct($args, $request = null){
        parent::__construct($args);
        $this->request = $request ?: Request::capture();
    }

    private function correct($tillDot = true){
        $as = explode('as', $this->name);
        if(count($as) > 1) return trim(end($as));

        if(!$tillDot) return $this->name;

        $dot = explode('.', $this->name);
        if($dot) return trim(end($dot));
    }

    private function wrap($obj){
        if($this->wrapper && is_callable($this->wrapper)){
            $this->value = call_user_func($this->wrapper, $this->value, $obj);
        }
    }

    private function sortingUrl(){
        $query = ['sort' => $this->correct(false)];

        if(($sort = $this->request->offsetGet('sort')) && $sort == $this->correct(false)){
            if(!$order = $this->request->offsetGet('order')){
                $query['order'] = self::ORDER_DESC;
            }else{
                $query['order'] = ($order == self::ORDER_DESC ? self::ORDER_ASC : self::ORDER_DESC);
            }
        }else{
            $query['order'] = self::ORDER_DESC;
        }
        
        return '?'.http_build_query(array_merge($this->request->query->all(), $query));
    }

    /**
     * need to process xss check on properties like label shouln't include <script>alert('Kaboom!')</script> 
     */
    public function sorting(){        
        if($this->sortable){
            return vsprintf($this->sortHtml, [$this->sortingUrl(), $this->label]);
        }else{
            return $this->label;
        }
    }

    public function render($obj){
        if(property_exists($obj, ( $this->readableName = $this->correct()) ) ){
            $this->value = $obj->{$this->readableName};
            $this->wrap($obj);
        }
        return $this->value;
    }
}
