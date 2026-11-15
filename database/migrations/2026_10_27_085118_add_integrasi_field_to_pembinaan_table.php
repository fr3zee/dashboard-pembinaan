<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembinaan', function (Blueprint $table) {
            $table->string('integrasi_aplikasi', 255)->nullable()->after('layanan');
            $table->string('kategori_instansi', 100)->nullable()->after('instansi_id');
        });
    }

    public function down(): void
    {
        Schema::table('pembinaan', function (Blueprint $table) {
            $table->dropColumn(['integrasi_aplikasi', 'kategori_instansi']);
        });
    }
};
