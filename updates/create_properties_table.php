<?php namespace Lbaig\Catalog\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreatePropertiesTable extends Migration
{
    public function up()
    {
        Schema::create('lbaig_catalog_properties', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('display_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lbaig_catalog_properties');
    }
}
