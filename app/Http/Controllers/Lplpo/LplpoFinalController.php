<?php
namespace App\Http\Controllers\Lplpo;
use App\Models\Lplpo\LplpoHeaderReport;
use App\Models\Lplpo\LplpoFinal;
use App\Models\MasterFaskes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LplpoFinalController extends Controller
{
    // ======================
    // HALAMAN LIST
    // ======================
    public function index()
    {
        return view('lplpo.final');
    }

    public function data()
    {
        $query = LplpoHeaderReport::query()
            ->leftJoin('master_faskes', 'lplpo_header_report.kode_faskes', '=', 'master_faskes.kodeFaskes')
            ->select(
                'lplpo_header_report.*',
                'master_faskes.namaFaskes'
            )
            ->where('final', true)
            ->orderBy('id', 'desc');

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                return '<a href="'.route('lplpo.final.detail', $row->id).'" class="btn btn-sm btn-info">Detail</a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // ======================
    // HALAMAN DETAIL
    // ======================
    public function detail($header_id)
    {
          $header = LplpoHeaderReport::query()
        ->leftJoin('master_faskes', 'lplpo_header_report.kode_faskes', '=', 'master_faskes.kodeFaskes')
        ->select('lplpo_header_report.*', 'master_faskes.namaFaskes')
        ->where('lplpo_header_report.id', $header_id)
        ->firstOrFail();

        return view('lplpo.detail', compact('header'));
    }

    public function detailData($header_id)
    {
        $query = LplpoFinal::where('header_id', $header_id);

        return datatables()->of($query)
            ->addIndexColumn()
            ->make(true);
    }
}
