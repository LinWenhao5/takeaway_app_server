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
        Schema::create('coupon_customer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('coupon_id')->constrained('coupons')->onDelete('cascade');
            
            $table->dateTime('received_at')->useCurrent();
            $table->dateTime('expires_at');
            
            $table->boolean('is_used')->default(false);
            $table->dateTime('used_at')->nullable();
            $table->foreignId('order_id')->nullable();

            $table->index(['customer_id', 'is_used']); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_customer');
    }
};
