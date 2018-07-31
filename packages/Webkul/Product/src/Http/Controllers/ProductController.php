<?php

namespace Webkul\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;

/**
 * Product controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;
    
    /**
     * AttributeFamilyRepository object
     *
     * @var array
     */
    protected $attributeFamily;
    
    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $product;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeFamilyRepository  $attributeFamily
     * @param  Webkul\Product\Repositories\ProductRepository            $product
     * @return void
     */
    public function __construct(AttributeFamily $attributeFamily, Product $product)
    {
        $this->attributeFamily = $attributeFamily;

        $this->product = $product;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $families = $this->attributeFamily->all();

        if($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamily->findOrFail($familyId);
        }

        return view($this->_config['view'], compact('families', 'configurableFamily'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        if(!request()->get('family') && request()->input('type') == 'configurable') {
            return redirect(url()->current() . '?family=' . request()->input('attribute_family_id'));
        }

        $this->validate(request(), [
            'type' => 'required',
            'attribute_family_id' => 'required',
            'sku' => ['required', 'unique:products,sku', new \Webkul\Core\Contracts\Validations\Slug],
            'super_attributes' => 'required|array|min:1'
        ]);

        $product = $this->product->create(request()->all());

        session()->flash('success', 'Product created successfully.');

        return redirect()->route($this->_config['redirect'], ['id' => $product->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $this->product->findOrFail($id);

        return view($this->_config['view'], compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'name' => 'required',
        ]);

        $this->product->update(request()->all(), $id);

        session()->flash('success', 'Product updated successfully.');

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}