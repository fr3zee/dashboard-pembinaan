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
        Schema::create('instansi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_instansi_id')->constrained('kategori_instansi')->restrictOnDelete();
            $table->string('nama');
            $table->boolean('status_aktif')->default(true)->index();
            $table->timestamps();

            $table->unique(['kategori_instansi_id', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instansi');
    }
};
