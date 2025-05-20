<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('nationality');
            $table->string('national_id');
            $table->string('phone');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('birth_date');
            $table->string('occupation');
            $table->string('language')->default('en');
            $table->enum('type', ['personal', 'business'])->default('personal');
            $table->boolean('is_verified')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}; 