<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('vat_rate_id')->nullable()->after('product_category_id');
            $table->foreign('vat_rate_id')->references('id')->on('vat_rates');
        });
    }

    /*
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_vat_rate_id_foreign');
            $table->dropColumn('vat_rate_id');
        });
    }
};
