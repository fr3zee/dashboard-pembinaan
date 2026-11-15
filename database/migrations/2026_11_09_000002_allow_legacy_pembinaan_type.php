<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembinaan', function (Blueprint $table) {
            $table->string('jenis_pembinaan')->nullable()->default('Sosialisasi Srikandi')->change();
        });
    }

    public function down(): void
    {
        Schema::table('pembinaan', function (Blueprint $table) {
            $table->string('jenis_pembinaan')->nullable(false)->default('Sosialisasi Srikandi')->change();
        });
    }
};
