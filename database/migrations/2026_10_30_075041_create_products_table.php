<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('store_id')
                ->nullable()
                ->constrained('stores')
                ->nullOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->foreignId('sub_category_id')
                ->nullable()
                ->constrained('sub_categories')
                ->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();

            $table->decimal('price', 15, 2)->nullable();
            $table->string('price_type', 30)->default('fixed');

            $table->string('condition', 30)->nullable();

            $table->string('status', 30)->default('draft');

            $table->unsignedBigInteger('views')->default(0);

            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->index(['category_id', 'status']);
            $table->index(['sub_category_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};