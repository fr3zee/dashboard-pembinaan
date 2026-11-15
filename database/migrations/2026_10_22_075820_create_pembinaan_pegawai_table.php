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
        Schema::create('pembinaan_pegawai', function (Blueprint $table) {
            $table->foreignId('pembinaan_id')->constrained('pembinaan')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->constrained('pegawai')->restrictOnDelete();
            $table->primary(['pembinaan_id', 'pegawai_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembinaan_pegawai');
    }
};
