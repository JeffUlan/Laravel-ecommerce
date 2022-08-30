<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;

class ProductFlatRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        Container $container
    )
    {
        parent::__construct($container);
    }

    /**
     * Specify model.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\Product\Contracts\ProductFlat';
    }

    /**
     * Get category product model.
     *
     * @param  int  $categoryId
     * @return \Illuminate\Support\Querybuilder
     */
    public function categoryProductQueryBuilder($categoryId)
    {
        return $this->model
            ->leftJoin('product_categories', 'product_flat.product_id', 'product_categories.product_id')
            ->where('product_categories.category_id', $categoryId)
            ->where('product_flat.channel', core()->getCurrentChannelCode())
            ->where('product_flat.locale', app()->getLocale());
    }

    /**
     * Update `product_flat` custom column.
     *
     * @param  \Webkul\Attribute\Models\Attribute $attribute
     * @param  \Webkul\Product\Listeners\ProductFlat $listener
     * @return object
     */
    public function updateAttributeColumn(
        \Webkul\Attribute\Models\Attribute $attribute,
        \Webkul\Product\Listeners\ProductFlat $listener
    ) {
        return $this->model
            ->leftJoin('product_attribute_values as v', function ($join) use ($attribute) {
                $join->on('product_flat.id', '=', 'v.product_id')
                    ->on('v.attribute_id', '=', \DB::raw($attribute->id));
            })->update(['product_flat.' . $attribute->code => \DB::raw($listener->attributeTypeFields[$attribute->type] . '_value')]);
    }

    /**
     * Get category product attribute.
     *
     * @param  int  $categoryId
     * @return array
     */
    public function getCategoryProductAttribute($categoryId)
    {
        $qb = $this->categoryProductQueryBuilder($categoryId);

        $childQuery = $this->model->distinct()->whereIn('parent_id', $qb->distinct()->select(['id']));

        $attributeValues = $this->model
            ->distinct()
            ->leftJoin('product_attribute_values as pa', 'product_flat.product_id', 'pa.product_id')
            ->leftJoin('attributes as at', 'pa.attribute_id', 'at.id')
            ->leftJoin('product_super_attributes as ps', 'product_flat.product_id', 'ps.product_id')
            ->select('pa.integer_value', 'pa.text_value', 'pa.attribute_id', 'ps.attribute_id as attributeId')
            ->where('is_filterable', 1)
            ->where(function ($query) use ($qb, $childQuery) {
                $query->whereIn('pa.product_id', $qb->distinct()->select(['product_flat.product_id']));
                $query->orWhereIn('pa.product_id', $childQuery->select(['product_flat.product_id']));
            })
            ->get();

        $attributeInfo['attributeOptions'] =  $attributeInfo['attributes'] = [];

        foreach ($attributeValues as $attribute) {
            $attributeKeys = array_keys($attribute->toArray());

            foreach ($attributeKeys as $key) {
                if (! is_null($attribute[$key])) {
                    if (
                        $key == 'integer_value'
                        && ! in_array($attribute[$key], $attributeInfo['attributeOptions'])
                    ) {
                        array_push($attributeInfo['attributeOptions'], $attribute[$key]);
                    } elseif (
                        $key == 'text_value'
                        && ! in_array($attribute[$key], $attributeInfo['attributeOptions'])
                    ) {
                        $multiSelectArrributes = explode(",", $attribute[$key]);

                        foreach ($multiSelectArrributes as $multi) {
                            if (! in_array($multi, $attributeInfo['attributeOptions'])) {
                                array_push($attributeInfo['attributeOptions'], $multi);
                            }
                        }
                    } elseif (
                        (
                            $key == 'attribute_id'
                            || $key == 'attributeId'
                        )
                        && ! in_array($attribute[$key], $attributeInfo['attributes'])
                    ) {
                        array_push($attributeInfo['attributes'], $attribute[$key]);
                    }
                }
            }
        }

        return $attributeInfo;
    }

    /**
     * Filter attributes according to products.
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return \Illuminate\Support\Collection
     */
    public function getProductsRelatedFilterableAttributes($category)
    {
        static $loadedCategoryAttributes = [];

        if (array_key_exists($category->id, $loadedCategoryAttributes)) {
            return $loadedCategoryAttributes[$category->id];
        }

        $productsCount = $this->categoryProductQueryBuilder($category->id)->count();

        if ($productsCount > 0) {
            $categoryFilterableAttributes = $category->filterableAttributes->pluck('id')->toArray();

            $productCategoryAttributes = $this->getCategoryProductAttribute($category->id);

            $allFilterableAttributes = array_filter(array_unique(array_intersect($categoryFilterableAttributes, $productCategoryAttributes['attributes'])));

            $attributes = $this->attributeRepository->getModel()::with([
                'options' => function ($query) use ($productCategoryAttributes) {
                    return $query->whereIn('id', $productCategoryAttributes['attributeOptions'])
                        ->orderBy('sort_order');
                }
            ])->whereIn('id', $allFilterableAttributes)->get();

            return $loadedCategoryAttributes[$category->id] = $attributes;
        } else {
            return $loadedCategoryAttributes[$category->id] = $category->filterableAttributes;
        }
    }

    /**
     * Maximum price of category product.
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return float
     */
    public function getCategoryProductMaximumPrice($category = null)
    {
        static $loadedCategoryMaxPrice = [];

        if (! $category) {
            return $this->model->max('max_price');
        }

        if (array_key_exists($category->id, $loadedCategoryMaxPrice)) {
            return $loadedCategoryMaxPrice[$category->id];
        }

        return $loadedCategoryMaxPrice[$category->id] = $this->model
            ->leftJoin('product_categories', 'product_flat.product_id', 'product_categories.product_id')
            ->where('product_categories.category_id', $category->id)
            ->max('max_price');
    }

    /**
     * Get filter attributes.
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return array
     */
    public function getFilterAttributes($category)
    {
        $filterAttributes = [];

        if (isset($category)) {
            $filterAttributes = $this->getProductsRelatedFilterableAttributes($category);
        }

        if (empty($filterAttributes)) {
            $filterAttributes = $this->attributeRepository->getFilterAttributes();
        }

        return $filterAttributes;
    }

    /**
     * Handle category product max price.
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return float
     */
    public function handleCategoryProductMaximumPrice($category)
    {
        $maxPrice = 0;

        if (isset($category)) {
            $maxPrice = core()->convertPrice($this->getCategoryProductMaximumPrice($category));
        }

        return $maxPrice;
    }
}
