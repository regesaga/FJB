<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_areas', function (Blueprint $table) {
            $table->id();

            // Relasi ke listing jasa
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            // Wilayah layanan: pilih salah satu
            $table->string('village_code', 255)->nullable();
            $table->string('district_code', 255)->nullable();
            $table->string('regency_code', 255)->nullable();

            $table->timestamps();

            // Foreign key wilayah
            $table->foreign('village_code')
                ->references('code')
                ->on('villages')
                ->restrictOnDelete();

            $table->foreign('district_code')
                ->references('code')
                ->on('districts')
                ->restrictOnDelete();

            $table->foreign('regency_code')
                ->references('code')
                ->on('regencies')
                ->restrictOnDelete();

            // Mencegah wilayah yang sama dimasukkan dua kali
            $table->unique(
                ['product_id', 'village_code'],
                'service_areas_product_village_unique'
            );

            $table->unique(
                ['product_id', 'district_code'],
                'service_areas_product_district_unique'
            );

            $table->unique(
                ['product_id', 'regency_code'],
                'service_areas_product_regency_unique'
            );

            $table->index('product_id');
        });

        // Setiap baris wajib memilih tepat satu jenis wilayah.
        DB::statement("
            ALTER TABLE service_areas
            ADD CONSTRAINT service_areas_exactly_one_area_check
            CHECK (
                (village_code IS NOT NULL) +
                (district_code IS NOT NULL) +
                (regency_code IS NOT NULL) = 1
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('service_areas');
    }
};