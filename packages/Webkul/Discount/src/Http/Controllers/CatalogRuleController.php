<?php

namespace Webkul\Discount\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Product\Repositories\ProductFlatRepository as Product;
use Webkul\Discount\Repositories\CatalogRuleRepository as CatalogRule;

/**
 * Catalog Rule controller
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleController extends Controller
{
    /**
     * Initialize _config, a default request parameter with route
     */
    protected $_config;

    /**
     * Attribute $attribute
     */
    protected $attribute;

    /**
     * AttributeFamily $attributeFamily
     */
    protected $attributeFamily;

    /**
     * Category $category
     */
    protected $category;

    /**
     * Product $product
     */
    protected $product;

    /**
     * Property for catalog rule application
     */
    protected $appliedConfig;

    /**
     * To hold the catalog repository instance
     */
    protected $catalogRule;

    public function __construct(Attribute $attribute, AttributeFamily $attributeFamily, Category $category, Product $product, CatalogRule $catalogRule)
    {
        $this->_config = request('_config');

        $this->attribute = $attribute;

        $this->attributeFamily = $attributeFamily;

        $this->category = $category;

        $this->product = $product;

        $this->catalogRule = $catalogRule;

        $this->appliedConfig = [
            0 => trans('admin::app.promotion.catalog.apply-percent'),
            1 => trans('admin::app.promotion.catalog.apply-fixed'),
            2 => trans('admin::app.promotion.catalog.adjust-to-percent'),
            3 => trans('admin::app.promotion.catalog.adjust-to-value')
        ];
    }

    public function index()
    {
        return view($this->_config['view']);
    }

    public function create()
    {
        return view($this->_config['view'])->with('criteria', [$this->attribute->getNameAndId(), $this->category->getNameAndId()]);
    }

    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|string',
            'description' => 'string',
            'customer_groups' => 'required|array',
            'channels' => 'required|array',
            'starts_from' => 'required|date_format:Y-m-d H:i:s',
            'ends_till' => 'required|date_format:Y-m-d H:i:s',
            'apply' => 'numeric|min:1|max:4'
        ]);

        $catalogRule = $this->catalogRule->create(request()->all());
    }

    public function fetchAttribute()
    {
        return request()->all();
    }
}