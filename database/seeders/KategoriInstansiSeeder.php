<?php

namespace Database\Seeders;

use App\Models\KategoriInstansi;
use Illuminate\Database\Seeder;

class KategoriInstansiSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Kementerian', 'LPPND', 'Pemerintah Daerah', 'Universitas/Lainnya'] as $nama) {
            KategoriInstansi::firstOrCreate(['nama' => $nama], ['status_aktif' => true]);
        }
    }
}
