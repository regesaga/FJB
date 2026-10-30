<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_details', function (Blueprint $table) {
            $table->id();

            // Relasi ke listing utama
            $table->foreignId('product_id')
                ->unique()
                ->constrained('products')
                ->cascadeOnDelete();

            // Informasi tarif jasa
            $table->string('pricing_type', 30)->default('negotiable');
            $table->decimal('price_from', 15, 2)->nullable();
            $table->decimal('price_to', 15, 2)->nullable();
            $table->string('price_unit', 50)->nullable();

            // Pengalaman penyedia jasa
            $table->unsignedSmallInteger('experience_years')->nullable();

            // Jadwal pelayanan
            $table->json('schedule')->nullable();

            // Keterangan tambahan khusus jasa
            $table->text('service_description')->nullable();

            $table->timestamps();

            $table->index('pricing_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_details');
    }
};