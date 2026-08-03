<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        
            $table->string('full_name')->nullable();
            $table->string('phone',20)->nullable();
        
            $table->date('birth_date')->nullable();
        
            $table->enum('gender',['L','P'])->nullable();
        
            $table->text('bio')->nullable();
        
            $table->string('avatar')->nullable();
        
            $table->string('cover')->nullable();
        
            $table->string('identity_number')->nullable();
        
            $table->boolean('verified')->default(false);
        
            $table->decimal('latitude',10,7)->nullable();
            $table->decimal('longitude',10,7)->nullable();
        
            $table->timestamp('last_seen')->nullable();
        
            $table->boolean('online')->default(false);
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
