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
        | DEFAULT BULAN & TAHUN
        |--------------------------------------------------------------------------
        */

        $bulan = (int) $request->input(
            'bulan',
            now()->month
        );

        $tahun = (int) $request->input(
            'tahun',
            now()->year
        );

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

            /*
            |--------------------------------------------------------------------------
            | DINKES
            |
            | User Dinkes memiliki kodeKota.
            | master_faskes.kodeKabupaten = users.kodeKota
            |--------------------------------------------------------------------------
            */

            $kodeKota = $user->kodeKota;

            $faskes = DB::table('master_faskes')
                ->where(
                    'kodeKabupaten',
                    $kodeKota
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
                'bulan',
                'tahun',
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

        $bulan = (int) $request->input(
            'bulan',
            now()->month
        );

        $tahun = (int) $request->input(
            'tahun',
            now()->year
        );

        /*
        |--------------------------------------------------------------------------
        | BASE QUERY REPORT
        |--------------------------------------------------------------------------
        */

        $reportQuery = DB::table(
            'new_lplpo_reports as r'
        )

        /*
        |--------------------------------------------------------------------------
        | JOIN MASTER FASKES
        |--------------------------------------------------------------------------
        */

        ->leftJoin(
            'master_faskes as f',
            'f.kodeFaskes',
            '=',
            'r.kode_faskes'
        )

        /*
        |--------------------------------------------------------------------------
        | FILTER PERIODE
        |--------------------------------------------------------------------------
        */

        ->where(
            'r.bulan',
            $bulan
        )

        ->where(
            'r.tahun',
            $tahun
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
        | FILTER BERDASARKAN GROUP
        |--------------------------------------------------------------------------
        */

        if ($groupId === 2) {

            /*
            |--------------------------------------------------------------------------
            | DINKES
            |
            | Hanya faskes dalam wilayah Dinkes
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

        } elseif (
            $groupId === 3 ||
            $groupId === 5
        ) {

            /*
            |--------------------------------------------------------------------------
            | PUSKESMAS
            |
            | Hanya faskes milik user yang login
            |--------------------------------------------------------------------------
            */

            $reportQuery->where(
                'r.kode_faskes',
                $user->kodeFaskes
            );

        } else {

            abort(
                403,
                'Group user tidak diperbolehkan.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL REPORT ID
        |--------------------------------------------------------------------------
        */

        $reportIds = (clone $reportQuery)
            ->pluck('r.id');


        /*
        |--------------------------------------------------------------------------
        | INFORMASI HEADER
        |--------------------------------------------------------------------------
        */

        $header = (clone $reportQuery)
            ->select(
                'r.kode_faskes',
                'r.nama_faskes'
            )
            ->distinct()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | REKAP ITEM
        |--------------------------------------------------------------------------
        |
        | Seluruh item dari semua LPLPO FINAL
        | digabung dan dijumlahkan.
        |
        */

        $items = collect();


        if ($reportIds->isNotEmpty()) {

            $items = DB::table(
                'new_lplpo_itemlist as i'
            )

            ->leftJoin(
                'new_lplpo_program_list as p',
                'p.id',
                '=',
                'i.program_id'
            )

            ->whereIn(
                'i.report_id',
                $reportIds
            )

            ->select(

                'i.program_id',

                DB::raw(
                    'COALESCE(p.program_name, i.program_name, "Non Program") as program_name'
                ),

                'i.kode_obat',

                'i.nama_obat',

                'i.satuan',

                /*
                |--------------------------------------------------------------------------
                | STOK AWAL
                |--------------------------------------------------------------------------
                */

                DB::raw(
                    'SUM(COALESCE(i.stok_awal_progam_pkd,0)) as stok_awal_progam_pkd'
                ),

                DB::raw(
                    'SUM(COALESCE(i.stok_awal_jkn,0)) as stok_awal_jkn'
                ),

                /*
                |--------------------------------------------------------------------------
                | PENERIMAAN
                |--------------------------------------------------------------------------
                */

                DB::raw(
                    'SUM(COALESCE(i.penerimaan_program_pkd,0)) as penerimaan_program_pkd'
                ),

                DB::raw(
                    'SUM(COALESCE(i.penerimaan_jkn,0)) as penerimaan_jkn'
                ),

                /*
                |--------------------------------------------------------------------------
                | PERSEDIAAN
                |--------------------------------------------------------------------------
                */

                DB::raw(
                    'SUM(COALESCE(i.persediaan_program_pkd,0)) as persediaan_program_pkd'
                ),

                DB::raw(
                    'SUM(COALESCE(i.persediaan_jkn,0)) as persediaan_jkn'
                ),

                /*
                |--------------------------------------------------------------------------
                | PEMAKAIAN
                |--------------------------------------------------------------------------
                */

                DB::raw(
                    'SUM(COALESCE(i.pemakaian_program_pkd,0)) as pemakaian_program_pkd'
                ),

                DB::raw(
                    'SUM(COALESCE(i.pemakaian_jkn,0)) as pemakaian_jkn'
                ),

                /*
                |--------------------------------------------------------------------------
                | EXPIRED
                |--------------------------------------------------------------------------
                */

                DB::raw(
                    'SUM(COALESCE(i.item_expired,0)) as item_expired'
                ),

                /*
                |--------------------------------------------------------------------------
                | STOK AKHIR
                |--------------------------------------------------------------------------
                */

                DB::raw(
                    'SUM(COALESCE(i.stok_akhir_program_pkd,0)) as stok_akhir_program_pkd'
                ),

                DB::raw(
                    'SUM(COALESCE(i.stok_akhir_jkn,0)) as stok_akhir_jkn'
                ),

                /*
                |--------------------------------------------------------------------------
                | PERMINTAAN
                |--------------------------------------------------------------------------
                */

                DB::raw(
                    'SUM(COALESCE(i.permintaan,0)) as permintaan'
                ),

                /*
                |--------------------------------------------------------------------------
                | PEMBERIAN
                |--------------------------------------------------------------------------
                */

                DB::raw(
                    'SUM(COALESCE(i.pemberian_program_pkd,0)) as pemberian_program_pkd'
                ),

                DB::raw(
                    'SUM(COALESCE(i.pemberian_jkn,0)) as pemberian_jkn'
                )

            )

            ->groupBy(

                'i.program_id',

                'p.program_name',

                'i.program_name',

                'i.kode_obat',

                'i.nama_obat',

                'i.satuan'

            )

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

            'bulan' => $bulan,

            'tahun' => $tahun,

            'group_id' => $groupId,

            'header' => $header,

            'jumlah_laporan' => $reportIds->count(),

            'jumlah_item' => $items->count(),

            'items' => $items

        ]);
    }
}
