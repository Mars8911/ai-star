<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ai_stars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('unique_id')->unique();
            $table->string('video_path')->nullable();
            $table->string('audio_path')->nullable();
            $table->decimal('public_price', 10, 2)->default(50.00);
            $table->decimal('business_price', 10, 2)->default(1000.00);
            $table->text('introduction');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_stars');
    }
}; 