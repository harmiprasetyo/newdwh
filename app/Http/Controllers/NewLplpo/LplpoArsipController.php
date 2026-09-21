<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use App\Models\NewLplpo\Report;
use App\Models\NewLplpo\Item;
use App\Models\Master\MasterFaskes;
use App\Models\NewLplpo\StokMinimalObat;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\NewLplpo\LplpoDetailExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LplpoArsipController extends Controller
{
    /**
     * ==========================================================
     * INDEX
     * ==========================================================
     */
    public function index()
    {
        return view('newlplpo.arsip.index');
    }


    /**
     * ==========================================================
     * DATATABLE
     * ==========================================================
     */
    public function datatable(Request $request)
    {
        $query = Report::query()
            ->where('report_status', 'FINAL');

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | FILTER BERDASARKAN GROUP USER
        |--------------------------------------------------------------------------
        */
        if (in_array((int) $user->groupid, [3, 4, 5], true)) {

            $query->where(
                'kode_faskes',
                $user->kodeFaskes
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER BULAN
        |--------------------------------------------------------------------------
        */
        if ($request->filled('bulan')) {

            $query->where(
                'bulan',
                $request->bulan
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER TAHUN
        |--------------------------------------------------------------------------
        */
        if ($request->filled('tahun')) {

            $query->where(
                'tahun',
                $request->tahun
            );
        }


        return DataTables::of($query)

            ->addIndexColumn()


            /*
            |--------------------------------------------------------------------------
            | TANGGAL
            |--------------------------------------------------------------------------
            */
            ->editColumn('created_at', function ($row) {

                return optional(
                    $row->created_at
                )->format('d-m-Y H:i');
            })


            /*
            |--------------------------------------------------------------------------
            | NAMA FASKES
            |--------------------------------------------------------------------------
            */
            ->addColumn('nama_faskes', function ($row) {

                return optional(
                    MasterFaskes::where(
                        'kodeFaskes',
                        $row->kode_faskes
                    )->first()
                )->namaFaskes;
            })


            /*
            |--------------------------------------------------------------------------
            | JUMLAH ITEM
            |--------------------------------------------------------------------------
            */
            ->addColumn('items_count', function ($row) {

                return Item::where(
                    'report_id',
                    $row->id
                )->count();
            })


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */
            ->addColumn('status', function () {

                return '<span class="badge bg-success">
                            SELESAI
                        </span>';
            })


            /*
            |--------------------------------------------------------------------------
            | AKSI
            |--------------------------------------------------------------------------
            */
            ->addColumn('aksi', function ($row) {

                return '
                    <a href="' .
                        route(
                            'newlplpo.arsip.detail',
                            $row->id
                        ) . '"
                        class="btn btn-primary btn-sm"
                        title="Detail">

                        <i class="bi bi-eye"></i>

                    </a>

                    <a href="' .
                        route(
                            'newlplpo.arsip.print',
                            $row->id
                        ) . '"
                        class="btn btn-success btn-sm"
                        title="Print">

                        <i class="bi bi-printer"></i>

                    </a>
                ';
            })


            ->rawColumns([
                'status',
                'aksi'
            ])

            ->make(true);
    }


    /**
     * ==========================================================
     * DETAIL
     * ==========================================================
     */
    public function detail($id)
    {
        /*
        |--------------------------------------------------------------------------
        | LOAD REPORT
        |--------------------------------------------------------------------------
        */
        $report = Report::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | LOAD ITEM
        |--------------------------------------------------------------------------
        |
        | Program:
        |   new_lplpo_program_list
        |
        | Obat:
        |   master_obat
        |
        */
        $items = Item::with([
                'program',
                'obat',
            ])

            ->where(
                'report_id',
                $report->id
            )

            ->orderByRaw("
                CASE
                    WHEN program_id = 1 THEN 0
                    WHEN LOWER(TRIM(program_name)) = 'non program' THEN 0
                    ELSE 1
                END
            ")

            ->orderBy(
                'program_id'
            )

            ->orderBy(
                'nama_obat'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | LOAD STOK MINIMAL
        |--------------------------------------------------------------------------
        |
        | Jangan menggunakan relasi stokMinimal() langsung.
        |
        | Data harus dicari berdasarkan:
        |
        | kode_obat
        | kodeFaskes
        | tahun
        |
        */
        $stokMinimal = StokMinimalObat::query()

            ->where(
                'kodeFaskes',
                $report->kode_faskes
            )

            ->where(
                'tahun',
                $report->tahun
            )

            ->whereIn(
                'kode_obat',
                $items
                    ->pluck('kode_obat')
                    ->filter()
                    ->unique()
                    ->values()
            )

            ->get()

            ->keyBy(
                'kode_obat'
            );


        /*
        |--------------------------------------------------------------------------
        | ATTACH MASTER DATA KE ITEM
        |--------------------------------------------------------------------------
        |
        | Kita tidak mengubah struktur database.
        | Data hanya ditambahkan sebagai property runtime.
        |
        */
        $items->each(function ($item) use ($stokMinimal) {

            $stok = $stokMinimal->get(
                $item->kode_obat
            );


            $item->obat_esensial = optional(
                $stok
            )->obat_esensial;


            $item->obat_formularium_puskesmas = optional(
                $stok
            )->obat_formularium_puskesmas;


            /*
            |--------------------------------------------------------------------------
            | NAPZA
            |--------------------------------------------------------------------------
            |
            | Sumber:
            | master_obat.obat_napza
            |
            */
            $item->obat_napza = optional(
                $item->obat
            )->obat_napza;
        });


        /*
        |--------------------------------------------------------------------------
        | LOAD FASKES
        |--------------------------------------------------------------------------
        */
        $faskes = MasterFaskes::with([
            'type',
            'provinsi',
            'kota',
            'kecamatan'
        ])

        ->where(
            'kodeFaskes',
            $report->kode_faskes
        )

        ->first();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */
        return view(
            'newlplpo.arsip.detail',
            compact(
                'report',
                'items',
                'faskes'
            )
        );
    }


    /**
     * ==========================================================
     * PRINT
     * ==========================================================
     */
    public function print($id)
    {
        // Akan kita implementasikan pada step export.
    }

    public function exportExcel($id)
{
    $report = Report::where('report_status', 'FINAL')
        ->findOrFail($id);

    $filename = 'LPLPO-' .
        ($report->nomor_lplpo ?: $report->id) .
        '.xlsx';

    return Excel::download(
        new LplpoDetailExport($report),
        $filename
    );
}


public function exportPdf($id)
{
    $report = Report::where('report_status', 'FINAL')
        ->findOrFail($id);

    $items = Item::with([
            'program',
            'obat',
        ])
        ->where('report_id', $report->id)
        ->orderByRaw("
            CASE
                WHEN program_id = 1 THEN 0
                WHEN LOWER(TRIM(program_name)) = 'non program' THEN 0
                ELSE 1
            END
        ")
        ->orderBy('program_id')
        ->orderBy('nama_obat')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | STOK MINIMAL / OE / FORMULARIUM
    |--------------------------------------------------------------------------
    */

    $kodeObat = $items
        ->pluck('kode_obat')
        ->filter()
        ->unique()
        ->values();

    $stokMinimal = collect();

    if ($kodeObat->isNotEmpty()) {
        $stokMinimal = StokMinimalObat::query()
            ->where('kodeFaskes', $report->kode_faskes)
            ->where('tahun', $report->tahun)
            ->whereIn('kode_obat', $kodeObat)
            ->get()
            ->keyBy('kode_obat');
    }

    $items->each(function ($item) use ($stokMinimal) {

        $stok = $stokMinimal->get($item->kode_obat);

        $item->obat_esensial =
            optional($stok)->obat_esensial;

        $item->obat_formularium_puskesmas =
            optional($stok)->obat_formularium_puskesmas;

        $item->obat_napza =
            optional($item->obat)->obat_napza;
    });

    /*
    |--------------------------------------------------------------------------
    | FASKES
    |--------------------------------------------------------------------------
    */

    $faskes = MasterFaskes::with([
        'type',
        'provinsi',
        'kota',
        'kecamatan',
    ])
        ->where('kodeFaskes', $report->kode_faskes)
        ->first();

    /*
    |--------------------------------------------------------------------------
    | PDF
    |--------------------------------------------------------------------------
    */

    $pdf = Pdf::loadView(
        'newlplpo.arsip.pdf',
        compact(
            'report',
            'items',
            'faskes'
        )
    )
        ->setPaper('a4', 'landscape');

    $filename = 'LPLPO-' .
        ($report->nomor_lplpo ?: $report->id) .
        '.pdf';

    return $pdf->download($filename);
}

}
