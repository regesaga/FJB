<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_locations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->unique()
                ->constrained('products')
                ->cascadeOnDelete();

            $table->string('province_code');
            $table->string('regency_code');
            $table->string('district_code');
            $table->string('village_code');

            $table->text('address_detail')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();

            $table->foreign('province_code')
                ->references('code')
                ->on('provinces')
                ->restrictOnDelete();

            $table->foreign('regency_code')
                ->references('code')
                ->on('regencies')
                ->restrictOnDelete();

            $table->foreign('district_code')
                ->references('code')
                ->on('districts')
                ->restrictOnDelete();

            $table->foreign('village_code')
                ->references('code')
                ->on('villages')
                ->restrictOnDelete();

            $table->index(['regency_code', 'district_code']);
            $table->index('village_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_locations');
    }
};