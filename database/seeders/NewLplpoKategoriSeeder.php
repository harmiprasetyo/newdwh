<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewLplpoKategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            'Analgesik & Antipiretik',
            'Antiinflamasi',
            'Antibiotik',
            'Antimikroba',
            'Antijamur',
            'Antivirus',
            'Antiparasit',
            'Antihistamin / Antialergi',
            'Kortikosteroid',
            'Obat Saluran Pernapasan',
            'Antiasma',
            'Antitusif',
            'Ekspektoran / Mukolitik',
            'Obat Gastrointestinal',
            'Antasida',
            'Antiemetik',
            'Antidiare',
            'Laksatif',
            'Obat Hipertensi',
            'Obat Jantung & Kardiovaskular',
            'Diuretik',
            'Obat Diabetes / Antidiabetik',
            'Obat Penurun Kolesterol',
            'Obat Anemia',
            'Vitamin & Mineral',
            'Cairan Rehidrasi',
            'Antiseptik',
            'Obat Luka / Topikal',
            'Obat Dermatologi',
            'Obat Mata',
            'Obat Telinga',
            'Obat Hidung',
            'Obat Mulut & Gigi',
            'Obat Kebidanan',
            'Obat Kesehatan Reproduksi',
            'Imunisasi / Vaksin',
            'Obat Kegawatdaruratan',
            'Obat Penyakit Menular',
            'Obat Program Puskesmas',
            'Obat Tradisional / Komplementer',
        ];

        foreach ($kategori as $item) {
            DB::table('new_lplpo_kategori')->updateOrInsert(
                ['kategori' => $item],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}