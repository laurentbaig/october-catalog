<?php namespace Lbaig\Catalog\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateProductPropertyTable extends Migration
{
    public function up()
    {
        Schema::create('lbaig_catalog_product_property', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('property_id')->unsigned()->nullable();
 
        });
    }

    public function down()
    {
        Schema::dropIfExists('lbaig_catalog_product_property');
    }
}
