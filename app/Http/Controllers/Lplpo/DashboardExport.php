<?php

namespace App\Http\Controllers\Lplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;

class DashboardExport implements FromCollection
{
    protected $bulan, $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return DB::table('lplpo_final')
            ->select('kode_faskes','kode_obat','nama_obat','stok_akhir_field1')
            ->where('bulan', $this->bulan)
            ->where('tahun', $this->tahun)
            ->get();
    }
}
