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
        return []; // Remove this line to activate

        return [
            'Lbaig\Catalog\Components\MyComponent' => 'myComponent',
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
                'icon'        => 'icon-leaf',
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
                ]
            ],
        ];
    }
}
