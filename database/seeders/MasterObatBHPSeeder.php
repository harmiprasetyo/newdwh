<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterObatBHPSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            [
                'kode_obat' => 'BHP1',
                'nama_obat' => 'ALKOHOL SWAB',
                'satuan' => 'BOX',
            ],
            [
                'kode_obat' => 'BHP2',
                'nama_obat' => 'ALAT SUNTIK SEKALI PAKAI 1 ML + JARUM',
                'satuan' => 'BUAH',
            ],
            [
                'kode_obat' => 'BHP3',
                'nama_obat' => 'ALAT SUNTIK SEKALI PAKAI 2,5 ML-3 ML+ JARUM',
                'satuan' => 'BUAH',
            ],
            [
                'kode_obat' => 'BHP4',
                'nama_obat' => 'ALAT SUNTIK SEKALI PAKAI 5ML + JARUM',
                'satuan' => 'BUAH',
            ],
            [
                'kode_obat' => 'BHP5',
                'nama_obat' => 'APD',
                'satuan' => 'PAKET',
            ],
            [
                'kode_obat' => 'BHP6',
                'nama_obat' => 'BLOOD LANCETE',
                'satuan' => 'BOX',
            ],
            [
                'kode_obat' => 'BHP7',
                'nama_obat' => 'BOX SLIDE',
                'satuan' => 'BUAH',
            ],
            [
                'kode_obat' => 'BHP8',
                'nama_obat' => 'CAT GUT CROMIC 2/0 + JARUM',
                'satuan' => 'BUAH',
            ],
            [
                'kode_obat' => 'BHP9',
                'nama_obat' => 'CAT GUT CROMIC 3/0 + JARUM',
                'satuan' => 'BUAH',
            ],
            [
                'kode_obat' => 'BHP10',
                'nama_obat' => 'CAT GUT PLAIN 2/0+ JARUM',
                'satuan' => 'BUAH',
            ],
            [
                'kode_obat' => 'BHP11',
                'nama_obat' => 'INFUSET ANAK',
                'satuan' => 'SET',
            ],
            [
                'kode_obat' => 'BHP12',
                'nama_obat' => 'INFUSET DEWASA',
                'satuan' => 'SET',
            ],
            [
                'kode_obat' => 'BHP13',
                'nama_obat' => 'IV.CATHETER NO. 18 G',
                'satuan' => 'BUAH',
            ],
            [
                'kode_obat' => 'BHP14',
                'nama_obat' => 'IV.CATHETER NO. 20 G',
                'satuan' => 'BUAH',
            ],
            [
                'kode_obat' => 'BHP15',
                'nama_obat' => 'IV.CATHETER NO. 22 G',
                'satuan' => 'BUAH',
            ],
            [
                'kode_obat' => 'BHP16',
                'nama_obat' => 'IV.CATHETER NO. 24 G',
                'satuan' => 'PCS',
            ],
            [
                'kode_obat' => 'BHP17',
                'nama_obat' => 'KAPAS PEMBALUT 250 MG',
                'satuan' => 'ROLL',
            ],
            [
                'kode_obat' => 'BHP18',
                'nama_obat' => 'KASA STERIL 16 CM X 16 CM',
                'satuan' => 'KOTAK',
            ],
            [
                'kode_obat' => 'BHP19',
                'nama_obat' => 'KASSA PEMBALUT HIDROFIL 4 M X 15 CM (4X10)',
                'satuan' => 'BUAH',
            ],
            [
                'kode_obat' => 'BHP20',
                'nama_obat' => 'MASKER KF94',
                'satuan' => 'BOX',
            ],
            [
                'kode_obat' => 'BHP21',
                'nama_obat' => 'MASKER KN95',
                'satuan' => 'PCS',
            ],
            [
                'kode_obat' => 'BHP22',
                'nama_obat' => 'MASKER N95',
                'satuan' => 'PCS',
            ],
            [
                'kode_obat' => 'BHP23',
                'nama_obat' => 'PLESTER KECIL',
                'satuan' => 'BUAH',
            ],
            [
                'kode_obat' => 'BHP24',
                'nama_obat' => 'PLESTERIN ROLL NON WOVEN ROLL 5CM X 5M',
                'satuan' => 'ROLL',
            ],
            [
                'kode_obat' => 'BHP25',
                'nama_obat' => 'SARUNG TANGAN OBGYN',
                'satuan' => 'PCS',
            ],
            [
                'kode_obat' => 'BHP26',
                'nama_obat' => 'SARUNG TANGAN PENDEK NON STERIL UKURAN L',
                'satuan' => 'KOTAK',
            ],
            [
                'kode_obat' => 'BHP27',
                'nama_obat' => 'SARUNG TANGAN PENDEK NON STERIL UKURAN M',
                'satuan' => 'KOTAK',
            ],
            [
                'kode_obat' => 'BHP28',
                'nama_obat' => 'SARUNG TANGAN POWDER FREE',
                'satuan' => 'KOTAK',
            ],
            [
                'kode_obat' => 'BHP29',
                'nama_obat' => 'SILK (BENANG BEDAH SUTERA) NO. 3/0',
                'satuan' => 'BOX',
            ],
            [
                'kode_obat' => 'BHP30',
                'nama_obat' => 'SILK( BENANG BEDAH SUTERA) NO 2/0',
                'satuan' => 'BOX',
            ],
            [
                'kode_obat' => 'BHP31',
                'nama_obat' => 'SURGICAL FACE MASK TYPE II R',
                'satuan' => 'BOX',
            ],
            [
                'kode_obat' => 'BHP32',
                'nama_obat' => 'ALKOHOL SWAB',
                'satuan' => 'BOX',
            ],
            [
                'kode_obat' => 'BHP33',
                'nama_obat' => 'BLOOD LANCET',
                'satuan' => 'BOX',
            ],
            [
                'kode_obat' => 'BHP34',
                'nama_obat' => 'HB HEMOGLOBIN TEST STRIPS',
                'satuan' => 'KOTAK',
            ],
            [
                'kode_obat' => 'BHP35',
                'nama_obat' => 'ALAT SUNTIK SEKALI PAKAI 1 ML',
                'satuan' => 'PIECES',
            ],
            [
                'kode_obat' => 'BHP36',
                'nama_obat' => 'ALKOHOL SWAB',
                'satuan' => 'BOX',
            ],
            [
                'kode_obat' => 'BHP37',
                'nama_obat' => 'CRYPTOCOCCUS ANTIGEN',
                'satuan' => 'TEST',
            ],
            [
                'kode_obat' => 'BHP38',
                'nama_obat' => 'KONDOM',
                'satuan' => 'BUAH',
            ],
            [
                'kode_obat' => 'BHP39',
                'nama_obat' => 'MASKER BEDAH',
                'satuan' => 'KOTAK',
            ],
            [
                'kode_obat' => 'BHP40',
                'nama_obat' => 'ADS 0,3 ML',
                'satuan' => 'PCS',
            ],
            [
                'kode_obat' => 'BHP41',
                'nama_obat' => 'ADS 0,5 ML',
                'satuan' => 'SET',
            ],
            [
                'kode_obat' => 'BHP42',
                'nama_obat' => 'ADS 0,05 ML',
                'satuan' => 'PIECES',
            ],
            [
                'kode_obat' => 'BHP43',
                'nama_obat' => 'ADS 5ML',
                'satuan' => 'SET',
            ],
            [
                'kode_obat' => 'BHP44',
                'nama_obat' => 'ALKOHOL SWAB',
                'satuan' => 'PCS',
            ],
            [
                'kode_obat' => 'BHP45',
                'nama_obat' => 'DISPOSABLE SYRINGE 3 ML (TP)',
                'satuan' => 'PCS',
            ],
            [
                'kode_obat' => 'BHP46',
                'nama_obat' => 'SAFETY BOX',
                'satuan' => 'PIECES',
            ],
            [
                'kode_obat' => 'BHP47',
                'nama_obat' => 'SAFETY BOX 2,5 L',
                'satuan' => 'PIECES',
            ],
            [
                'kode_obat' => 'BHP48',
                'nama_obat' => 'BLUE TIPS',
                'satuan' => 'PACK',
            ],
            [
                'kode_obat' => 'BHP49',
                'nama_obat' => 'KERTAS EKG 12 CHANNEL UKURAN 110/112 MM',
                'satuan' => 'ROLL',
            ],
            [
                'kode_obat' => 'BHP50',
                'nama_obat' => 'KERTAS EKG 12 CHANNEL UKURAN 210 MM',
                'satuan' => 'ROLL',
            ],
            [
                'kode_obat' => 'BHP51',
                'nama_obat' => 'KERTAS EKG 12 CHANNEL UKURAN 80 MM',
                'satuan' => 'ROLL',
            ],
            [
                'kode_obat' => 'BHP52',
                'nama_obat' => 'KERTAS EKG 3 CHANNEL UKURAN 63 MM',
                'satuan' => 'ROLL',
            ],
            [
                'kode_obat' => 'BHP53',
                'nama_obat' => 'ULTRASOUND GEL 250 ML',
                'satuan' => 'BOTOL',
            ],
            [
                'kode_obat' => 'BHP54',
                'nama_obat' => 'VACUTAINER CLOT ACTIVATOR',
                'satuan' => 'PACK',
            ],
            [
                'kode_obat' => 'BHP55',
                'nama_obat' => 'BOOKLET OAT DOSIS HARIAN',
                'satuan' => 'TABLET',
            ],
            [
                'kode_obat' => 'BHP56',
                'nama_obat' => 'KACA SLIDE',
                'satuan' => 'KOTAK',
            ],
            [
                'kode_obat' => 'BHP57',
                'nama_obat' => 'MASKER BEDAH',
                'satuan' => 'BOX',
            ],
            [
                'kode_obat' => 'BHP58',
                'nama_obat' => 'MASKER BEDAH (MRA)',
                'satuan' => 'PCS',
            ],
            [
                'kode_obat' => 'BHP59',
                'nama_obat' => 'MASKER EARLOOP HIJAB',
                'satuan' => 'PCS',
            ],
            [
                'kode_obat' => 'BHP60',
                'nama_obat' => 'POT DAHAK',
                'satuan' => 'POT',
            ],
        ];

        $data = array_map(function ($item) use ($now) {
            return array_merge([
                'obat_napza' => 'tidak',
                'kelompok_obat' => null,
                'golongan_obat' => null,
                'kategori_obat' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ], $item);
        }, $data);

        DB::table('master_obat')->upsert(
            $data,
            ['kode_obat'],
            [
                'nama_obat',
                'satuan',
                'obat_napza',
                'kelompok_obat',
                'golongan_obat',
                'kategori_obat',
                'updated_at',
            ]
        );
    }
}
