<?php

namespace Database\Seeders;

use App\Models\Penyakit;
use Illuminate\Database\Seeder;
class PenyakitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Penyakit::create([
            'kd_penyakit' => 'P1',
            'nama_penyakit' => 'Insomnia',
            'deskripsi' => 'Penyakit gk bisa tidur',
        ]);
    }
}


