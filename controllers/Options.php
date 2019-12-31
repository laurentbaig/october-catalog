<?php namespace Lbaig\Catalog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Lbaig\Catalog\Models\OptionItem;
use Request;

/**
 * Options Back-end Controller
 */
class Options extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Lbaig.Catalog', 'catalog', 'options');
        $this->addJs('/plugins/lbaig/catalog/assets/js/Sortable.min.js');
        $this->addJs('/plugins/lbaig/catalog/assets/js/initialize_sorting.js');
    }

    // to reorder the children OptionItem's
    public function onReorder() {
        $records = Request::input('rcd');
        $model = new OptionItem;
        $model->setSortableOrder($records, range(1, count($records)));
}
}
