<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('company_email');
            $table->string('contact_person');
            $table->string('contact_phone');
            $table->string('company_id');
            $table->string('business_address');
            $table->date('establishment_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_profiles');
    }
}; 