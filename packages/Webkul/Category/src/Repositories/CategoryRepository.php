<?php 

namespace Webkul\Category\Repositories;
 
use Webkul\Core\Eloquent\Repository;
use Webkul\Category\Models\Category;
use Illuminate\Container\Container as App;
use Webkul\Category\Models\CategoryTranslation;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Category Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CategoryRepository extends Repository
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Category\Models\Category';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        if(isset($data['locale']) && $data['locale'] == 'all') {
            $model = app()->make($this->model());
            
            foreach(core()->getAllLocales() as $locale) {
                foreach ($model->translatedAttributes as $attribute) {
                    if(isset($data[$attribute])) {
                        $data[$locale->code][$attribute] = $data[$attribute];
                    }
                }
            }
        }

        return $this->model->create($data);
    }

    /**
     * Specify category tree
     *
     * @param integer $id
     * @return mixed
     */
    public function getCategoryTree($id = null)
    {
        return $id
            ? Category::orderBy('position', 'ASC')->where('id', '!=', $id)->get()->toTree()
            : Category::orderBy('position', 'ASC')->get()->toTree();
    }

    /**
     * Checks slug is unique or not based on locale
     *
     * @param integer $id
     * @param string  $slug
     * @return boolean
     */
    public function isSlugUnique($id, $slug)
    {
        return CategoryTranslation::where('category_id', '!=', $id)->where('slug', '=', $slug)->first() ? false : true;
    }

    /**
     * Retrive category from slug
     *
     * @param string $slug
     * @return mixed
     */
    public function findBySlugOrFail($slug)
    {
        $category = $this->model->whereTranslation('slug', $slug)->first();

        if($category)
            return $category;

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $slug
        );
    }
}