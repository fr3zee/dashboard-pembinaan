<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::table('users', function(Blueprint $t){$t->string('nomor_telepon')->nullable()->after('email');$t->string('nip')->nullable()->after('nomor_telepon');$t->string('unit_kerja')->nullable()->after('nip');$t->string('instansi')->nullable()->after('unit_kerja');$t->boolean('status_aktif')->default(true)->after('role');}); } public function down(): void { Schema::table('users', function(Blueprint $t){$t->dropColumn(['nomor_telepon','nip','unit_kerja','instansi','status_aktif']);}); } };
