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
use App\Models\NewLplpo\InfoLinkApproval;

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

    $query = Report::query()

        ->join(
            'master_faskes',
            'master_faskes.kodeFaskes',
            '=',
            'new_lplpo_reports.kode_faskes'
        )

        ->where(
            'report_status',
            'SUBMITED'
        )

        ->where(
            'master_faskes.kodeKabupaten',
            $kodeKabupaten
        )

        ->when(
            $request->bulan,
            function ($q) use ($request) {

                $q->where(
                    'bulan',
                    $request->bulan
                );

            }
        )

        ->when(
            $request->tahun,
            function ($q) use ($request) {

                $q->where(
                    'tahun',
                    $request->tahun
                );

            }
        )

        ->select(
            'new_lplpo_reports.*',
            'master_faskes.namaFaskes'
        )

        ->with([
            'linkApproval'
        ])

        ->withCount('items');


    return DataTables::of($query)

        ->addIndexColumn()


        /*
        |--------------------------------------------------------------------------
        | CREATED AT
        |--------------------------------------------------------------------------
        */

        ->editColumn(
            'created_at',
            function ($row) {

                return $row->created_at
                    ? $row->created_at->format('d-m-Y')
                    : '-';

            }
        )


        /*
        |--------------------------------------------------------------------------
        | NAMA FASKES
        |--------------------------------------------------------------------------
        */

        ->addColumn(
            'nama_faskes',
            function ($row) {

                return $row->namaFaskes;

            }
        )


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        ->addColumn(
            'status_badge',
            function ($row) {

                return '
                    <span class="badge bg-warning">
                        Terkirim
                    </span>
                ';

            }
        )


        /*
        |--------------------------------------------------------------------------
        | QR CODE APPROVAL KAPUS
        |--------------------------------------------------------------------------
        */

        ->addColumn(
            'qr_code',
            function ($row) {

                $approval = $row->linkApproval;

                /*
                 * Belum ada approval
                 */
                if (
                    !$approval ||
                    empty($approval->verificationToken)
                ) {

                    return '
                        <span class="badge bg-secondary">
                            -
                        </span>
                    ';

                }


                /*
                 * URL verifikasi publik
                 */
                $verificationUrl = route(
                    'newlplpo.approval.verify',
                    $approval->verificationToken
                );


                return '
                    <div class="text-center">

                        <div
                            class="qr-code"
                            data-url="' .
                            e($verificationUrl)
                            . '"
                            style="
                                width:100px;
                                height:100px;
                                margin:auto;
                            ">
                        </div>

                        <small class="text-muted">
                            Scan QR
                        </small>

                    </div>
                ';

            }
        )


        /*
        |--------------------------------------------------------------------------
        | ACTION
        |--------------------------------------------------------------------------
        */

        ->addColumn(
            'action',
            function ($row) {

                return '

                    <a
                        href="' .
                        route(
                            'newlplpo.verifikasi.detail',
                            $row->id
                        ) .
                        '"
                        class="btn btn-primary btn-sm"
                        title="Detail">

                        <i class="bi bi-eye"></i>

                    </a>

                ';

            }
        )


        ->rawColumns([
            'status_badge',
            'qr_code',
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
    $report = Report::with('kunjungan')
        ->findOrFail($id);

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
