<?php namespace Lbaig\Catalog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

use Lbaig\Catalog\Models\Category;

/**
 * Product Order Back-end Controller
 */
class ProductOrder extends Controller
{
    public $implement = [
    ];

    public $reorderConfig = 'config_reorder.yaml';
    
    public $pageTitle = 'Sort Products';
    
    public $category = null;
    
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Lbaig.Catalog', 'catalog', 'productorder');
    }

    public function index()
    {
        $categories = Category::has('products')->get();
        $this->vars['categories'] = $categories;
        $this->category = $this->vars['selectedCategoryId'] = $categories->first()->id;
        $products = $categories->first()->products;
        $this->vars['products'] = $products;
    }

    public function onSelectCategory()
    {
        $this->category = $category = Category::findOrFail(input('category'));
        $products = $category->products()->orderBy('sort_order', 'asc')->get();
        return $this->renderProducts($products);
    }

    public function renderProducts($products)
    {
        return [
            '#products-list' => $this->makePartial('product_list', [
                'products' => $products
            ])
        ];
    }

    public function onReorder()
    {
        if (!($ids = post('record_ids'))) {
            return;
        }
        
        $sortOrders = [];
        for ($ii = 0; $ii < count($ids); $ii++) {
            $sortOrders[$ii] = $ii;
        }
        $model = new \Lbaig\Catalog\Models\Product;
        $model->setSortableOrder($ids, $sortOrders);
    }
}
