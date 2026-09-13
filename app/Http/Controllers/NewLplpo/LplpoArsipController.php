<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use App\Models\NewLplpo\Report;
use App\Models\NewLplpo\Item;
use App\Models\Master\MasterFaskes;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LplpoArsipController extends Controller
{
    /**
     * Halaman Arsip
     */
    public function index()
    {
        return view('newlplpo.arsip.index');
    }

    /**
     * Datatable
     */
   /**
 * Datatable
 */
public function datatable(Request $request)
{
    $query = Report::query()
        ->where('report_status', 'FINAL');


    /*
    |--------------------------------------------------------------------------
    | FILTER FASKES USER
    |--------------------------------------------------------------------------
    |
    | Group 3, 4, 5 hanya dapat melihat
    | arsip LPLPO dari faskes miliknya.
    |
    */

    $user = auth()->user();

    if (
        in_array(
            (int) $user->groupid,
            [3, 4, 5],
            true
        )
    ) {

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
        | CREATED AT
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
        | TOTAL ITEM
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
                <a href="' . route(
                    'newlplpo.arsip.detail',
                    $row->id
                ) . '"
                    class="btn btn-primary btn-sm"
                    title="Detail">

                    <i class="bi bi-eye"></i>

                </a>

                <a href="' . route(
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
     * Detail Arsip
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

        return view('newlplpo.arsip.detail', compact(
            'report',
            'items',
            'faskes'
        ));
    }

    /**
     * Print Laporan
     */
    public function print($id)
    {
        // Tahap berikutnya
    }
}
