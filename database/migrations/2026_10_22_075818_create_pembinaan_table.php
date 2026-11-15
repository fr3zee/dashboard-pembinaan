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
        Schema::create('pembinaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi')->restrictOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->string('satker')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('metode_pelaksanaan');
            $table->string('lokasi')->nullable();
            $table->string('provinsi')->nullable()->index();
            $table->string('kota')->nullable()->index();
            $table->string('layanan');
            $table->text('catatan_hasil')->nullable();
            $table->enum('status_dokumen', ['Tahap Persiapan', 'Selesai'])->default('Tahap Persiapan');
            $table->timestamps();

            $table->index(['tanggal_mulai', 'layanan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembinaan');
    }
};
