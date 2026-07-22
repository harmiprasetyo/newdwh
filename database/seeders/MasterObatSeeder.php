<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterObatSeeder extends Seeder
{
    public function run()
    {
        DB::table('master_obat')->insert([
            [
                'nama_obat' => 'ALLOPURINOL TAB 300 mg',
                'kode_obat' => '92000281',
                'satuan' => 'TABLET',
                'stok_minimal'=>'100'
            ],
            [
                'nama_obat' => 'ALLOPURINOL TAB 100 mg',
                'kode_obat' => '92000124',
                'satuan' => 'TABLET',
                'stok_minimal'=>'100'
            ],
            [
                'nama_obat' => 'AMBROKSOL TAB 30 mg',
                'kode_obat' => '9199988',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'AMITRIPTILIN TAB 25 mg',
                'kode_obat' => '9288889',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'AMLODIPIN TAB 5 mg',
                'kode_obat' => '92001058',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'AMLODIPIN TAB 10 mg',
                'kode_obat' => '92000407',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'AMOKSISILIN KAP 250 mg',
                'kode_obat' => '92000284',
                'stok_minimal'=>'100',
                'satuan' => 'KAPSUL'
            ],
            [
                'nama_obat' => 'AMOKSISILIN TAB 500 mg',
                'kode_obat' => '92000881',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'ANTASIDA DOEN TAB KUNYAH',
                'kode_obat' => '92000798',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'ANTI DIARE (ATTAPULGIT)',
                'kode_obat' => '92001138',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'ASAM MEFENAMAT TAB 500 mg',
                'kode_obat' => '92000568',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'ASAM ASKORBAT TAB 250 mg',
                'kode_obat' => '92001587',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'ASAM ASETIL SALISILAT TAB 80 mg',
                'kode_obat' => '92000659',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'ASAM FOLAT TAB 0.4 mg',
                'kode_obat' => '92001357',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'ASETIL SISTEIN TAB 200 mg',
                'kode_obat' => '92000610',
                'stok_minimal'=>'100',
                'satuan' => 'KAPSUL'
            ],
            [
                'nama_obat' => 'ASIKLOVIR TAB 200 mg',
                'kode_obat' => '92000472',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'ASIKLOVIR TAB 400 mg',
                'kode_obat' => '92000770',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'BETAHISTIN MESILAT TAB 6 mg',
                'kode_obat' => '92000000',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'CETIRIZIN TAB 10 mg',
                'kode_obat' => '92000121',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'DEKSAMETASON TAB 0.5 mg',
                'kode_obat' => '92000525',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'DIAZEPAM TAB 2 mg',
                'kode_obat' => '92000132',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'DIAZEPAM TAB 5 mg',
                'kode_obat' => '92000435',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'DOMPERIDON TAB 10 mg',
                'kode_obat' => '92000139',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'DOKSISIKLIN KAP 100 mg',
                'kode_obat' => '92000584',
                'stok_minimal'=>'100',
                'satuan' => 'KAPSUL'
            ],
            [
                'nama_obat' => 'ERYTROMISIN TAB 500 mg',
                'kode_obat' => '92001215',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'ERYTROMISIN TAB 250 mg',
                'kode_obat' => '92000874',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'FENOBARBITAL TAB 30 mg',
                'kode_obat' => '92000374',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'FUROSEMIDA TAB 40 mg',
                'kode_obat' => '92000914',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'GLIMEPIRIDE TAB 2 mg',
                'kode_obat' => '92000163',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'HALOPERIDOL TAB 5 mg',
                'kode_obat' => '92000445',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'IBUPROFEN TAB 400 mg',
                'kode_obat' => '92001497',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'KETOKONAZOL TAB 200 mg',
                'kode_obat' => '92000357',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'METFORMIN TAB 500 mg',
                'kode_obat' => '92000209',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'METRONIDAZOL TAB 500 mg',
                'kode_obat' => '92000959',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'OMEPRAZOL KAP 20 mg',
                'kode_obat' => '92001480',
                'stok_minimal'=>'100',
                'satuan' => 'KAPSUL'
            ],
            [
                'nama_obat' => 'PARASETAMOL TAB 500 mg',
                'kode_obat' => '92001267',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
            [
                'nama_obat' => 'PREDNISON TAB 5 mg',
                'kode_obat' => '92000706',
                'stok_minimal'=>'100',
                'satuan' => 'TABLET'
            ],
        ]);
    }
}
