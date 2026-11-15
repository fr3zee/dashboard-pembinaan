<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE pembinaan MODIFY COLUMN status_dokumen VARCHAR(50) NOT NULL DEFAULT 'Belum Menggunakan'");
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE pembinaan MODIFY COLUMN status_dokumen ENUM('Tahap Persiapan','Selesai') NOT NULL DEFAULT 'Tahap Persiapan'");
    }
};
