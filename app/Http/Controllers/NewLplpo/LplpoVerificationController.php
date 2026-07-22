<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\NewLplpo\Report;
use App\Models\NewLplpo\Item;
use App\Models\NewLplpo\ReportNote;
use App\Models\Master\MasterFaskes;

use Yajra\DataTables\Facades\DataTables;

class LplpoVerificationController extends Controller
{

    /**
     * ==========================================================
     * Halaman daftar verifikasi
     * ==========================================================
     */
    public function index()
    {

   // dd(auth()->check(), auth()->user());
    return view('newlplpo.verifikasi.index');
    }

    /**
     * ==========================================================
     * Datatable laporan yang menunggu verifikasi
     * ==========================================================
     */
    public function datatable(Request $request)
    {

        $kodeKabupaten = auth()->user()->kodeKota;

      //  dd($kodeKabupaten);

        $query = Report::query()

            ->join(
                'master_faskes',
                'master_faskes.kodeFaskes',
                '=',
                'new_lplpo_reports.kode_faskes'
            )

            ->where('report_status','SUBMITED')

            ->where(
                'master_faskes.kodeKabupaten',
                $kodeKabupaten
            )

            ->when($request->bulan,function($q) use($request){

                $q->where('bulan',$request->bulan);

            })

            ->when($request->tahun,function($q) use($request){

                $q->where('tahun',$request->tahun);

            })

            ->select(
                'new_lplpo_reports.*',
                'master_faskes.namaFaskes'
            )

            ->withCount('items');
            //dd($query);

        return DataTables::of($query)

            ->addIndexColumn()

            ->editColumn('created_at',function($row){

                return $row->created_at->format('d-m-Y');

            })

            ->addColumn('nama_faskes',function($row){

                return $row->namaFaskes;

            })

            ->addColumn('status_badge',function($row){

                return '<span class="badge bg-warning">Terkirim</span>';

            })

            ->addColumn('action',function($row){

                return '

                <a
                    href="'.route('newlplpo.verifikasi.detail',$row->id).'"
                    class="btn btn-primary btn-sm">

                    <i class="bi bi-eye"></i>

                </a>

                ';

            })

            ->rawColumns([
                'status_badge',
                'action'
            ])

            ->make(true);

    }

    /**
     * ==========================================================
     * Detail laporan
     * ==========================================================
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
    ->first();

    return view('newlplpo.verifikasi.detail', compact(
        'report',
        'faskes',
        'items'
    ));
}
    /**
     * ==========================================================
     * Terima laporan
     * ==========================================================
     */
    public function approve($id)
    {

        $report = Report::findOrFail($id);

        DB::transaction(function() use($report){

            $report->update([

                'report_status'=>'verified'

            ]);

        });

        return response()->json([

            'success'=>true,

            'message'=>'Laporan berhasil diverifikasi.'

        ]);

    }

    /**
     * ==========================================================
     * Tolak laporan
     * ==========================================================
     */
    public function reject(Request $request,$id)
    {

        $request->validate([

            'note'=>'required'

        ]);

        DB::transaction(function() use($request,$id){

            $report = Report::findOrFail($id);

            $report->update([

                'report_status'=>'rejected'

            ]);

            ReportNote::create([

                'report_id'=>$report->id,

                'note_type'=>'rejected',

                'note'=>$request->note,

                'created_by'=>Auth::id()

            ]);

        });

        return response()->json([

            'success'=>true,

            'message'=>'Laporan berhasil ditolak.'

        ]);

    }

}
