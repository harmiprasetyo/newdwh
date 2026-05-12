<?php

namespace App\Http\Controllers\Lplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
use App\Models\Lplpo\Lplpo;

class DashboardController extends Controller
{
    //

 public function index()
    {
        return view('lplpo.dashboard_dinkes');
    }

    public function data(Request $request)
    {
        $query = Lplpo::query();

        // FILTER
        if ($request->bulan) {
            $query->where('bulan', $request->bulan);
        }

        if ($request->tahun) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->faskes) {
            $query->whereIn('kode_faskes', explode(',', $request->faskes));
        }

        return response()->json([

            // KPI
            'total_faskes' => (clone $query)->distinct('kode_faskes')->count(),
            'total_obat' => (clone $query)->count(),
            'total_pemakaian' => (clone $query)->sum('pemakaian_pkd'),
            'total_stok' => (clone $query)->sum('stok_akhir_pkd'),

            // CHART PER FASKES
            'chart_faskes' => (clone $query)
                ->select('kode_faskes', DB::raw('SUM(pemakaian_pkd) as total'))
                ->groupBy('kode_faskes')
                ->orderByDesc('total')
                ->get(),

            // TOP OBAT
            'top_obat' => (clone $query)
                ->select('nama_obat', DB::raw('SUM(pemakaian_pkd) as total'))
                ->groupBy('nama_obat')
                ->orderByDesc('total')
                ->limit(10)
                ->get(),

            // RANKING FASKES
            'ranking_faskes' => (clone $query)
                ->select('kode_faskes', DB::raw('SUM(pemakaian_pkd) as total'))
                ->groupBy('kode_faskes')
                ->orderByDesc('total')
                ->limit(10)
                ->get(),

            // STOK KRITIS
            'stok_kritis' => (clone $query)
                ->whereColumn('stok_akhir_pkd', '<', 'stok_optimum')
                ->limit(20)
                ->get([
                    'nama_obat',
                    'kode_faskes',
                    'stok_akhir_pkd',
                    'stok_optimum'
                ])
        ]);
    }


public function dashboard()
{
    return view('lplpo.lplpodashboard');
}

public function dashboardData(Request $request)
{
    $query = Lplpo::query();

    if ($request->bulan) $query->where('bulan', $request->bulan);
    if ($request->tahun) $query->where('tahun', $request->tahun);
    if ($request->faskes) $query->where('kode_faskes', $request->faskes);

    return response()->json([
        'total_obat' => $query->count(),

        'total_pemakaian' => $query->sum('pemakaian_pkd'),
        'total_stok_akhir' => $query->sum('stok_akhir_pkd'),

        'top_obat' => $query->select('nama_obat', DB::raw('SUM(pemakaian_pkd) as total'))
            ->groupBy('nama_obat')
            ->orderByDesc('total')
            ->limit(5)
            ->get(),

        'chart' => $query->select('bulan', DB::raw('SUM(pemakaian_pkd) as total'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
    ]);
}





public function pivot()
{
    return view('lplpo.pivot');
}

public function pivotData(Request $request)
{
    $tahun = $request->tahun ?? date('Y');

    $data = DB::table('lplpo')
        ->leftJoin('master_faskes', 'lplpo.kode_faskes', '=', 'master_faskes.kodeFaskes')
        ->select(
            'master_faskes.namaFaskes',

            DB::raw("SUM(CASE WHEN bulan = 1 THEN pemakaian_pkd ELSE 0 END) as jan"),
            DB::raw("SUM(CASE WHEN bulan = 2 THEN pemakaian_pkd ELSE 0 END) as feb"),
            DB::raw("SUM(CASE WHEN bulan = 3 THEN pemakaian_pkd ELSE 0 END) as mar"),
            DB::raw("SUM(CASE WHEN bulan = 4 THEN pemakaian_pkd ELSE 0 END) as apr"),
            DB::raw("SUM(CASE WHEN bulan = 5 THEN pemakaian_pkd ELSE 0 END) as mei"),
            DB::raw("SUM(CASE WHEN bulan = 6 THEN pemakaian_pkd ELSE 0 END) as jun"),
            DB::raw("SUM(CASE WHEN bulan = 7 THEN pemakaian_pkd ELSE 0 END) as jul"),
            DB::raw("SUM(CASE WHEN bulan = 8 THEN pemakaian_pkd ELSE 0 END) as agu"),
            DB::raw("SUM(CASE WHEN bulan = 9 THEN pemakaian_pkd ELSE 0 END) as sep"),
            DB::raw("SUM(CASE WHEN bulan = 10 THEN pemakaian_pkd ELSE 0 END) as okt"),
            DB::raw("SUM(CASE WHEN bulan = 11 THEN pemakaian_pkd ELSE 0 END) as nov"),
            DB::raw("SUM(CASE WHEN bulan = 12 THEN pemakaian_pkd ELSE 0 END) as des"),

            DB::raw("SUM(pemakaian_pkd) as total")
        )
        ->where('tahun', $tahun)
        ->groupBy('master_faskes.namaFaskes')
        ->orderBy('master_faskes.namaFaskes')
        ->get();

    return response()->json($data);
}



public function pivotChart(Request $request)
{
    $tahun = $request->tahun ?? date('Y');

    $data = DB::table('lplpo')
        ->leftJoin('master_faskes', 'lplpo.kode_faskes', '=', 'master_faskes.kodeFaskes')
        ->select(
            'master_faskes.namaFaskes',
            'bulan',
            DB::raw('SUM(pemakaian_pkd) as total')
        )
        ->where('tahun', $tahun)
        ->groupBy('master_faskes.namaFaskes', 'bulan')
        ->orderBy('bulan')
        ->get();

    return response()->json($data);
}


}
