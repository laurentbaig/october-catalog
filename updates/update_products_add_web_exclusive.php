<?php namespace Lbaig\Catalog\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateProductsAddWebExclusive extends Migration
{
    public function up()
    {
        Schema::table('lbaig_catalog_products', function (Blueprint $table) {
            $table->boolean('web_exclusive')->default(false);
        });
    }

    public function down()
    {
        Schema::table('lbaig_catalog_products', function (Blueprint $table) {
            $table->dropColumn('web_exclusive');
        });
    }
}
