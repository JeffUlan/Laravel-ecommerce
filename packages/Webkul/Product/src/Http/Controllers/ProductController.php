<?php

namespace Webkul\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Http\Requests\ProductForm;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Repositories\ProductGridRepository as ProductGrid;
use Webkul\Product\Repositories\ProductFlatRepository as ProductFlat;
use Webkul\Product\Repositories\ProductAttributeValueRepository as ProductAttributeValue;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Inventory\Repositories\InventorySourceRepository as InventorySource;

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
     * CategoryRepository object
     *
     * @var array
     */
    protected $category;

    /**
     * InventorySourceRepository object
     *
     * @var array
     */
    protected $inventorySource;

    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $product;

    /**
     * ProductGrid Repository object
     *
     * @var array
     */
    protected $productGrid;

    /**
     * ProductFlat Repository Object
     *
     * @vatr array
     */
    protected $productFlat;
    protected $productAttributeValue;
    protected $attribute;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeFamilyRepository  $attributeFamily
     * @param  Webkul\Category\Repositories\CategoryRepository          $category
     * @param  Webkul\Inventory\Repositories\InventorySourceRepository  $inventorySource
     * @param  Webkul\Product\Repositories\ProductRepository            $product
     * @return void
     */
    public function __construct(
        AttributeFamily $attributeFamily,
        Category $category,
        InventorySource $inventorySource,
        Product $product,
        ProductGrid $productGrid,
        ProductFlat $productFlat,
        ProductAttributeValue $productAttributeValue)
    {
        $this->attributeFamily = $attributeFamily;

        $this->category = $category;

        $this->inventorySource = $inventorySource;

        $this->product = $product;

        $this->productGrid = $productGrid;

        $this->productFlat = $productFlat;

        $this->productAttributeValue = $productAttributeValue;

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

        $configurableFamily = null;

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamily->find($familyId);
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
        if (!request()->get('family') && request()->input('type') == 'configurable' && request()->input('sku') != '') {
            return redirect(url()->current() . '?family=' . request()->input('attribute_family_id') . '&sku=' . request()->input('sku'));
        }

        if (request()->input('type') == 'configurable' && (! request()->has('super_attributes') || ! count(request()->get('super_attributes')))) {
            session()->flash('error', 'Please select atleast one configurable attribute.');

            return back();
        }

        $this->validate(request(), [
            'type' => 'required',
            'attribute_family_id' => 'required',
            'sku' => ['required', 'unique:products,sku', new \Webkul\Core\Contracts\Validations\Slug]
        ]);

        $product = $this->product->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Product']));

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
        $product = $this->product->with(['variants'])->find($id);

        $categories = $this->category->getCategoryTree();

        $inventorySources = $this->inventorySource->all();

        return view($this->_config['view'], compact('product', 'categories', 'inventorySources'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\Product\Http\Requests\ProductForm $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductForm $request, $id)
    {
        $product = $this->product->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Product']));

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
        $this->product->delete($id);

        session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Product']));

        return redirect()->back();
    }

    /**
     * Mass Delete the products
     *
     * @return response
     */
    public function massDestroy()
    {
        $productIds = explode(',', request()->input('indexes'));

        foreach ($productIds as $productId) {
            $this->product->delete($productId);
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Mass updates the products
     *
     * @return response
     */
    public function massUpdate()
    {
        $data = request()->all();

        if (!isset($data['massaction-type'])) {
            return redirect()->back();
        }

        if (!$data['massaction-type'] == 'update') {
            return redirect()->back();
        }

        $productIds = explode(',', $data['indexes']);

        foreach ($productIds as $productId) {
            $this->product->update([
                'channel' => null,
                'locale' => null,
                'status' => $data['update-options']
            ], $productId);
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /*
     * To be manually invoked when data is seeded into products
     */
    public function sync()
    {
        Event::fire('products.datagrid.sync', true);

        return redirect()->route('admin.catalog.products.index');
    }

    /**
     * Testing for the product flat sync method on product creation and updation
     */
    public function testProductFlat() {
        $product = $this->product->find(4);
        $allChannelAndLocales = [];
        $productMapped = [];
        $channelLocaleMap = [];

        foreach(core()->getAllChannels() as $channel) {
            array_push($productMapped, [
                'product_id' => $product->id,
                'type' => $product->type,
                'channel_code' => $channel->code,
                'locale_code' => 'null',
            ]);

            array_push($channelLocaleMap, [
                'product_id' => $product->id,
                'type' => $product->type,
                'channel_code' => $channel->code,
                'locale_code' => 'null',
            ]);

            foreach($channel->locales as $locale) {
                array_push($productMapped, [
                    'product_id' => $product->id,
                    'type' => $product->type,
                    'channel_code' => $channel->code,
                    'locale_code' => $locale->code
                ]);

                array_push($channelLocaleMap, [
                    'product_id' => $product->id,
                    'type' => $product->type,
                    'channel_code' => $channel->code,
                    'locale_code' => $locale->code,
                ]);

                array_push($productMapped, [
                    'product_id' => $product->id,
                    'type' => $product->type,
                    'channel_code' => 'null',
                    'locale_code' => $locale->code,
                ]);

                array_push($channelLocaleMap, [
                    'product_id' => $product->id,
                    'type' => $product->type,
                    'channel_code' => 'null',
                    'locale_code' => $locale->code,
                ]);
            }
        }

        $attributes = $product->attribute_family->custom_attributes;

        foreach($attributes as $key => $attribute) {
            if($attribute->value_per_channel && $attribute->value_per_locale) {
                $values = $this->productAttributeValue->findWhere(['attribute_id' => $attribute->id, 'product_id' => $product->id]);

                foreach($values as $key => $value) {
                    $this->pushCorrect($value->channel, $value->locale, $productMapped);
                }
            } else if($attribute->value_per_channel && !$attribute->value_per_locale) {
                $this->pushCorrect($value->channel, $value->locale, $productMapped);
            } else if($attribute->value_per_locale) {
                $this->pushCorrect($value->channel, $value->locale, $productMapped);
            } else {
                $this->pushCorrect($value->channel, $value->locale, $productMapped);
            }

            // if($attribute->type == 'select') {
            //     if($attribute->value_per_channel && $attribute->value_per_locale) {
            //         dd($this->productAttributeValue->findWhere(['attribute_id' => $attribute->id]));

            //         // $this->pushCorrect($attribute->channel);
            //     } else if($attribute->value_per_channel && !$attribute->value_per_locale) {
            //         // $this->pushCorrect();
            //     } else if($attribute->value_per_locale) {
            //         // $this->pushCorrect();
            //     } else {
            //         // $this->pushCorrect();
            //     }
            // } else if($attribute->type == 'multiselect') {
            //     // $this->pushCorrect();
            // } else {
            //     if($attribute->value_per_channel && $attribute->value_per_locale) {
            //         dd($this->productAttributeValue->findWhere(['attribute_id' => $attribute->id]));

            //         // $this->pushCorrect($attribute->channel);
            //     } else if($attribute->value_per_channel && !$attribute->value_per_locale) {
            //         // $this->pushCorrect();
            //     } else if($attribute->value_per_locale) {
            //         // $this->pushCorrect();
            //     } else {
            //         // $this->pushCorrect();
            //     }
            // }
        }

        dd($productMapped);
    }

    public function pushCorrect($channelCode = null, $localeCode = null, $productMapped) {
        dd($channelCode, $localeCode, $productMapped);
    }
}