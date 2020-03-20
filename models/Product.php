<?php namespace Lbaig\Catalog\Models;

use Model;

/**
 * Product Model
 */
class Product extends Model
{
    use \October\Rain\Database\Traits\Sluggable;
    use \October\Rain\Database\Traits\Validation;

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
        $query->where('active', true);
    }

    public function afterCreate()
    {
        $categoryPropertyIds = $this->category->properties->pluck('id');
        $this->properties()->sync($categoryPropertyIds);
    }
}
