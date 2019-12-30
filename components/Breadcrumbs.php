<?php namespace Lbaig\Catalog\Components;

use Cms\Classes\ComponentBase;
use Lbaig\Catalog\Models\Category;
use Lbaig\Catalog\Models\Product;


class Breadcrumbs extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Breadcrumbs Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function getFromCategory(Category $category = null)
    {
        if ($category === null) {
            return [];
        }

        $result = [$category];
        while ($category->parent !== null) {
            $result[] = $category->parent;
            $category = $category->parent;
        }

        return array_reverse($result);
    }

    public function getFromProduct(Product $product = null)
    {
        if ($product === null) {
            return [];
        }

        $category = $product->category;
        $result = [$product, $category ];
        while ($category->parent !== null) {
            $result[] = $category->parent;
            $category = $category->parent;
        }
        
        return array_reverse($result);
    }
}
