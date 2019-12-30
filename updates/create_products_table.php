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
            $table->boolean('taxable')->default(true);
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('vendor_code')->nullable();
            $table->decimal('price', 13, 2)->default(0);
            $table->integer('shipping_weight')->default(0);
            $table->text('description')->nullable();

            $table->foreign('category_id')
                  ->references('id')
                  ->on('lbaig_catalog_categories');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lbaig_catalog_products');
    }
}
