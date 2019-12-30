<?php namespace Lbaig\Catalog\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('lbaig_catalog_products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('category_id')->unsigned()->nullable();
            $table->boolean('active')->default(false);
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->foreign('category_id')
                  ->references('id')
                  ->on('lbaig_catalog_categories');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('lbaig_catalog_products', function (Blueprint $table) {
            //$table->dropForeign('lbaig_catalog_products_category_id_foreign');
        });
        Schema::dropIfExists('lbaig_catalog_products');
    }
}
