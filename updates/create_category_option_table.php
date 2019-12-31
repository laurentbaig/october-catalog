<?php namespace Lbaig\Catalog\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCategoryOptionTable extends Migration
{
    public function up()
    {
        Schema::create('lbaig_catalog_category_option', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('option_id')->unsigned()->nullable();
 
        });
    }

    public function down()
    {
        Schema::dropIfExists('lbaig_catalog_category_option');
    }
}
