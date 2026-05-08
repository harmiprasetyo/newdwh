<?php

namespace App\Imports;

use App\Models\Lplpo\Lplpo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class LplpoImport implements ToCollection
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    // helper aman
    private function toInt($val)
    {
        return is_numeric($val) ? (int)$val : 0;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            if ($index == 0) continue; // skip header

            // skip baris kosong
            if (empty($row[1])) continue;

            $stok_awal_pkd = $this->toInt($row[3]);
            $stok_awal_program = $this->toInt($row[4]);

            $penerimaan_pkd = $this->toInt($row[5]);
            $penerimaan_program = $this->toInt($row[6]);

            $persediaan_pkd = $stok_awal_pkd + $penerimaan_pkd;
            $persediaan_program = $stok_awal_program + $penerimaan_program;

            $pemakaian_pkd = $this->toInt($row[9]);
            $pemakaian_program = $this->toInt($row[10]);

             $kadaluarsa = $this->toInt($row[11]);
            $pengembalian = $this->toInt($row[12]);

            $stok_akhir_pkd = $persediaan_pkd - $pemakaian_pkd - $pengembalian-$kadaluarsa;
            $stok_akhir_program = $persediaan_program - $pemakaian_program;

            $rko = $this->toInt($row[15]);

            $stok_optimum = (int) round($rko * 2.5);
            $permintaan = $stok_optimum - ($stok_akhir_pkd + $stok_akhir_program);
            $keterangan = $row[19] ?? '';

            Lplpo::create([
                'nama_obat' => $row[1],
                'kode_faskes' => auth()->user()->kodeFaskes ?? 'DEFAULT',

                'bulan' => $this->bulan,
                'tahun' => $this->tahun,

                'stok_awal_pkd' => $stok_awal_pkd,
                'stok_awal_program' => $stok_awal_program,

                'penerimaan_pkd' => $penerimaan_pkd,
                'penerimaan_program' => $penerimaan_program,

                'persediaan_pkd' => $persediaan_pkd,
                'persediaan_program' => $persediaan_program,

                'pemakaian_pkd' => $pemakaian_pkd,
                'pemakaian_program' => $pemakaian_program,

                'kadaluarsa' => $kadaluarsa,
                'pengembalian' => $pengembalian,

                'stok_akhir_pkd' => $stok_akhir_pkd,
                'stok_akhir_program' => $stok_akhir_program,

                'rko' => $rko,
                'stok_optimum' => $stok_optimum,
                'permintaan' => $permintaan,
                'keterangan' => $keterangan
            ]);
        }
    }
}
