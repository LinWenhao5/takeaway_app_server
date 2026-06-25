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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->string('type');
            $table->decimal('value', 10, 2);
            $table->decimal('min_order_amount', 10, 2)->default(0.00);
            
            $table->dateTime('pickup_start_at')->nullable();
            $table->dateTime('pickup_end_at')->nullable();
            
            $table->integer('valid_days')->nullable();
            $table->dateTime('use_start_at')->nullable();
            $table->dateTime('use_end_at')->nullable();

            $table->integer('total_quantity')->nullable();
            $table->integer('received_quantity')->default(0);
            $table->integer('per_customer_limit')->default(1);
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
