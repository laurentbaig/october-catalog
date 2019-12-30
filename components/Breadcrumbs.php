<?php namespace Lbaig\Catalog\Components;

use Cms\Classes\ComponentBase;
use Lbaig\Catalog\Models\Category;

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

    public function get(Category $category = null)
    {
        if ($category === null) {
            return [];
        }

        $result = [$category];
        while ($category->parent !== null) {
            $result[] = $category->parent;
            $category = $category->parent;
        }
        \Log::info($result);

        return array_reverse($result);
    }
}
