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
        Schema::create('stores', function (Blueprint $table){

            $table->id();
        
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        
            $table->string('name');
        
            $table->string('slug')->unique();
        
            $table->string('logo')->nullable();
        
            $table->string('banner')->nullable();
        
            $table->text('description')->nullable();
        
            $table->string('phone')->nullable();
        
            $table->string('whatsapp')->nullable();
        
            $table->string('address')->nullable();
        
            $table->decimal('latitude',10,7)->nullable();
        
            $table->decimal('longitude',10,7)->nullable();
        
            $table->decimal('rating',3,2)->default(0);
        
            $table->integer('total_reviews')->default(0);
        
            $table->integer('followers')->default(0);
        
            $table->boolean('verified')->default(false);
        
            $table->boolean('is_open')->default(true);
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
