<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use App\Models\NewLplpo\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Master\MasterFaskes;
use App\Models\NewLplpo\Item;
use Illuminate\Support\Facades\DB;



class LplpoPemberianController extends Controller
{
    /**
     * Halaman daftar laporan yang siap diberikan obat
     */
    public function index()
    {
        return view('newlplpo.pemberian');
    }

    /**
     * Datatable
     */
    public function datatable(Request $request)
    {


    $user = Auth::user();

        $query = Report::query()
    ->leftJoin(
        'master_faskes',
        'master_faskes.kodeFaskes',
        '=',
        'new_lplpo_reports.kode_faskes'
    )
    ->withCount('items')
    ->where('report_status','VERIFIED')
    ->where('master_faskes.kodeKabupaten',$user->kodeKota)
    ->when($request->bulan,function($q) use($request){
        $q->where('new_lplpo_reports.bulan',$request->bulan);
    })
    ->when($request->tahun,function($q) use($request){
        $q->where('new_lplpo_reports.tahun',$request->tahun);
    })
    ->select([
        'new_lplpo_reports.*',
        'master_faskes.namaFaskes as nama_faskes'
    ]);

   return DataTables::of($query)

    ->addIndexColumn()

    ->addColumn('items_count', function ($row) {
        return $row->items()->count();
    })

    ->editColumn('created_at', function ($row) {
        return optional($row->created_at)->format('d/m/Y');
    })

    ->addColumn('status_badge', function ($row) {

        return '<span class="badge bg-success">'
            .$row->report_status.
            '</span>';

    })

    ->addColumn('action', function ($row) {

        return '<a href="'.route('newlplpo.pemberian.detail',$row->id).'"
                    class="btn btn-primary btn-sm">
                    <i class="bi bi-eye"></i>
                </a>';

    })

    ->rawColumns(['status_badge','action'])

    ->make(true);
    }

    /**
     * Detail laporan
     */
 public function detail($id)
{
    $report = Report::findOrFail($id);

    $items = Item::with('program')
        ->where('report_id', $report->id)
        ->orderBy('program_id')
        ->orderBy('nama_obat')
        ->get();

    $faskes = MasterFaskes::with([
        'type',
        'provinsi',
        'kota',
        'kecamatan'
    ])
    ->where('kodeFaskes', $report->kode_faskes)
    ->firstOrFail();

    return view(
        'newlplpo.pemberian_detail',
        compact(
            'report',
            'items',
            'faskes'
        )
    );
}

    /**
     * Update jumlah pemberian
     * (Tahap II)
     */


public function updatePemberian(Request $request, $id)
{
    $request->validate([
        'pemberian_program_pkd' => 'required|numeric|min:0',
        'pemberian_jkn'         => 'required|numeric|min:0',
    ]);

    $item = Item::findOrFail($id);

    // Validasi tidak boleh melebihi permintaan
    $totalPemberian = $request->pemberian_program_pkd + $request->pemberian_jkn;

    if ($totalPemberian > $item->permintaan) {
        return response()->json([
            'message' => 'Total pemberian tidak boleh melebihi jumlah permintaan.'
        ], 422);
    }

    $item->update([
        'pemberian_program_pkd' => $request->pemberian_program_pkd,
        'pemberian_jkn'         => $request->pemberian_jkn,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Pemberian berhasil disimpan.'
    ]);
}

    /**
     * Finalisasi pemberian
     * (Tahap III)
     */


public function finish($id)
{
    DB::beginTransaction();

    try {

        $report = Report::findOrFail($id);

        $belumDiisi = Item::where('report_id', $id)
            ->where(function ($q) {
                $q->whereNull('pemberian_program_pkd')
                  ->orWhereNull('pemberian_jkn');
            })
            ->count();

        if ($belumDiisi > 0) {

            return response()->json([
                'success' => false,
                'message' => 'Masih ada item obat yang belum diinput pemberiannya.'
            ], 422);

        }

        $report->update([
            'report_status' => 'FINAL'
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diselesaikan.'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);

    }
}
}
