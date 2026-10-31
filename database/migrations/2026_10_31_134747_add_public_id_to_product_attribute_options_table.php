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
        Schema::table('product_attribute_options', function (Blueprint $table) {
            $table->uuid('public_id')->nullable()->unique();
        });

        DB::table('product_attribute_options')
            ->whereNull('public_id')
            ->orderBy('id')
            ->chunkById(100, function ($options) {
                foreach ($options as $option) {
                    DB::table('product_attribute_options')
                        ->where('id', $option->id)
                        ->update([
                            'public_id' => (string) Str::uuid(),
                        ]);
                }
            });

        Schema::table('product_attribute_options', function (Blueprint $table) {
            $table->uuid('public_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('product_attribute_options', function (Blueprint $table) {
            $table->dropUnique(['public_id']);
            $table->dropColumn('public_id');
        });
    }
};