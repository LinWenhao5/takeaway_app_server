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
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn(['vat_amount', 'vat_rate', 'vat_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->decimal('vat_amount', 10, 2)->nullable();
            $table->decimal('vat_rate', 5, 2)->nullable();
            $table->string('vat_name')->nullable();
        });
    }
};
