<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('buyer_id')->constrained('users');
            $table->foreignId('seller_id')->constrained('users');
            $table->foreignId('ai_star_id')->constrained('ai_stars');
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['public', 'custom'])->default('public');
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->text('custom_content')->nullable();
            $table->string('language')->default('en');
            $table->string('scene_type')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}; 