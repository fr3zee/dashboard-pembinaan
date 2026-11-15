<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembinaan', function (Blueprint $table) {
            $table->string('nama_kegiatan')->nullable()->after('created_by');
        });
    }

    public function down(): void
    {
        Schema::table('pembinaan', function (Blueprint $table) {
            $table->dropColumn('nama_kegiatan');
        });
    }
};
