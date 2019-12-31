<?php namespace Lbaig\Catalog\Models;

use Model;

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
        'options' => ['Lbaig\Catalog\Models\Option', 'table' => 'lbaig_catalog_category_option']
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
}
