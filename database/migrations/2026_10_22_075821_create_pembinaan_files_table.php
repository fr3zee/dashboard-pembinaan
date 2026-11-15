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
        Schema::create('pembinaan_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembinaan_id')->constrained('pembinaan')->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users')->restrictOnDelete();
            $table->enum('jenis', ['laporan', 'materi']);
            $table->string('nama_asli');
            $table->string('path');
            $table->string('mime_type');
            $table->unsignedBigInteger('ukuran');
            $table->enum('visibilitas', ['Publikasi', 'Private'])->default('Private');
            $table->timestamps();

            $table->index(['pembinaan_id', 'jenis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembinaan_files');
    }
};
