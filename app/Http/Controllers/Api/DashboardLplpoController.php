<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Lplpo\DashboardExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardLplpoController extends Controller
{


public function index(Request $request)
{
    $bulan = $request->bulan;
    $tahun = $request->tahun;

    $data = DB::table('lplpo_final as l1')
        ->select(
            'l1.kode_faskes',
            'l1.kode_obat',
            DB::raw('(l1.stok_akhir_field1 + l1.stok_akhir_field2 + l1.stok_akhir_field3) as stok_akhir'),

            DB::raw('(
                SELECT
                    SUM(l2.pemakaian_field1 + l2.pemakaian_field2 + l2.pemakaian_field3)
                    / COUNT(DISTINCT CONCAT(l2.bulan,"-",l2.tahun))
                FROM lplpo_final l2
                WHERE l2.kode_obat = l1.kode_obat
                AND l2.kode_faskes = l1.kode_faskes
                AND (l2.tahun < '.$tahun.' OR (l2.tahun = '.$tahun.' AND l2.bulan <= '.$bulan.'))
            ) as avg_pemakaian')
        )
        ->where('l1.bulan', $bulan)
        ->where('l1.tahun', $tahun)
        ->get();

    $obatMonitor = $data->unique('kode_obat')->count();

    $stokKritis = 0;
    $stokOut = 0;

    $perFaskes = [];

    foreach ($data as $d) {

        $avgHarian = ($d->avg_pemakaian ?? 0) / 30;

        if ($avgHarian <= 0) continue;

        $dos = $d->stok_akhir / $avgHarian;

        // grouping per faskes
        if (!isset($perFaskes[$d->kode_faskes])) {
            $perFaskes[$d->kode_faskes] = [
                'kritis' => 0,
                'risk' => 0,
                'aman' => 0
            ];
        }

        if ($dos < 7) {
            $stokKritis++;
            $perFaskes[$d->kode_faskes]['kritis']++;
        } elseif ($dos <= 30) {
            $stokOut++;
            $perFaskes[$d->kode_faskes]['risk']++;
        } else {
            $perFaskes[$d->kode_faskes]['aman']++;
        }
    }

    return response()->json([
        'summary' => [
            'obat_monitor' => $obatMonitor,
            'stok_kritis' => $stokKritis,
            'stok_out' => $stokOut
        ],
        'chart' => $perFaskes
    ]);
}


public function export(Request $request)
{
    return Excel::download(
        new DashboardExport($request->bulan, $request->tahun),
        'dashboard_lplpo.xlsx'
    );
}

}
