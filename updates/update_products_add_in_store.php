<?php namespace Lbaig\Catalog\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateProductsAddInStore extends Migration
{
    public function up()
    {
        Schema::table('lbaig_catalog_products', function (Blueprint $table) {
            $table->boolean('in_store')->default(true);
        });
    }

    public function down()
    {
        Schema::table('lbaig_catalog_products', function (Blueprint $table) {
            $table->dropColumn('in_store');
        });
    }
}
