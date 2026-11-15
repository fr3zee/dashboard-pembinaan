<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::dropIfExists('tamu_konsultasi'); Schema::dropIfExists('tamu_kunjungan'); }
    public function down(): void {}
};
