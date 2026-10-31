<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_attributes', function (Blueprint $table) {
            $table->boolean('is_recommended')
                ->default(false)
                ->after('searchable');

            $table->string('recommendation_group')
                ->nullable()
                ->after('is_recommended')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('product_attributes', function (Blueprint $table) {
            $table->dropIndex(['recommendation_group']);

            $table->dropColumn([
                'is_recommended',
                'recommendation_group',
            ]);
        });
    }
};