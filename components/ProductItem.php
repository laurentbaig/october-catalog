<?php namespace Lbaig\Catalog\Components;

use Cms\Classes\ComponentBase;
use Lbaig\Catalog\Models\Product;


class ProductItem extends ComponentBase
{
    public $product;
    
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

    public function onRun()
    {
        $this->product = $this->get();
    
    }

    public function get()
    {
        $slug = $this->property('slug');
        return Product::active()->where('slug', $slug)->first();
    }

    public function nextItem()
    {
        $item = Product::active()
              ->where('category_id', $this->product->category_id)
              ->where('sort_order', '>', $this->product->sort_order)
              ->first();
        return $item;
    }

    public function prevItem()
    {
        $item = Product::active()
              ->where('category_id', $this->product->category_id)
              ->where('sort_order', '<', $this->product->sort_order)
              ->orderBy('sort_order', 'desc')
              ->first();
        return $item;
    }
}
