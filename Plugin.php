<?php namespace Lbaig\Catalog;

use Backend;
use System\Classes\PluginBase;

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
        return []; // Remove this line to activate

        return [
            'lbaig.catalog.some_permission' => [
                'tab' => 'Catalog',
                'label' => 'Some permission'
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
                        'permissions' => ['lbaig.lshop.some_permission']
                    ],
                    'products' => [
                        'label' => 'Products',
                        'icon' => 'icon-book',
                        'url' => Backend::url('lbaig/catalog/products'),
                        'permissions' => ['lbaig.lshop.some_permission']
                    ],
                    'properties' => [
                        'label' => 'Properties',
                        'icon' => 'icon-list-ul',
                        'url' => Backend::url('lbaig/catalog/properties'),
                        'permissions' => ['lbaig.lshop.some_permission']
                    ]
                ]
            ],
        ];
    }
}
