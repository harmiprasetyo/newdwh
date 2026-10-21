<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterPuskesmasBekasiSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $data = [
            ['32750200001', 'PONDOK GEDE',       '327508'],
            ['32750200002', 'JATIBENING',        '327508'],
            ['32750200003', 'JATIMAKMUR',        '327508'],
            ['32750200004', 'JATISAMPURNA',      '327510'],
            ['32750200005', 'JATIRAHAYU',        '327512'],
            ['32750200006', 'JATIWARNA',         '327512'],
            ['32750200007', 'JATI ASIH',         '327509'],
            ['32750200008', 'JATI LUHUR',        '327509'],
            ['32750200009', 'BANTAR GEBANG',     '327507'],
            ['32750200010', 'MUSTIKA JAYA',      '327511'],
            ['32750200011', 'DUREN JAYA',        '327501'],
            ['32750200012', 'BEKASI JAYA',       '327501'],
            ['32750200013', 'KARANG KITRI',      '327501'],
            ['32750200014', 'AREN JAYA',         '327501'],
            ['32750200015', 'BOJONG RAWA LUMBU', '327505'],
            ['32750200016', 'PENGASINAN',        '327505'],
            ['32750200017', 'BOJONG MENTENG',    '327505'],
            ['32750200018', 'PERUMNAS II',       '327504'],
            ['32750200019', 'JAKA MULYA',        '327504'],
            ['32750200020', 'PEKAYON JAYA',      '327504'],
            ['32750200021', 'MARGA JAYA',        '327504'],
            ['32750200022', 'KOTA BARU',         '327502'],
            ['32750200023', 'RAWA TEMBAGA',      '327502'],
            ['32750200024', 'BINTARA',           '327502'],
            ['32750200025', 'BINTARA JAYA',      '327502'],
            ['32750200026', 'KRANJI',            '327502'],
            ['32750200027', 'PEJUANG',           '327506'],
            ['32750200028', 'SEROJA',            '327503'],
            ['32750200029', 'TELUK PUCUNG',      '327503'],
            ['32750200030', 'MARGA MULYA',       '327503'],
            ['32750200031', 'KALIABANG TENGAH',  '327503'],
            ['32750200032', 'JATI RANGGON',      '327510'],
            ['32750200033', 'PERWIRA',           '327503'],
            ['32750200034', 'HARAPAN BARU',      '327503'],
            ['32750200035', 'CIKETING UDIK',     '327507'],
            ['32750200036', 'KALI BARU',         '327506'],
            ['32750200037', 'JAKA SETIA',        '327504'],
            ['32750200038', 'CIMUNING',          '327507'],
            ['32750200039', 'PADURENAN',         '327511'],
            ['32750200040', 'SUMUR BATU',        '327507'],
            ['32750200041', 'MUSTIKASARI',       '327511'],
            ['32750200042', 'JATIBENING BARU',   '327508'],
            ['32750200043', 'JATIKARYA',         '327510'],
            ['32750200044', 'JATIKRAMAT',        '327509'],
            ['32750200045', 'JATIMEKAR',         '327509'],
            ['32750200046', 'MEDAN SATRIA',      '327506'],
            ['32750200047', 'HARAPAN MULYA',     '327506'],
        ];

        DB::transaction(function () use ($data, $now) {
            foreach ($data as [$kodeFaskes, $namaFaskes, $kodeKecamatan]) {
                DB::table('master_faskes')->updateOrInsert(
                    ['kodeFaskes' => $kodeFaskes],
                    [
                        'typeFaskes'    => 1,
                        'kodePropinsi'  => '32',
                        'kodeKabupaten' => '3275',
                        'kodeKecamatan' => $kodeKecamatan,
                        'kepemilikan'   => 'Pemerintah',
                        'namaFaskes'    => $namaFaskes,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ]
                );
            }
        });
    }
}
