<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_identity_verifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            // Dokumen identitas
            $table->string('identity_type')->default('ktp');
            $table->string('identity_number_encrypted')->nullable();
            $table->string('identity_document_path')->nullable();

            // Verifikasi wajah
            $table->string('selfie_path')->nullable();
            $table->string('face_match_status')->default('pending');
            $table->string('liveness_status')->default('pending');

            // Status verifikasi
            $table->string('status')->default('pending');
            $table->text('rejection_reason')->nullable();

            // Pemeriksaan admin
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_identity_verifications');
    }
};