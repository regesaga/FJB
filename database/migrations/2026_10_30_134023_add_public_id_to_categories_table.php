<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan kolom UUID sementara nullable.
        Schema::table('categories', function (Blueprint $table) {
            $table->uuid('public_id')->nullable()->unique();
        });

        // Isi UUID untuk seluruh kategori yang sudah ada.
        DB::table('categories')
            ->whereNull('public_id')
            ->orderBy('id')
            ->chunkById(100, function ($categories) {
                foreach ($categories as $category) {
                    DB::table('categories')
                        ->where('id', $category->id)
                        ->update([
                            'public_id' => (string) Str::uuid(),
                        ]);
                }
            });

        // Jadikan UUID wajib diisi.
        Schema::table('categories', function (Blueprint $table) {
            $table->uuid('public_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['public_id']);
            $table->dropColumn('public_id');
        });
    }
};