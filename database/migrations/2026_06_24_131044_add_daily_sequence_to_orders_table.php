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
        Schema::table('orders', function (Blueprint $table) {
            $table->date('order_date')->nullable();
            $table->unsignedInteger('daily_sequence')->nullable();

            $table->unique(['order_date', 'daily_sequence']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique('orders_orderdate_seq_unique');
            $table->dropColumn(['order_date', 'daily_sequence']);
        });
    }
};
