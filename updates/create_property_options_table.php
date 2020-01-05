<?php namespace Lbaig\Catalog\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreatePropertyOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('lbaig_catalog_property_options', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('property_id')->unsigned()->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('name');
            $table->text('description')->nullable();
            $table->double('price', 13, 2)->default(0);
            $table->integer('shipping_weight')->default(0);
            $table->timestamps();

            $table->foreign('property_id')
                  ->references('id')
                  ->on('lbaig_catalog_properties')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lbaig_catalog_property_options');
    }
}
