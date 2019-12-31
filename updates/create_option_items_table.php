<?php namespace Lbaig\Catalog\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateOptionItemsTable extends Migration
{
    public function up()
    {
        Schema::create('lbaig_catalog_option_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('option_id')->unsigned()->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('name');
            $table->text('description')->nullable();
            $table->double('price', 13, 2)->default(0);
            $table->integer('shipping_weight')->default(0);
            $table->timestamps();

            $table->foreign('option_id')
                  ->references('id')
                  ->on('lbaig_catalog_options')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lbaig_catalog_option_items');
    }
}
