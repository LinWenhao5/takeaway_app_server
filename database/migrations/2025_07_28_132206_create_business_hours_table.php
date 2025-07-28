<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('business_hours', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('weekday')->unique()->comment('0=Sunday, 1=Monday, ..., 6=Saturday');
            $table->time('open_time')->comment('Opening time');
            $table->time('close_time')->comment('Closing time');
            $table->boolean('is_closed')->default(false)->comment('Is closed on this day');
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_hours');
    }
};