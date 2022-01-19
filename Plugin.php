<?php namespace Lbaig\Catalog;

use Backend;
use System\Classes\PluginBase;
use Event;

/**
 * Catalog Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Catalog',
            'description' => 'No description provided yet...',
            'author'      => 'Lbaig',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        // Register menu items
        Event::listen('pages.menuitem.listTypes', function () {
            return [
                'catalog-category' => 'Catalog Category',
                'all-catalog-categories' => 'All Catalog Categories',
                'catalog-product' => 'Catalog Product',
                'all-catalog-products' => 'All Catalog Products',
                'category-catalog-products' => 'Catagorized Catalog Products'
            ];
        });

        Event::listen('pages.menuitem.getTypeInfo', function ($type) {
            if ($type == 'catalog-category' || $type == 'all-catalog-categories') {
                return \Lbaig\Catalog\Models\Category::getMenuTypeInfo($type);
            }
            elseif ($type == 'catalog-product' || $type == 'all-catalog-products' ||
                    $type == 'category-catalog-products') {
                return \LBaig\Catalog\Models\Product::getMenuTypeInfo($type);
            }
        });

        Event::listen('pages.menuitem.resolveItem', function ($type, $item, $url, $theme) {
            if ($type == 'catalog-category' || $type == 'all-catalog-categories') {
                return \Lbaig\Catalog\Models\Category::resolveMenuItem($item, $url, $theme);
            }
            elseif ($type == 'catalog-product' || $type == 'all-catalog-products' ||
                    $type == 'category-catalog-products') {
                return \LBaig\Catalog\Models\Product::resolveMenuItem($item, $url, $theme);
            }
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Lbaig\Catalog\Components\Breadcrumbs' => 'Breadcrumbs',
            'Lbaig\Catalog\Components\CategoryItem' => 'CategoryItem',
            'Lbaig\Catalog\Components\CategoryList' => 'CategoryList',
            'Lbaig\Catalog\Components\ProductItem' => 'ProductItem',
            'Lbaig\Catalog\Components\ProductList' => 'ProductList'
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'lbaig.catalog.some_permission' => [
                'tab' => 'Catalog',
                'label' => 'Some permission'
            ],
            'lbaig.catalog.access_settings' => [
                'tab' => 'Catalog',
                'label' => 'Access backend settings'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'catalog' => [
                'label'       => 'Catalog',
                'url'         => Backend::url('lbaig/catalog/products'),
                'icon'        => 'icon-th-large',
                'permissions' => ['lbaig.catalog.*'],
                'order'       => 500,
                'sideMenu' => [
                    'categories' => [
                        'label' => 'Categories',
                        'icon' => 'icon-sitemap',
                        'url' => Backend::url('lbaig/catalog/categories'),
                        'permissions' => ['lbaig.catalog.some_permission']
                    ],
                    'products' => [
                        'label' => 'Products',
                        'icon' => 'icon-book',
                        'url' => Backend::url('lbaig/catalog/products'),
                        'permissions' => ['lbaig.catalog.some_permission']
                    ],
                    'properties' => [
                        'label' => 'Properties',
                        'icon' => 'icon-list-ul',
                        'url' => Backend::url('lbaig/catalog/properties'),
                        'permissions' => ['lbaig.catalog.some_permission']
                    ]
                ]
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'basket' => [
                'label'       => 'Catalog Settings',
                'description' => 'Manage catalog settings.',
                'category'    => 'Catalog',
                'icon'        => 'icon-cog',
                'class'       => 'Lbaig\Catalog\Models\Settings',
                'order'       => 500,
                'keywords'    => 'security location',
                'permissions' => ['lbaig.catalog.access_settings']
            ]
        ];
    }
}
