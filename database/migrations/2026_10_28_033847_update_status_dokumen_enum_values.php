<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            Schema::table('pembinaan', function (Blueprint $table): void {
                $table->string('status_dokumen', 50)->default('Belum Menggunakan')->change();
            });

            return;
        }

        DB::statement("ALTER TABLE pembinaan MODIFY COLUMN status_dokumen VARCHAR(50) NOT NULL DEFAULT 'Belum Menggunakan'");
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            Schema::table('pembinaan', function (Blueprint $table): void {
                $table->string('status_dokumen', 50)->default('Tahap Persiapan')->change();
            });

            return;
        }

        DB::statement("ALTER TABLE pembinaan MODIFY COLUMN status_dokumen ENUM('Tahap Persiapan','Selesai') NOT NULL DEFAULT 'Tahap Persiapan'");
    }
};
