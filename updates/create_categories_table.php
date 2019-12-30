<?php namespace Lbaig\Catalog\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('lbaig_catalog_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('active')->default(false);
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // nested tree
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('nest_left')->nullable();
            $table->unsignedInteger('nest_right')->nullable();
            $table->unsignedInteger('nest_depth')->nullable();
 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lbaig_catalog_categories');
    }
}
