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
        Schema::create('category_attributes', function (Blueprint $table){

            $table->id();
        
            $table->foreignId('category_id')->constrained();
        
            $table->foreignId('product_attribute_id')->constrained();
        
            $table->boolean('required')->default(false);
        
            $table->boolean('filterable')->default(true);
        
            $table->boolean('searchable')->default(true);
        
            $table->boolean('visible')->default(true);
        
            $table->integer('sort')->default(0);
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_attributes');
    }
};
