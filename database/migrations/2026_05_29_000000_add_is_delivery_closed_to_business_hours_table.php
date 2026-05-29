<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_hours', function (Blueprint $table) {
            $table->boolean('is_delivery_closed')->default(false)->after('is_closed')->comment('Is delivery closed on this day');
        });
    }

    public function down(): void
    {
        Schema::table('business_hours', function (Blueprint $table) {
            $table->dropColumn('is_delivery_closed');
        });
    }
};