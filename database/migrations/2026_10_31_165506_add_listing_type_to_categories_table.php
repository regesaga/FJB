<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('listing_type', 20)
                ->default('product')
                ->after('slug')
                ->index();
        });

        DB::table('categories')
            ->where('slug', 'jasa')
            ->update(['listing_type' => 'service']);

        DB::table('categories')
            ->where('slug', 'lowongan-kerja')
            ->update(['listing_type' => 'job']);

        DB::table('categories')
            ->where('slug', 'lainnya')
            ->update(['listing_type' => 'other']);
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['listing_type']);
            $table->dropColumn('listing_type');
        });
    }
};