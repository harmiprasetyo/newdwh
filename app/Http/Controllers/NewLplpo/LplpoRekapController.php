<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LplpoRekapController extends Controller
{
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
        $user = auth()->user();

        $groupId = (int) $user->groupid;


        /*
        |--------------------------------------------------------------------------
        | PARAMETER PERIODE
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

            return response()->json([
                'success' => false,
                'message' => 'Bulan periode tidak valid.'
            ], 422);
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

            return response()->json([
                'success' => false,
                'message' => 'Tahun periode tidak valid.'
            ], 422);
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

            return response()->json([
                'success' => false,
                'message' =>
                    'Periode dari tidak boleh lebih besar dari periode sampai.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI GROUP
        |--------------------------------------------------------------------------
        */

        if (!in_array($groupId, [2, 3, 5])) {

            return response()->json([
                'success' => false,
                'message' => 'Group user tidak diperbolehkan.'
            ], 403);
        }


        /*
        |--------------------------------------------------------------------------
        | BASE QUERY REPORT
        |--------------------------------------------------------------------------
        */

        $reportQuery = DB::table(
            'new_lplpo_reports as r'
        )

            ->leftJoin(
                'master_faskes as f',
                'f.kodeFaskes',
                '=',
                'r.kode_faskes'
            )

            /*
            |--------------------------------------------------------------------------
            | HANYA FINAL
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | TAHUN SAMA
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | TAHUN MULAI
            |--------------------------------------------------------------------------
            */

            $query->where(function ($q) use (
                $tahunMulai,
                $bulanMulai
            ) {

                $q->where(
                    'r.tahun',
                    $tahunMulai
                )

                ->where(
                    'r.bulan',
                    '>=',
                    $bulanMulai
                );
            });


            /*
            |--------------------------------------------------------------------------
            | TAHUN TENGAH
            |--------------------------------------------------------------------------
            */

            if (
                ($tahunSampai - $tahunMulai) > 1
            ) {

                $query->orWhereBetween(
                    'r.tahun',
                    [
                        $tahunMulai + 1,
                        $tahunSampai - 1
                    ]
                );
            }


            /*
            |--------------------------------------------------------------------------
            | TAHUN SAMPAI
            |--------------------------------------------------------------------------
            */

            $query->orWhere(function ($q) use (
                $tahunSampai,
                $bulanSampai
            ) {

                $q->where(
                    'r.tahun',
                    $tahunSampai
                )

                ->where(
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

            /*
            |--------------------------------------------------------------------------
            | DINKES
            |--------------------------------------------------------------------------
            */

            $reportQuery->where(
                'f.kodeKabupaten',
                $user->kodeKota
            );


            /*
            |--------------------------------------------------------------------------
            | FILTER FASKES
            |--------------------------------------------------------------------------
            */

            if ($request->filled('kode_faskes')) {

                $reportQuery->where(
                    'r.kode_faskes',
                    $request->kode_faskes
                );
            }
        }


        elseif (
            $groupId === 3 ||
            $groupId === 5
        ) {

            /*
            |--------------------------------------------------------------------------
            | PUSKESMAS
            |--------------------------------------------------------------------------
            */

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
        | REKAP ITEM
        |--------------------------------------------------------------------------
        */

        $items = collect();


        if ($reportIds->isNotEmpty()) {

            /*
            |--------------------------------------------------------------------------
            | MASTER STOK MINIMAL
            |--------------------------------------------------------------------------
            |
            | Kita buat subquery terlebih dahulu agar join tidak
            | menggandakan baris item ketika data report memiliki
            | beberapa periode.
            |
            */

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

                /*
                |--------------------------------------------------------------------------
                | PROGRAM
                |--------------------------------------------------------------------------
                */

                ->leftJoin(
                    'new_lplpo_program_list as p',
                    'p.id',
                    '=',
                    'i.program_id'
                )

                /*
                |--------------------------------------------------------------------------
                | MASTER OBAT
                |--------------------------------------------------------------------------
                */

                ->leftJoin(
                    'master_obat as mo',
                    'mo.kode_obat',
                    '=',
                    'i.kode_obat'
                )

                /*
                |--------------------------------------------------------------------------
                | REPORT
                |--------------------------------------------------------------------------
                */

                ->leftJoin(
                    'new_lplpo_reports as r',
                    'r.id',
                    '=',
                    'i.report_id'
                )

                /*
                |--------------------------------------------------------------------------
                | STOK MINIMAL
                |--------------------------------------------------------------------------
                */

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

                /*
                |--------------------------------------------------------------------------
                | FILTER REPORT
                |--------------------------------------------------------------------------
                */

                ->whereIn(
                    'i.report_id',
                    $reportIds
                )

                /*
                |--------------------------------------------------------------------------
                | SELECT
                |--------------------------------------------------------------------------
                */

                ->select(

                    /*
                    |--------------------------------------------------------------------------
                    | PROGRAM
                    |--------------------------------------------------------------------------
                    */

                    'i.program_id',

                    DB::raw(
                        'COALESCE(
                            p.program_name,
                            i.program_name,
                            "Non Program"
                        ) as program_name'
                    ),


                    /*
                    |--------------------------------------------------------------------------
                    | IDENTITAS OBAT
                    |--------------------------------------------------------------------------
                    */

                    'i.kode_obat',

                    'i.nama_obat',

                    'i.satuan',


                    /*
                    |--------------------------------------------------------------------------
                    | NAPZA
                    |--------------------------------------------------------------------------
                    */

                    DB::raw(
                        'COALESCE(
                            mo.obat_napza,
                            "tidak"
                        ) as obat_napza'
                    ),


                    /*
                    |--------------------------------------------------------------------------
                    | ESENSIAL
                    |--------------------------------------------------------------------------
                    */

                    DB::raw(
                        'COALESCE(
                            ms.obat_esensial,
                            "noe"
                        ) as obat_esensial'
                    ),


                    /*
                    |--------------------------------------------------------------------------
                    | FORMULARIUM PKM
                    |--------------------------------------------------------------------------
                    */

                    DB::raw(
                        'COALESCE(
                            ms.obat_formularium_puskesmas,
                            "false"
                        ) as obat_formularium_puskesmas'
                    ),


                    /*
                    |--------------------------------------------------------------------------
                    | STOK AWAL
                    |--------------------------------------------------------------------------
                    */

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


                    /*
                    |--------------------------------------------------------------------------
                    | PENERIMAAN
                    |--------------------------------------------------------------------------
                    */

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


                    /*
                    |--------------------------------------------------------------------------
                    | PERSEDIAAN
                    |--------------------------------------------------------------------------
                    */

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


                    /*
                    |--------------------------------------------------------------------------
                    | PEMAKAIAN
                    |--------------------------------------------------------------------------
                    */

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


                    /*
                    |--------------------------------------------------------------------------
                    | EXPIRED
                    |--------------------------------------------------------------------------
                    */

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


                    /*
                    |--------------------------------------------------------------------------
                    | STOK AKHIR
                    |--------------------------------------------------------------------------
                    */

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


                    /*
                    |--------------------------------------------------------------------------
                    | PERMINTAAN
                    |--------------------------------------------------------------------------
                    */

                    DB::raw(
                        'SUM(
                            COALESCE(
                                i.permintaan,
                                0
                            )
                        ) as permintaan'
                    ),


                    /*
                    |--------------------------------------------------------------------------
                    | PEMBERIAN
                    |--------------------------------------------------------------------------
                    */

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

                /*
                |--------------------------------------------------------------------------
                | GROUP
                |--------------------------------------------------------------------------
                */

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
                | SORT
                |--------------------------------------------------------------------------
                */

                ->orderBy(
                    'program_name'
                )

                ->orderBy(
                    'i.nama_obat'
                )

                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'bulan_mulai' => $bulanMulai,

            'tahun_mulai' => $tahunMulai,

            'bulan_sampai' => $bulanSampai,

            'tahun_sampai' => $tahunSampai,

            'periode' =>
                sprintf(
                    '%02d/%04d - %02d/%04d',
                    $bulanMulai,
                    $tahunMulai,
                    $bulanSampai,
                    $tahunSampai
                ),

            'group_id' => $groupId,

            'header' => $header,

            'jumlah_laporan' =>
                $reportIds->count(),

            'jumlah_item' =>
                $items->count(),

            'items' => $items
        ]);
    }
}
