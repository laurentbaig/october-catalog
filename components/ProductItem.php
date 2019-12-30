<?php namespace Lbaig\Catalog\Components;

use Cms\Classes\ComponentBase;
use Lbaig\Catalog\Models\Product;


class ProductItem extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'ProductItem Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title' => 'Slug',
                'description' => 'Slug of product',
                'default' => '*',
                'type' => 'string',
            ]
        ];
    }

    public function get()
    {
        $slug = $this->property('slug');
        return Product::where('slug', $slug)->first();
    }
}
