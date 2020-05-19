<?php namespace Lbaig\Catalog\Components;

use Cms\Classes\ComponentBase;
use Lbaig\Catalog\Models\Category;


class CategoryList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'CategoryList Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function getRootCategories()
    {
        $categories = Category::active()
                    ->getAllRoot();
        /*
                    ->whereNull('parent_id')
                    ->orderBy('nest_right', 'asc')
                    ->get();
*/
        return $categories;
    }
}
