<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Admin\Facades\Configuration;
use Webkul\Core\Repositories\CoreConfigRepository as CoreConfig;

/**
 * Configuration controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * CoreConfigRepository object
     *
     * @var array
     */
    protected $coreConfig;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Core\Repositories\CoreConfigRepository  $coreConfig
     * @return void
     */
    public function __construct(CoreConfig $coreConfig)
    {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->coreConfig = $coreConfig;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if(!request()->route('slug'))
        //     return redirect()->route('admin.configuration.index', ['slug' => 'marketplace']);

        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->all();

        session()->flash('success', 'Shipping Method is created successfully');

        return redirect()->route($this->_config['redirect']);
    }
}