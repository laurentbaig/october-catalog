<?php namespace Lbaig\Catalog\Models;

use Model;

/**
 * Property Model
 */
class Property extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'lbaig_catalog_properties';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'display_name'
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

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'options' => 'Lbaig\Catalog\Models\PropertyOption'
    ];
    public $belongsTo = [];
    public $belongsToMany = [
        'categories' => ['Lbaig\Catalog\Models\Category', 'table' => 'lbaig_catalog_category_property'],
        'products' => ['Lbaig\Catalog\Models\Product', 'table' => 'lbaig_catalog_product_property']
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];


    public function getDisplayNameAttribute($value)
    {
        if (strlen($value) === 0) {
            return $this->name;
        }
        return $value;
    }
}
