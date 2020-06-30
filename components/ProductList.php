<?php namespace Lbaig\Catalog\Components;

use Cms\Classes\ComponentBase;
use Lbaig\Catalog\Models\Category;
use Lbaig\Catalog\Models\Product;

class ProductList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'ProductList Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function get(Category $category)
    {
        // select all subcategories as well as category
        $subcategory_ids = Category::active()
                         ->where('nest_left', '>=', $category->nest_left)
                         ->where('nest_right', '<=', $category->nest_right)
                         ->pluck('id');

        // select all products that belong to this category or lower
        $products = Product::active()
                  ->whereIn('category_id', $subcategory_ids)
                  ->orderBy('category_id', 'asc')
                  ->orderBy('sort_order', 'asc')
                  ->orderBy('lbaig_catalog_products.created_at', 'desc')
                  ->get();

        // now collect all products that are 'other_products'
        $other_products = $category->other_products;
        \Log::info($category);
        \Log::info($other_products);
        $allproducts = $products->merge($other_products);

        return $allproducts;
    }
}
