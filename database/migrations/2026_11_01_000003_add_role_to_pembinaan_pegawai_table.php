<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembinaan_pegawai', function (Blueprint $table) {
            $table->string('peran')->default('Tim')->after('pegawai_id');
        });
    }

    public function down(): void
    {
        Schema::table('pembinaan_pegawai', function (Blueprint $table) {
            $table->dropColumn('peran');
        });
    }
};
