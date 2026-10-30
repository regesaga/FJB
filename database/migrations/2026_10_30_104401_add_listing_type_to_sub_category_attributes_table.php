<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_category_attributes', function (Blueprint $table) {
            // Hapus unique constraint lama
            $table->dropUnique('sub_category_attribute_unique');

            // Tambahkan jenis iklan
            $table->string('listing_type', 20)
                ->default('both')
                ->after('product_attribute_id');
        });

        // Ambil ID kategori Jasa
        $jasaCategoryId = DB::table('categories')
            ->where('slug', 'jasa')
            ->value('id');

        if ($jasaCategoryId) {
            // Pertanyaan lama di kategori Jasa
            // ditandai sebagai pertanyaan jasa.
            $jasaSubcategories = DB::table('sub_categories')
                ->where('category_id', $jasaCategoryId)
                ->select('id');

            DB::table('sub_category_attributes')
                ->whereIn('sub_category_id', $jasaSubcategories)
                ->update(['listing_type' => 'service']);

            // Pertanyaan kategori selain Jasa
            // ditandai sebagai pertanyaan barang.
            DB::table('sub_category_attributes')
                ->whereNotIn('sub_category_id', $jasaSubcategories)
                ->update(['listing_type' => 'product']);
        }

        // Unique constraint baru
        Schema::table('sub_category_attributes', function (Blueprint $table) {
            $table->unique(
                [
                    'sub_category_id',
                    'product_attribute_id',
                    'listing_type',
                ],
                'sub_category_attribute_type_unique'
            );
        });
    }

    public function down(): void
    {
        // Perhatian: rollback hanya aman jika belum ada
        // pasangan pertanyaan yang sama untuk jenis iklan berbeda.

        $duplicates = DB::table('sub_category_attributes')
            ->select('sub_category_id', 'product_attribute_id')
            ->groupBy('sub_category_id', 'product_attribute_id')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($duplicates) {
            throw new RuntimeException(
                'Rollback dibatalkan: terdapat pertanyaan ganda antarjenis iklan.'
            );
        }

        Schema::table('sub_category_attributes', function (Blueprint $table) {
            $table->dropUnique('sub_category_attribute_type_unique');
            $table->dropColumn('listing_type');

            $table->unique(
                ['sub_category_id', 'product_attribute_id'],
                'sub_category_attribute_unique'
            );
        });
    }
};