<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;

use App\Models\NewLplpo\Report;
use App\Models\NewLplpo\Item;
use App\Models\NewLplpo\Program;

use App\Models\Master\MasterFaskes;

use App\Services\NewLplpo\ReportService;
use App\Services\NewLplpo\ItemService;
use App\Services\NewLplpo\ApprovalService;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use Yajra\DataTables\Facades\DataTables;


class LplpoController extends Controller
{

    protected ReportService $service;

    protected ItemService $itemService;

    protected ApprovalService $approvalService;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function __construct(
        ReportService $service,
        ItemService $itemService,
        ApprovalService $approvalService
    ) {

        $this->service =
            $service;

        $this->itemService =
            $itemService;

        $this->approvalService =
            $approvalService;

    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD / LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {

        $reports =
            $this->service->list($request);


        return view(
            'newlplpo.listlplpo',
            compact('reports')
        );

    }


    /*
    |--------------------------------------------------------------------------
    | BUAT LPLPO
    |--------------------------------------------------------------------------
    */

    public function create()
    {

        $programs =
            Program::orderBy('program_name')
                ->get();


        $kodeFaskes =
            session('kodeFaskes');


        $faskes =
            MasterFaskes::with([
                'type',
                'provinsi',
                'kota',
                'kecamatan'
            ])
            ->where(
                'kodeFaskes',
                $kodeFaskes
            )
            ->firstOrFail();


        return view(
            'newlplpo.buatlplpo',
            [

                'programs' =>
                    $programs,

                'nomorLplpo' =>
                    'LPLPO-'
                    . $kodeFaskes
                    . '-'
                    . Carbon::now()->format(
                        'YdmHis'
                    ),

                'faskes' =>
                    $faskes

            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | STORE REPORT
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ) {

        $kodeFaskes =
            session('kodeFaskes');


        $namaFaskes =
            MasterFaskes::where(
                'kodeFaskes',
                $kodeFaskes
            )
            ->value('namaFaskes');


        $request->validate([

            'bulan' => [
                'required',
                'integer',
                'min:1',
                'max:12'
            ],

            'tahun' => [
                'required',
                'integer',
                'min:2000',
                'max:2100'
            ],

            'nomor_lplpo' => [
                'required',
                'string',
                'max:255'
            ]

        ]);


        $data = [

            'kode_faskes' =>
                $kodeFaskes,

            'nama_faskes' =>
                $namaFaskes,

            'bulan' =>
                $request->bulan,

            'tahun' =>
                $request->tahun,

            'nomor_lplpo' =>
                $request->nomor_lplpo,

        ];


        $report =
            $this->service->create(
                $data
            );


        return redirect()
            ->route(
                'newlplpo.edit',
                $report->id
            )
            ->with(
                'success',
                'Laporan berhasil dibuat.'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | EDIT REPORT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {

        $report =
            Report::with([
                'linkApproval.kapus',
                'kunjungan',
            ])
            ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | COPY ITEM BULAN SEBELUMNYA
        |--------------------------------------------------------------------------
        |
        | Tetap dilakukan untuk DRAFT / REJECTED jika belum mempunyai item.
        |
        */

        if (
            in_array(
                $report->report_status,
                ['DRAFT', 'REJECTED'],
                true
            )
            &&
            !Item::where(
                'report_id',
                $report->id
            )->exists()
        ) {

            $this->itemService
                ->copyPreviousMonthItems(
                    $report->id
                );

        }


        /*
        |--------------------------------------------------------------------------
        | ITEMS
        |--------------------------------------------------------------------------
        */

        $items =
            $this->itemService
                ->listByReport($id);


        /*
        |--------------------------------------------------------------------------
        | PROGRAMS
        |--------------------------------------------------------------------------
        */

        $programs =
            Program::orderBy(
                'id'
            )->get();


        /*
        |--------------------------------------------------------------------------
        | FASKES
        |--------------------------------------------------------------------------
        */

        $faskes =
            MasterFaskes::with([
                'type',
                'provinsi',
                'kota',
                'kecamatan'
            ])
            ->where(
                'kodeFaskes',
                $report->kode_faskes
            )
            ->firstOrFail();


        return view(
            'newlplpo.buatlplpo',
            [

                'report' =>
                    $report,

                'items' =>
                    $items,

                'programs' =>
                    $programs,

                'faskes' =>
                    $faskes,

                'nomorLplpo' =>
                    $report->nomor_lplpo,

                'mode' =>
                    'edit'

            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE HEADER
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $report =
            Report::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | JANGAN BOLEH SUBMIT LANGSUNG
        |--------------------------------------------------------------------------
        |
        | Sebelumnya:
        |
        | report_status = SUBMITED
        |
        | Sekarang proses tersebut dilakukan oleh ApprovalService::approve()
        |
        */

        if (
            $request->has('report_status') &&
            $request->report_status === 'SUBMITED'
        ) {

            return response()->json(
                [

                    'success' => false,

                    'message' =>
                        'Laporan harus dikirim melalui proses approval Kepala Puskesmas.'

                ],
                422
            );

        }


        /*
        |--------------------------------------------------------------------------
        | STATUS LOCK
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $report->report_status,
                ['DRAFT', 'REJECTED'],
                true
            )
        ) {

            if ($request->ajax()) {

                return response()->json(
                    [

                        'success' => false,

                        'message' =>
                            'Laporan tidak dapat diubah karena status laporan adalah '
                            . $report->report_status . '.'

                    ],
                    422
                );

            }


            return back()
                ->with(
                    'error',
                    'Laporan tidak dapat diubah karena status laporan adalah '
                    . $report->report_status . '.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE HEADER
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'bulan' => [
                'required',
                'integer',
                'min:1',
                'max:12'
            ],

            'tahun' => [
                'required',
                'integer',
                'min:2000',
                'max:2100'
            ]

        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $report =
            $this->service->update(
                $report,
                [

                    'bulan' =>
                        $request->bulan,

                    'tahun' =>
                        $request->tahun,

                ]
            );


        /*
        |--------------------------------------------------------------------------
        | AJAX
        |--------------------------------------------------------------------------
        */

        if ($request->ajax()) {

            return response()->json(
                [

                    'success' => true,

                    'message' =>
                        'Header berhasil diupdate.',

                    'data' =>
                        $report

                ]
            );

        }


        return back()
            ->with(
                'success',
                'Data berhasil diupdate.'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | KIRIM / KIRIM ULANG APPROVAL
    |--------------------------------------------------------------------------
    */

    public function resubmitApproval(
        $id
    ) {

        try {

            $report =
                Report::with([
                    'kunjungan',
                    'items',
                ])
                ->findOrFail($id);


            /*
            |--------------------------------------------------------------------------
            | HANYA DRAFT / REJECTED
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $report->report_status,
                    ['DRAFT', 'REJECTED'],
                    true
                )
            ) {

                return response()->json(
                    [

                        'success' => false,

                        'message' =>
                            'Laporan tidak dapat dikirim untuk approval karena status laporan adalah '
                            . $report->report_status . '.'

                    ],
                    422
                );

            }


            /*
            |--------------------------------------------------------------------------
            | PROSES APPROVAL
            |--------------------------------------------------------------------------
            */

            $approval =
                $this->approvalService
                    ->send($report);


            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json(
                [

                    'success' => true,

                    'message' =>
                        $report->report_status === 'REJECTED'
                            ? 'Laporan berhasil diperbaiki dan dikirim kembali untuk approval Kepala Puskesmas.'
                            : 'Laporan berhasil dikirim untuk approval Kepala Puskesmas.',

                    'data' => [

                        'report_id' =>
                            $report->id,

                        'report_status' =>
                            $approval->report->fresh()->report_status,

                        'lplpo_status' =>
                            $approval->report->fresh()->lplpo_status,

                        'approval_id' =>
                            $approval->id,

                    ]

                ]
            );

        }


        catch (ValidationException $e) {

            return response()->json(
                [

                    'success' => false,

                    'message' =>
                        $e->getMessage(),

                    'errors' =>
                        $e->errors()

                ],
                422
            );

        }


        catch (\Throwable $e) {

            report($e);


            return response()->json(
                [

                    'success' => false,

                    'message' =>
                        'Terjadi kesalahan saat mengirim approval.'

                ],
                500
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */

    public function detail($id)
    {

        $report =
            Report::findOrFail($id);


        $items =
            $this->itemService
                ->listByReport($id);


        $faskes =
            MasterFaskes::with([
                'type',
                'provinsi',
                'kota',
                'kecamatan'
            ])
            ->where(
                'kodeFaskes',
                $report->kode_faskes
            )
            ->firstOrFail();


        return view(
            'newlplpo.detail',
            [

                'report' =>
                    $report,

                'items' =>
                    $items,

                'faskes' =>
                    $faskes

            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LAPORAN
    |--------------------------------------------------------------------------
    */

    public function laporan()
    {

        return view(
            'newlplpo.laporan'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LAPORAN DATATABLE
    |--------------------------------------------------------------------------
    */

    public function laporanDatatable(
        Request $request
    ) {

        $request->merge([

            'bulan' =>
                $request->bulan
                ?? Carbon::now()->month,

            'tahun' =>
                $request->tahun
                ?? Carbon::now()->year,

        ]);


         /*
    |--------------------------------------------------------------------------
    | FILTER FASKES USER
    |--------------------------------------------------------------------------
    */

    $user = auth()->user();

    if (
        in_array(
            (int) $user->groupid,
            [3, 4, 5],
            true
        )
    ) {

        $request->merge([
            'kode_faskes' => $user->kodeFaskes,
        ]);

    }



        $data =
            $this->service->laporan(
                $request
            );


        return DataTables::of($data)

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
                        ? $row->created_at->format(
                            'd-m-Y H:i'
                        )
                        : '-';

                }
            )


            /*
            |--------------------------------------------------------------------------
            | STATUS BADGE
            |--------------------------------------------------------------------------
            */

            ->addColumn(
                'status_badge',
                function ($row) {

                    $color =
                        match ($row->report_status) {

                            'DRAFT' =>
                                'warning',

                            'SUBMITED' =>
                                'info',

                            'VERIFIED' =>
                                'primary',

                            'REJECTED' =>
                                'danger',

                            'FINAL' =>
                                'success',

                            default =>
                                'dark'

                        };


                    $status =
                        match ($row->report_status) {

                            'DRAFT' =>
                                'DRAFT',

                            'SUBMITED' =>
                                'TERKIRIM',

                            'VERIFIED' =>
                                'TERVERIFIKASI',

                            'REJECTED' =>
                                'DITOLAK',

                            'FINAL' =>
                                'SELESAI',

                            default =>
                                'NEW'

                        };


                    return
                        '<span class="badge bg-'
                        . $color
                        . '">'
                        . $status
                        . '</span>';

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

                    /*
                    |--------------------------------------------------------------------------
                    | DRAFT
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $row->report_status === 'DRAFT'
                    ) {

                        return
                            '<a href="'
                            . route(
                                'newlplpo.edit',
                                $row->id
                            )
                            . '" class="btn btn-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <button
                                class="btn btn-danger btn-sm btnDelete"
                                data-id="'
                            . $row->id
                            . '"
                                title="Hapus">

                                <i class="bi bi-trash"></i>

                            </button>';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | REJECTED
                    |--------------------------------------------------------------------------
                    |
                    | Bisa diperbaiki.
                    | Tidak boleh dihapus dari sini.
                    |
                    */

                    if (
                        $row->report_status === 'REJECTED'
                    ) {

                        return
                            '<a href="'
                            . route(
                                'newlplpo.edit',
                                $row->id
                            )
                            . '" class="btn btn-warning btn-sm" title="Perbaiki">
                                <i class="bi bi-pencil-square"></i>
                            </a>';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | LOCKED
                    |--------------------------------------------------------------------------
                    */

                    return
                        '<a href="'
                        . route(
                            'newlplpo.detail',
                            $row->id
                        )
                        . '" class="btn btn-primary btn-sm" title="Detail">

                            <i class="bi bi-eye"></i>

                        </a>';

                }
            )


            ->rawColumns([
                'status_badge',
                'action'
            ])

            ->make(true);

    }


    /*
    |--------------------------------------------------------------------------
    | ITEMS
    |--------------------------------------------------------------------------
    */

    public function items($reportId)
    {

        $items =
            $this->itemService
                ->listByReport(
                    $reportId
                );


        return view(
            'newlplpo.partials.items_table',
            compact('items')
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {

        $report =
            Report::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | HANYA DRAFT YANG BOLEH DIHAPUS
        |--------------------------------------------------------------------------
        */

        if (
            $report->report_status !== 'DRAFT'
        ) {

            return response()->json(
                [

                    'success' => false,

                    'message' =>
                        'Laporan tidak dapat dihapus karena status laporan bukan DRAFT.'

                ],
                422
            );

        }


        $this->service->delete(
            $report
        );


        return response()->json(
            [

                'success' => true,

                'message' =>
                    'Laporan berhasil dihapus.'

            ]
        );

    }


     public function pemberian()
    {

        return view(

            'newlplpo.pemberian'

        );

    }


    /*
    |--------------------------------------------------------------------------
    | STORE ITEM
    |--------------------------------------------------------------------------
    |
    | PENTING:
    | Method storeItem(), updateItem(), destroyItem(), dll
    | yang sudah ada di controller Anda tetap dipakai.
    |
    | Jangan mengubah implementasinya jika saat ini sudah berjalan.
    |
    */
}
