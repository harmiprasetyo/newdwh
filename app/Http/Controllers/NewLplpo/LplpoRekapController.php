<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\NewLplpo\LplpoRekapExport;
use App\Models\Master\MasterFaskes;
use App\Models\NewLplpo\StokMinimalObat;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LplpoRekapController extends Controller
{

private function buildRekapData(Request $request): array
{
    $user = auth()->user();

    $groupId = (int) $user->groupid;

    $bulanMulai = (int) $request->input(
        'bulan_mulai',
        now()->month
    );

    $tahunMulai = (int) $request->input(
        'tahun_mulai',
        now()->year
    );

    $bulanSampai = (int) $request->input(
        'bulan_sampai',
        now()->month
    );

    $tahunSampai = (int) $request->input(
        'tahun_sampai',
        now()->year
    );

    if (
        $bulanMulai < 1 ||
        $bulanMulai > 12 ||
        $bulanSampai < 1 ||
        $bulanSampai > 12
    ) {
        abort(422, 'Bulan periode tidak valid.');
    }

    if (
        $tahunMulai < 2000 ||
        $tahunMulai > 2100 ||
        $tahunSampai < 2000 ||
        $tahunSampai > 2100
    ) {
        abort(422, 'Tahun periode tidak valid.');
    }

    $periodeMulai =
        ($tahunMulai * 100) + $bulanMulai;

    $periodeSampai =
        ($tahunSampai * 100) + $bulanSampai;

    if ($periodeMulai > $periodeSampai) {
        abort(
            422,
            'Periode mulai tidak boleh lebih besar dari periode sampai.'
        );
    }

    if (!in_array($groupId, [2, 3, 5])) {
        abort(
            403,
            'Anda tidak memiliki akses ke halaman rekap LPLPO.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BASE QUERY REPORT
    |--------------------------------------------------------------------------
    */

    $reportQuery = DB::table('new_lplpo_reports as r')
        ->leftJoin(
            'master_faskes as f',
            'f.kodeFaskes',
            '=',
            'r.kode_faskes'
        )
        ->where(
            'r.report_status',
            'FINAL'
        );

    /*
    |--------------------------------------------------------------------------
    | FILTER PERIODE
    |--------------------------------------------------------------------------
    */

    $reportQuery->where(function ($query) use (
        $tahunMulai,
        $bulanMulai,
        $tahunSampai,
        $bulanSampai
    ) {

        if ($tahunMulai === $tahunSampai) {

            $query
                ->where(
                    'r.tahun',
                    $tahunMulai
                )
                ->whereBetween(
                    'r.bulan',
                    [
                        $bulanMulai,
                        $bulanSampai
                    ]
                );

            return;
        }

        $query->where(function ($q) use (
            $tahunMulai,
            $bulanMulai
        ) {

            $q->where(
                'r.tahun',
                $tahunMulai
            )->where(
                'r.bulan',
                '>=',
                $bulanMulai
            );
        });

        if (($tahunSampai - $tahunMulai) > 1) {

            $query->orWhereBetween(
                'r.tahun',
                [
                    $tahunMulai + 1,
                    $tahunSampai - 1
                ]
            );
        }

        $query->orWhere(function ($q) use (
            $tahunSampai,
            $bulanSampai
        ) {

            $q->where(
                'r.tahun',
                $tahunSampai
            )->where(
                'r.bulan',
                '<=',
                $bulanSampai
            );
        });
    });

    /*
    |--------------------------------------------------------------------------
    | FILTER GROUP
    |--------------------------------------------------------------------------
    */

    if ($groupId === 2) {

        $reportQuery->where(
            'f.kodeKabupaten',
            $user->kodeKota
        );

        if ($request->filled('kode_faskes')) {

            $reportQuery->where(
                'r.kode_faskes',
                $request->kode_faskes
            );
        }

    } elseif (
        $groupId === 3 ||
        $groupId === 5
    ) {

        $reportQuery->where(
            'r.kode_faskes',
            $user->kodeFaskes
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REPORT ID
    |--------------------------------------------------------------------------
    */

    $reportIds = (clone $reportQuery)
        ->pluck('r.id');

    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    $header = (clone $reportQuery)
        ->select(
            'r.kode_faskes',
            'r.nama_faskes'
        )
        ->distinct()
        ->orderBy(
            'r.nama_faskes'
        )
        ->get();

    /*
    |--------------------------------------------------------------------------
    | ITEM
    |--------------------------------------------------------------------------
    */

    $items = collect();

    if ($reportIds->isNotEmpty()) {

        $stokMinimal = DB::table(
            'master_stokminimal_obat'
        )
            ->select(
                'kode_obat',
                'kodeFaskes',
                'tahun',
                DB::raw(
                    'MAX(obat_esensial) as obat_esensial'
                ),
                DB::raw(
                    'MAX(obat_formularium_puskesmas)
                     as obat_formularium_puskesmas'
                )
            )
            ->groupBy(
                'kode_obat',
                'kodeFaskes',
                'tahun'
            );

        $items = DB::table(
            'new_lplpo_itemlist as i'
        )

            ->leftJoin(
                'new_lplpo_program_list as p',
                'p.id',
                '=',
                'i.program_id'
            )

            ->leftJoin(
                'master_obat as mo',
                'mo.kode_obat',
                '=',
                'i.kode_obat'
            )

            ->leftJoin(
                'new_lplpo_reports as r',
                'r.id',
                '=',
                'i.report_id'
            )

            ->leftJoinSub(
                $stokMinimal,
                'ms',
                function ($join) {

                    $join->on(
                        'ms.kode_obat',
                        '=',
                        'i.kode_obat'
                    )
                    ->on(
                        'ms.kodeFaskes',
                        '=',
                        'r.kode_faskes'
                    )
                    ->on(
                        'ms.tahun',
                        '=',
                        'r.tahun'
                    );
                }
            )

            ->whereIn(
                'i.report_id',
                $reportIds
            )

            ->select(

                'i.program_id',

                DB::raw(
                    'COALESCE(
                        p.program_name,
                        i.program_name,
                        "Non Program"
                    ) as program_name'
                ),

                'i.kode_obat',

                'i.nama_obat',

                'i.satuan',

                DB::raw(
                    'COALESCE(
                        mo.obat_napza,
                        "tidak"
                    ) as obat_napza'
                ),

                DB::raw(
                    'COALESCE(
                        ms.obat_esensial,
                        "noe"
                    ) as obat_esensial'
                ),

                DB::raw(
                    'COALESCE(
                        ms.obat_formularium_puskesmas,
                        "false"
                    ) as obat_formularium_puskesmas'
                ),

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.stok_awal_progam_pkd,
                            0
                        )
                    ) as stok_awal_program_pkd'
                ),

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.stok_awal_jkn,
                            0
                        )
                    ) as stok_awal_jkn'
                ),

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.penerimaan_program_pkd,
                            0
                        )
                    ) as penerimaan_program_pkd'
                ),

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.penerimaan_jkn,
                            0
                        )
                    ) as penerimaan_jkn'
                ),

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.persediaan_program_pkd,
                            0
                        )
                    ) as persediaan_program_pkd'
                ),

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.persediaan_jkn,
                            0
                        )
                    ) as persediaan_jkn'
                ),

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.pemakaian_program_pkd,
                            0
                        )
                    ) as pemakaian_program_pkd'
                ),

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.pemakaian_jkn,
                            0
                        )
                    ) as pemakaian_jkn'
                ),

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.item_expired_pkd,
                            0
                        )
                    ) as item_expired_pkd'
                ),

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.item_expired_jkn,
                            0
                        )
                    ) as item_expired_jkn'
                ),

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.stok_akhir_program_pkd,
                            0
                        )
                    ) as stok_akhir_program_pkd'
                ),

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.stok_akhir_jkn,
                            0
                        )
                    ) as stok_akhir_jkn'
                ),

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.permintaan,
                            0
                        )
                    ) as permintaan'
                ),

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.pemberian_program_pkd,
                            0
                        )
                    ) as pemberian_program_pkd'
                ),

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.pemberian_jkn,
                            0
                        )
                    ) as pemberian_jkn'
                )
            )

            ->groupBy(
                'i.program_id',
                'p.program_name',
                'i.program_name',
                'i.kode_obat',
                'i.nama_obat',
                'i.satuan',
                'mo.obat_napza',
                'ms.obat_esensial',
                'ms.obat_formularium_puskesmas'
            )

            /*
            |--------------------------------------------------------------------------
            | NON PROGRAM PALING ATAS
            |--------------------------------------------------------------------------
            */

            ->orderByRaw("
                CASE
                    WHEN i.program_id = 1 THEN 0
                    WHEN LOWER(
                        TRIM(
                            COALESCE(
                                p.program_name,
                                i.program_name,
                                'Non Program'
                            )
                        )
                    ) = 'non program' THEN 0
                    ELSE 1
                END
            ")

            ->orderBy(
                'program_name'
            )

            ->orderBy(
                'i.nama_obat'
            )

            ->get();
    }

    return [
        'groupId'       => $groupId,
        'bulanMulai'    => $bulanMulai,
        'tahunMulai'    => $tahunMulai,
        'bulanSampai'   => $bulanSampai,
        'tahunSampai'   => $tahunSampai,
        'reportIds'     => $reportIds,
        'header'        => $header,
        'items'         => $items,
    ];
}

/**
     * ==========================================================
     * INDEX
     * ==========================================================
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $groupId = (int) $user->groupid;

        /*
        |--------------------------------------------------------------------------
        | DEFAULT PERIODE
        |--------------------------------------------------------------------------
        */

        $bulanMulai = (int) $request->input(
            'bulan_mulai',
            now()->month
        );

        $tahunMulai = (int) $request->input(
            'tahun_mulai',
            now()->year
        );

        $bulanSampai = (int) $request->input(
            'bulan_sampai',
            now()->month
        );

        $tahunSampai = (int) $request->input(
            'tahun_sampai',
            now()->year
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI BULAN
        |--------------------------------------------------------------------------
        */

        if (
            $bulanMulai < 1 ||
            $bulanMulai > 12 ||
            $bulanSampai < 1 ||
            $bulanSampai > 12
        ) {
            abort(
                422,
                'Bulan periode tidak valid.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI TAHUN
        |--------------------------------------------------------------------------
        */

        if (
            $tahunMulai < 2000 ||
            $tahunMulai > 2100 ||
            $tahunSampai < 2000 ||
            $tahunSampai > 2100
        ) {
            abort(
                422,
                'Tahun periode tidak valid.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI PERIODE
        |--------------------------------------------------------------------------
        */

        $periodeMulai =
            ($tahunMulai * 100) + $bulanMulai;

        $periodeSampai =
            ($tahunSampai * 100) + $bulanSampai;

        if ($periodeMulai > $periodeSampai) {

            abort(
                422,
                'Periode mulai tidak boleh lebih besar dari periode sampai.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI GROUP
        |--------------------------------------------------------------------------
        */

        if (!in_array($groupId, [2, 3, 5])) {

            abort(
                403,
                'Anda tidak memiliki akses ke halaman rekap LPLPO.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATA FASKES
        |--------------------------------------------------------------------------
        */

        $faskes = collect();


        if ($groupId === 2) {

            $faskes = DB::table('master_faskes')
                ->where(
                    'kodeKabupaten',
                    $user->kodeKota
                )
                ->orderBy('namaFaskes')
                ->get([
                    'kodeFaskes',
                    'namaFaskes'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'newlplpo.rekap.index',
            compact(
                'groupId',
                'bulanMulai',
                'tahunMulai',
                'bulanSampai',
                'tahunSampai',
                'faskes'
            )
        );
    }


    /**
     * ==========================================================
     * DATA REKAP
     * ==========================================================
     */
    public function data(Request $request)
{
    try {

        $data = $this->buildRekapData($request);

        return response()->json([

            'success' => true,

            'bulan_mulai' =>
                $data['bulanMulai'],

            'tahun_mulai' =>
                $data['tahunMulai'],

            'bulan_sampai' =>
                $data['bulanSampai'],

            'tahun_sampai' =>
                $data['tahunSampai'],

            'periode' =>
                sprintf(
                    '%02d/%04d - %02d/%04d',
                    $data['bulanMulai'],
                    $data['tahunMulai'],
                    $data['bulanSampai'],
                    $data['tahunSampai']
                ),

            'group_id' =>
                $data['groupId'],

            'header' =>
                $data['header'],

            'jumlah_laporan' =>
                $data['reportIds']->count(),

            'jumlah_item' =>
                $data['items']->count(),

            'items' =>
                $data['items'],
        ]);

    } catch (\Throwable $e) {

        return response()->json([

            'success' => false,

            'message' =>
                $e->getMessage(),

        ], $e->getCode() >= 400 &&
           $e->getCode() < 600
            ? $e->getCode()
            : 500);
    }
}


public function exportExcel(Request $request)
{
    $data = $this->buildRekapData($request);

    $filename = sprintf(
        'Rekap-LPLPO-%02d-%04d-sd-%02d-%04d.xlsx',
        $data['bulanMulai'],
        $data['tahunMulai'],
        $data['bulanSampai'],
        $data['tahunSampai']
    );

    return Excel::download(
        new LplpoRekapExport(
            $data['items'],
            $data['bulanMulai'],
            $data['tahunMulai'],
            $data['bulanSampai'],
            $data['tahunSampai']
        ),
        $filename
    );
}

public function exportPdf(Request $request)
{
    $data = $this->buildRekapData($request);

    $pdf = Pdf::loadView(
        'newlplpo.rekap.pdf',
        [
            'items' => $data['items'],
            'header' => $data['header'],
            'bulanMulai' => $data['bulanMulai'],
            'tahunMulai' => $data['tahunMulai'],
            'bulanSampai' => $data['bulanSampai'],
            'tahunSampai' => $data['tahunSampai'],
        ]
    )->setPaper(
        'a4',
        'landscape'
    );

    $filename = sprintf(
        'Rekap-LPLPO-%02d-%04d-sd-%02d-%04d.pdf',
        $data['bulanMulai'],
        $data['tahunMulai'],
        $data['bulanSampai'],
        $data['tahunSampai']
    );

    return $pdf->download($filename);
}
}
