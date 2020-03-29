<?php namespace Lbaig\Catalog\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreatePropertyOptionsTable extends Migration
{
    public function up()
    {
        Schema::table('lbaig_catalog_products', function (Blueprint $table) {
            $table->integer('sort_order')->default(0);
        });
    }

    public function down()
    {
        Schema::table('lbaig_catalog_products', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
}
