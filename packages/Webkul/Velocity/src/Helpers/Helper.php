<?php

namespace Webkul\Velocity\Helpers;

use DB;
use Webkul\Product\Helpers\Review;
use Webkul\Product\Models\Product as ProductModel;
use Webkul\Velocity\Repositories\OrderBrandsRepository;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\Velocity\Repositories\VelocityMetadataRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Product\Repositories\ProductRepository as ProductRepository;

class Helper extends Review
{
    /**
     * orderBrands object
     *
     * @var object
     */
    protected $orderBrands;

    /**
     * productRepository object
     *
     * @var object
     */
    protected $productRepository;

     /**
     * productModel object
     *
     * @var object
     */
    protected $productModel;

      /**
     * productModel object
     *
     * @var object
     */
    protected $attributeOption;

    /**
     * ProductReviewRepository object
     *
     * @var object
     */
    protected $productReviewRepository;

    /**
     * VelocityMetadata object
     *
     * @var object
     */
    protected $velocityMetadataRepository;

    public function __construct(
        ProductModel $productModel,
        ProductRepository $productRepository,
        AttributeOptionRepository $attributeOption,
        OrderBrandsRepository $orderBrandsRepository,
        ProductReviewRepository $productReviewRepository,
        VelocityMetadataRepository $velocityMetadataRepository
    ) {
        $this->productModel =  $productModel;
        $this->attributeOption =  $attributeOption;
        $this->productRepository = $productRepository;
        $this->orderBrandsRepository = $orderBrandsRepository;
        $this->productReviewRepository =  $productReviewRepository;
        $this->velocityMetadataRepository =  $velocityMetadataRepository;
    }

    public function topBrand($order)
    {
        $orderItems = $order->items;

        foreach ($orderItems as $key => $orderItem) {
            $products[] = $orderItem->product;

            $this->orderBrandsRepository->create([
                'order_id' => $orderItem->order_id,
                'order_item_id' => $orderItem->id,
                'product_id' => $orderItem->product_id,
                'brand' => $products[$key]->brand,
            ]);
        }
    }


    public function getBrandsWithCategories()
    {
        try {
            $orderBrand = $this->orderBrandsRepository->get()->toArray();

            if (isset($orderBrand) && ! empty($orderBrand)) {
                foreach ($orderBrand as $product) {
                    $product_id[] = $product['product_id'];

                    $product_categories = $this->productRepository->with('categories')->findWhereIn('id', $product_id)->toArray();
                }

                $categoryName = $brandName = $brandImplode = [];
                foreach($product_categories as $totalData) {
                    $brand = $this->attributeOption->findOneWhere(['id' => $totalData['brand']]);

                    foreach ($totalData['categories'] as $categories) {
                        foreach($categories['translations'] as $catName) {
                            if (isset($brand->admin_name)) {
                                $brandData[$brand->admin_name][] = $catName['name'];
                                $categoryName[] = $catName['name'];
                            }
                        }
                    }
                }

                $uniqueCategoryName = array_unique($categoryName);

                foreach($uniqueCategoryName as $key => $categoryNameValue) {
                    foreach($brandData as $brandDataKey => $brandDataValue) {
                        if(in_array($categoryNameValue,$brandDataValue)) {
                            $brandName[$categoryNameValue][] = $brandDataKey;
                        }
                    }
                }

                foreach($brandName as $brandKey => $brandvalue) {
                    $brandImplode[$brandKey][] = implode(' | ',array_map("ucfirst", $brandvalue));
                }

                return $brandImplode;
            }
        } catch (Exception $exception){
            throw $exception;
        }
    }

    /**
     * Returns the count rating of the product
     *
     * @param Product $product
     * @return array
    */
    public function getCountRating($product)
    {
        $reviews = $product->reviews()->where('status', 'approved')
            ->select('rating', DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->orderBy('rating','desc')
            ->get();

        $totalReviews = $this->getTotalReviews($product);

        for ($i = 5; $i >= 1; $i--) {
            if (! $reviews->isEmpty()) {
                foreach ($reviews as $review) {
                    if ($review->rating == $i) {
                        $percentage[$i] = $review->total;

                        break;
                    } else {
                        $percentage[$i]=0;
                    }
                }
            } else {
                $percentage[$i]=0;
            }
        }

        return $percentage;
    }

    public function getVelocityMetaData()
    {
        try {
            $metaData = $this->velocityMetadataRepository->get();

            if (! ($metaData && isset($metaData[0]) && $metaData = $metaData[0])) {
                $metaData = null;
            }

            return $metaData;
        } catch (\Exception $exception) {
        }
    }

    public function getShopRecentReviews($reviewCount = 4)
    {
        $reviews = $this->productReviewRepository->getModel()
                ->orderBy('id', 'desc')
                ->where('status', 'approved')
                ->take($reviewCount)->get();

        return $reviews;
    }

    public function jsonTranslations()
    {
        $currentLocale = app()->getLocale();

        $path = __DIR__ . "/../Resources/lang/$currentLocale/app.php";

        if (is_string($path) && is_readable($path)) {
            return include $path;
        }

        return [];
    }

    public function formatCartItem($item)
    {
        $product = $item->product;
        $images = $product->getTypeInstance()->getBaseImage($item);

        return [
            'images' => $images,
            'itemId' => $item->id,
            'name' => $item->name,
            'url_key' => $product->url_key,
            'quantity' => $item->quantity,
            'baseTotal' => core()->currency($item->base_total),
        ];
    }

    public function formatProduct($product, $list = false)
    {
        $reviewHelper = app('Webkul\Product\Helpers\Review');
        $productImageHelper = app('Webkul\Product\Helpers\ProductImage');

        $totalReviews = $reviewHelper->getTotalReviews($product);

        $avgRatings = ceil($reviewHelper->getAverageRating($product));

        $galleryImages = $productImageHelper->getGalleryImages($product);
        $productImage = $productImageHelper->getProductBaseImage($product)['medium_image_url'];

        return [
            'avgRating'         => $avgRatings,
            'totalReviews'      => $totalReviews,
            'image'             => $productImage,
            'galleryImages'     => $galleryImages,
            'name'              => $product->name,
            'slug'              => $product->url_key,
            'description'       => $product->description,
            'shortDescription'  => $product->short_description,
            'firstReviewText'   => trans('velocity::app.products.be-first-review'),
            'priceHTML'         => view('shop::products.price', ['product' => $product])->render(),
            'addToCartHtml'     => view('shop::products.add-to-cart', [
                'product'           => $product,
                'showCompare'       => true,
                'addWishlistClass'  => !(isset($list) && $list) ? '' : '',
                'addToCartBtnClass' => !(isset($list) && $list) ? 'small-padding' : '',
            ])->render(),
        ];
    }
}

