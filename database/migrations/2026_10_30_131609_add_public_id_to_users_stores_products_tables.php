<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Menambahkan UUID sebagai identifier publik.
     */
    public function up(): void
    {
        // 1. Tambahkan kolom public_id sementara nullable.
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('public_id')->nullable()->unique();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->uuid('public_id')->nullable()->unique();
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->uuid('public_id')->nullable()->unique();
        });

        // 2. Isi UUID untuk data lama.
        foreach (['users', 'products', 'stores'] as $tableName) {
            DB::table($tableName)
                ->whereNull('public_id')
                ->orderBy('id')
                ->chunkById(100, function ($records) use ($tableName) {
                    foreach ($records as $record) {
                        DB::table($tableName)
                            ->where('id', $record->id)
                            ->update([
                                'public_id' => (string) Str::uuid(),
                            ]);
                    }
                });
        }

        // 3. Jadikan public_id wajib diisi.
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('public_id')->nullable(false)->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->uuid('public_id')->nullable(false)->change();
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->uuid('public_id')->nullable(false)->change();
        });
    }

    /**
     * Membatalkan penambahan public_id.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropUnique(['public_id']);
            $table->dropColumn('public_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['public_id']);
            $table->dropColumn('public_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['public_id']);
            $table->dropColumn('public_id');
        });
    }
};