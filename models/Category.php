<?php namespace Lbaig\Catalog\Models;

use Model;
use Cms\Classes\Page as CmsPage;
use Cms\Classes\Theme;

/**
 * Category Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\NestedTree;
    use \October\Rain\Database\Traits\Sluggable;
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'lbaig_catalog_categories';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'slug',
        'active'
    ];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $slugs = [
        'slug' => 'name'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'products' => 'Lbaig\Catalog\Models\Product',
        'subcategories' => [
            'Lbaig\Catalog\Models\Category',
            'key' => 'parent_id'
        ]
    ];
    public $belongsTo = ['parent' => 'Lbaig\Catalog\Models\Category'
    ];
    public $belongsToMany = [
        'properties' => ['Lbaig\Catalog\Models\Property',
                         'table' => 'lbaig_catalog_category_property'],
        'other_products' => ['Lbaig\Catalog\Models\Product',
                            'table' => 'lbaig_catalog_category_product']
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
        'preview_image' => 'System\Models\File'
    ];
    public $attachMany = [];


    public function scopeActive($query)
    {
        $query->where('active', true);
    }

    public function getProductCountAttribute()
    {
        return $this->products->count();
    }

    public function getNestedCountAttribute()
    {
        // select all subcategories as well as category
        $subcategory_ids = Category::active()
                         ->where('nest_left', '>=', $this->nest_left)
                         ->where('nest_right', '<=', $this->nest_right)
                         ->pluck('id');

        // select all products that belong to this category or lower
        $products = Product::active()
                  ->whereIn('category_id', $subcategory_ids)
                  ->count();

        return $products;
    }

    public function getAllProductsCountAttribute()
    {
        $direct_product_count = $this->getNestedCountAttribute();
        $other_product_count = Product::active()
                             ->whereHas('other_categories', function ($query) {
                                 $query->where('lbaig_catalog_categories.id', $this->id);
                             })
                             ->count();
        $all_product_count = $direct_product_count + $other_product_count;
        return $all_product_count;
    }

    public static function getMenuTypeInfo($type)
    {
        $result = [];
        if ($type == 'catalog-category') {
            $result = [
                'references' => self::listSubCategoryOptions(),
                'nesting' => true,
                'dynamicItems' => true
            ];
        }

        if ($type == 'all-catalog-categories') {
            $result = [
                'dynamicItems' => true
            ];
        }

        if ($result) {
            $theme = Theme::getActiveTheme();
            $pages = CmsPage::listInTheme($theme, true);
            $cmsPages = [];
            foreach ($pages as $page) {
                if (!$page->hasComponent('CategoryItem')) {
                    continue;
                }

                $properties = $page->getComponentProperties('CategoryItem');
                if (!isset($properties['slug']) || !preg_match('/{{\s*:/', $properties['slug'])) {
                    continue;
                }

                $cmsPages[] = $page;
            }

            $result['cmsPages'] = $cmsPages;
        }

        return $result;
    }

    protected static function listSubCategoryOptions()
    {
        $category = self::getNested();

        $iterator = function($categories) use (&$iterator) {
            $result = [];

            foreach ($categories as $category) {
                if (!$category->children) {
                    $result[$category->id] = $category->name;
                }
                else {
                    $result[$category->id] = [
                        'title' => $category->name,
                        'items' => $iterator($category->children)
                    ];
                }
            }

            return $result;
        };

        return $iterator($category);
    }

    public static function resolveMenuItem($item, $url, $theme)
    {
        $result = null;

        if ($item->type == 'catalog-catagory') {
            if (!$item->reference || $item->cmsPage) {
                return;
            }

            $category = self::find($item->reference);
            if (!$category) {
                return;
            }

            $pageUrl = self::getCategoryPageUrl($item->cmsPage, $category, $theme);
            if (!$pageUrl) {
                return;
            }

            $pageUrl = Url::to($pageUrl);

            $result = [];
            $result['url'] = $pageUrl;
            $result['isActive'] = $pageUrl == $url;
            $result['mtime'] = $category->updated_at;

            if ($item->nesting) {
                $categories = $category->getNested();
                $iterator = function($categories) use (&$iterator, &$item, &$theme, $url) {
                    $branch = [];

                    foreach ($categories as $category) {

                        $branchItem = [];
                        $branchItem['url'] = self::getCategoryPageUrl($item->cmsPage, $category, $theme);
                        $branchItem['isActive'] = $branchItem['url'] == $url;
                        $branchItem['title'] = $category->name;
                        $branchItem['mtime'] = $category->updated_at;

                        if ($category->children) {
                            $branchItem['items'] = $iterator($category->children);
                        }

                        $branch[] = $branchItem;
                    }

                    return $branch;
                };

                $result['items'] = $iterator($categories);
            }
        }
        elseif ($item->type == 'all-catalog-categories') {
            $result = [
                'items' => []
            ];

            $categories = self::orderBy('name')->get();
            foreach ($categories as $category) {
                $categoryItem = [
                    'title' => $category->name,
                    'url'   => self::getCategoryPageUrl($item->cmsPage, $category, $theme),
                    'mtime' => $category->updated_at
                ];

                $categoryItem['isActive'] = $categoryItem['url'] == $url;

                $result['items'][] = $categoryItem;
            }
        }

        return $result;
    }

    protected static function getCategoryPageUrl($pageCode, $category, $theme)
    {
        $page = CmsPage::loadCached($theme, $pageCode);
        if (!$page) {
            return;
        }

        $properties = $page->getComponentProperties('CategoryItem');
        if (!isset($properties['slug'])) {
            return;
        }

        /*
         * Extract the routing parameter name from the category filter
         * eg: {{ :someRouteParam }}
         */
        if (!preg_match('/^\{\{([^\}]+)\}\}$/', $properties['slug'], $matches)) {
            return;
        }

        $paramName = substr(trim($matches[1]), 1);
        $url = CmsPage::url($page->getBaseFileName(), [$paramName => $category->slug]);

        return $url;
    }
}
