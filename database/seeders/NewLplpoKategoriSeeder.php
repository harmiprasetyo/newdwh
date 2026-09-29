<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewLplpoKategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [ 'Antipirai', 'Antihipertensi Sistemik – Calcium Channel Blocker (CCB)', 'Antihipertensi Sistemik – ACE Inhibitor', 'Diuretik', 'Antasida', 'Antiulkus – Agonis Reseptor H2/PPI', 'Antibakteri Sistemik', 'Antialergi dan Obat untuk Anafilaksis', 'Antiemetik', 'Antifungi Topikal', 'Vitamin Lainnya dan Mineral', 'Antidiabetes – Insulin Secretagogue (Sulfonilurea)', 'Antidiabetes – Non Insulin Secretagogue (Biguanid, Inhibitor Alpha Glucosidase)', 'Analgesik Non Narkotik – Antipiretik', 'Analgesik Non Narkotik – Antiinflamasi Non Steroid', 'Antiinflamasi dan Antipruritik Topikal', 'Kortikosteroid', 'Antiasma', 'Obat untuk Syok Kardiogenik dan Sepsis', 'Anastetik Lokal', 'Antiherpes', 'Antihiperlipidemia', 'Antiskabies', 'Antiseptik', 'Antiangina', 'Antifungi Sistemik', 'Obat Batuk', 'Obat Tuberkulosis', 'Obat Malaria', 'Obat HIV/AIDS', 'Gizi Ibu Hamil dan Rematri', 'Gizi Balita', 'Obat yang Memengaruhi Koagulasi', 'Oksitoksik', 'Antelmintik', 'Obat untuk Diare', 'Antimikroba untuk Mata', 'Antipsikotik', 'Antiansietas', 'Antiepilepsi-Antikonvulsi', ];

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
