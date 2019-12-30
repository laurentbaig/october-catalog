<?php namespace Lbaig\Catalog\Components;

use Cms\Classes\ComponentBase;
use Lbaig\Catalog\Models\Category;

class CategoryItem extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'CategoryItem Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title' => 'Category',
                'description' => 'Category of Products',
                'default' => '*',
                'type' => 'dropdown',
            ]
        ];
    }

    
    public function get()
    {
        $slug = $this->property('slug');
        
        return Category::where('slug', $slug)->first();
    }
}
