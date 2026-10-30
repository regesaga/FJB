<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Jenis iklan: barang atau jasa
            $table->string('listing_type', 20)
                ->default('product');

            // Status ketersediaan barang atau jasa
            $table->string('availability_status', 30)
                ->default('available');

            // Jumlah stok untuk iklan barang
            $table->unsignedInteger('stock_quantity')
                ->nullable()
                ->default(1);

            // Waktu terakhir ketersediaan dikonfirmasi
            $table->timestamp('availability_confirmed_at')
                ->nullable();

            // Batas waktu konfirmasi ketersediaan berikutnya
            $table->timestamp('availability_expires_at')
                ->nullable();

            // Index untuk pencarian
            $table->index(
                ['listing_type', 'status'],
                'products_listing_type_status_index'
            );

            $table->index(
                ['availability_status', 'availability_expires_at'],
                'products_availability_expiry_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_listing_type_status_index');

            $table->dropIndex('products_availability_expiry_index');

            $table->dropColumn([
                'listing_type',
                'availability_status',
                'stock_quantity',
                'availability_confirmed_at',
                'availability_expires_at',
            ]);
        });
    }
};