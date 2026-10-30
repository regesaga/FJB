<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_category_attributes', function (Blueprint $table) {
            $table->id();

            // Relasi ke subkategori
            $table->foreignId('sub_category_id')
                ->constrained('sub_categories')
                ->cascadeOnDelete();

            // Relasi ke pertanyaan/atribut
            $table->foreignId('product_attribute_id')
                ->constrained('product_attributes')
                ->cascadeOnDelete();

            // Pengaturan pertanyaan
            $table->boolean('required')->default(false);
            $table->boolean('filterable')->default(false);
            $table->boolean('searchable')->default(true);
            $table->boolean('visible')->default(true);

            // Urutan pertanyaan dalam formulir
            $table->unsignedInteger('sort')->default(0);

            $table->timestamps();

            // Satu pertanyaan tidak boleh terhubung dua kali
            // ke subkategori yang sama
            $table->unique(
                ['sub_category_id', 'product_attribute_id'],
                'sub_category_attribute_unique'
            );

            $table->index(
                ['sub_category_id', 'sort'],
                'sub_category_attributes_sort_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_category_attributes');
    }
};