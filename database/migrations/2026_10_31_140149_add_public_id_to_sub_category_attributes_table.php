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
        Schema::table('sub_category_attributes', function (Blueprint $table) {
            $table->uuid('public_id')->nullable()->unique();
        });

        DB::table('sub_category_attributes')
            ->whereNull('public_id')
            ->orderBy('id')
            ->chunkById(100, function ($mappings) {
                foreach ($mappings as $mapping) {
                    DB::table('sub_category_attributes')
                        ->where('id', $mapping->id)
                        ->update([
                            'public_id' => (string) Str::uuid(),
                        ]);
                }
            });

        Schema::table('sub_category_attributes', function (Blueprint $table) {
            $table->uuid('public_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('sub_category_attributes', function (Blueprint $table) {
            $table->dropUnique(['public_id']);
            $table->dropColumn('public_id');
        });
    }
};