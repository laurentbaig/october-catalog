<?php namespace Lbaig\Catalog\Models;

use Model;
use Catalog;
use Cms\Classes\Page as CmsPage;
use Cms\Classes\Theme;

/**
 * Product Model
 */
class Product extends Model
{
    use \October\Rain\Database\Traits\Sluggable;
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'lbaig_catalog_products';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

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

    protected $slugs = [
        'slug' => 'name'
    ];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'category' => 'Lbaig\Catalog\Models\Category'
    ];
    public $belongsToMany = [
        'properties' => ['Lbaig\Catalog\Models\Property',
                         'table'=>'lbaig_catalog_product_property'],
        'other_categories' => ['Lbaig\Catalog\Models\Category',
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
        $query->where('lbaig_catalog_products.active', true);
    }

    public function afterCreate()
    {
        $categoryPropertyIds = $this->category->properties->pluck('id');
        $this->properties()->sync($categoryPropertyIds);
    }

    public function getNameCategoryAttribute($value)
    {
        //$category = Category::find($this->category_id);
        $this->load('category');
        return $this->name . ' - ' . $this->category->name . " ({$this->id})";
    }

    public static function getMenuTypeInfo($type)
    {
        $result = [];

        if ($type == 'catalog-product') {
            $references = [];

            $products = self::orderBy('name')->get();
            foreach ($products as $product) {
                $references[$product->id] = $product->name;
            }

            $result = [
                'references'   => $references,
                'nesting'      => false,
                'dynamicItems' => false
            ];
        }

        if ($type == 'all-catalog-products') {
            $result = [
                'dynamicItems' => true
            ];
        }

        if ($type == 'category-catalog-products') {
            $references = [];

            $categories = Category::orderBy('name')->get();
            foreach ($categories as $category) {
                $references[$category->id] = $category->name;
            }

            $result = [
                'references'   => $references,
                'dynamicItems' => true
            ];
        }

        if ($result) {
            $theme = Theme::getActiveTheme();
            $pages = CmsPage::listInTheme($theme, true);
            $cmsPages = [];
            foreach ($pages as $page) {
                if (!$page->hasComponent('ProductItem')) {
                    continue;
                }

                $properties = $page->getComponentProperties('ProductItem');
                if (!isset($properties['slug']) || !preg_match('/{{\s*:/', $properties['slug'])) {
                    continue;
                }

                $cmsPages[] = $page;
            }

            $result['cmsPages'] = $cmsPages;
        }

        return $result;
    }

    public static function resolveMenuItem($item, $url, $theme)
    {
        $result = null;

        if ($item->type == 'catalog-product') {
            if (!$item->reference || !$item->cmsPage) {
                return;
            }

            $product = self::find($item->reference);
            if (!$product) {
                return;
            }

            $pageUrl = self::getProductPageUrl($item->cmsPage, $product, $theme);
            if (!$pageUrl) {
                return;
            }

            $pageUrl = Url::to($pageUrl);

            $result = [];
            $result['url'] = $pageUrl;
            $result['isActive'] = $pageUrl == $url;
            $result['mtime'] = $product->updated_at;
        }
        elseif ($item->type == 'all-catalog-products') {
            $result = [
                'items' => []
            ];

            $products = self::active()
                      ->orderBy('name')
                      ->get();

            foreach ($products as $product) {
                $postItem = [
                    'title' => $product->name,
                    'url'   => self::getProductPageUrl($item->cmsPage, $product, $theme),
                    'mtime' => $product->updated_at
                ];

                $postItem['isActive'] = $postItem['url'] == $url;

                $result['items'][] = $postItem;
            }
        }
        elseif ($item->type == 'category-blog-posts') {
            if (!$item->reference || !$item->cmsPage) {
                return;
            }

            $category = Category::find($item->reference);
            if (!$category) {
                return;
            }

            $result = [
                'items' => []
            ];

            $query = self::isPublished()
                   ->orderBy('name');

            $categories = $category->getAllChildrenAndSelf()->lists('id');
            $query->whereHas('other_categories', function($q) use ($categories) {
                $q->whereIn('id', $categories);
            });

            $products = $query->get();

            foreach ($products as $product) {
                $postItem = [
                    'title' => $product->name,
                    'url'   => self::getProductPageUrl($item->cmsPage, $product, $theme),
                    'mtime' => $product->updated_at
                ];

                $postItem['isActive'] = $postItem['url'] == $url;

                $result['items'][] = $postItem;
            }
        }

        return $result;
    }

    protected static function getProductPageUrl($pageCode, $product, $theme)
    {
        $page = CmsPage::loadCached($theme, $pageCode);
        if (!$page) {
            return;
        }

        $properties = $page->getComponentProperties('ProductItem');
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
        $url = CmsPage::url($page->getBaseFileName(), [$paramName => $product->slug]);

        return $url;
    }
}
