<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * ============================================================
     * DASHBOARD
     * ============================================================
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        /*
        |--------------------------------------------------------------------------
        | GROUP ID DARI AUTH USER
        |--------------------------------------------------------------------------
        */

        $groupId = (int) $user->groupid;

        /*
        |--------------------------------------------------------------------------
        | FILTER BULAN / TAHUN
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
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        if ($bulan < 1 || $bulan > 12) {
            $bulan = now()->month;
        }

        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = now()->year;
        }

         /*
        |--------------------------------------------------------------------------
        | DASHBOARD Administrator
        |--------------------------------------------------------------------------
        */

        if ($groupId === 1) {

            return $this->dashboardDinkes(
                $bulan,
                $tahun
            );

        }



        /*
        |--------------------------------------------------------------------------
        | DASHBOARD DINAS KESEHATAN
        |--------------------------------------------------------------------------
        */

        if ($groupId === 2) {

            return $this->dashboardDinkes(
                $bulan,
                $tahun
            );

        }

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD PUSKESMAS
        |--------------------------------------------------------------------------
        */

        if (in_array($groupId, [3, 5])) {

            return $this->dashboardPuskesmas(
                $user,
                $bulan,
                $tahun
            );

        }

        abort(
            403,
            'Anda tidak memiliki akses ke dashboard LPLPO.'
        );
    }


    /**
     * ============================================================
     * DASHBOARD DINKES
     * ============================================================
     */
    private function dashboardDinkes(
        int $bulan,
        int $tahun
    ) {

        /*
        |--------------------------------------------------------------------------
        | BASE QUERY REPORT
        |--------------------------------------------------------------------------
        */

        $reportQuery = DB::table(
            'new_lplpo_reports'
        )
        ->where('bulan', $bulan)
        ->where('tahun', $tahun);


        /*
        |--------------------------------------------------------------------------
        | 1. JUMLAH PUSKESMAS MELAPOR
        |--------------------------------------------------------------------------
        |
        | DISTINCT kode_faskes
        |
        */

       $jumlahPuskesmas = (clone $reportQuery)
    ->whereNotNull('kode_faskes')
    ->where('kode_faskes', '!=', '')
    ->where('report_status', '!=', 'DRAFT')
    ->distinct()
    ->count('kode_faskes');

        /*
        |--------------------------------------------------------------------------
        | 2. JUMLAH VERIFIED
        |--------------------------------------------------------------------------
        */

        $jumlahVerified = (clone $reportQuery)
            ->where(
                'report_status',
                'VERIFIED'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | 3. JUMLAH FINAL / SELESAI
        |--------------------------------------------------------------------------
        */

        $jumlahFinal = (clone $reportQuery)
            ->where(
                'report_status',
                'FINAL'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | DATA GRAFIK
        |--------------------------------------------------------------------------
        |
        | X = Nama Faskes
        | Y = Jumlah item obat yang dilaporkan
        |
        */

        $chartDinkes = DB::table(
            'new_lplpo_reports as r'
        )

        ->leftJoin(
            'new_lplpo_itemlist as i',
            'i.report_id',
            '=',
            'r.id'
        )

        ->where(
            'r.bulan',
            $bulan
        )

        ->where(
            'r.tahun',
            $tahun
        )

        ->select(

            'r.kode_faskes',

            'r.nama_faskes',

            DB::raw(
                'COUNT(i.id) as jumlah_item'
            )

        )

        ->groupBy(
            'r.kode_faskes',
            'r.nama_faskes'
        )

        ->orderByDesc(
            'jumlah_item'
        )

        ->get();


        /*
        |--------------------------------------------------------------------------
        | DATA TABEL REKAP
        |--------------------------------------------------------------------------
        */

        $rekapDinkes = DB::table(
            'new_lplpo_reports as r'
        )

        ->leftJoin(
            'new_lplpo_itemlist as i',
            'i.report_id',
            '=',
            'r.id'
        )

        ->where(
            'r.bulan',
            $bulan
        )

        ->where(
            'r.tahun',
            $tahun
        )
        ->where(
    'r.report_status',
    '!=',
    'DRAFT'
)

        ->select(

            'r.id',

            'r.kode_faskes',

            'r.nama_faskes',

            'r.nomor_lplpo',

            'r.report_status',

            'r.created_at',

            DB::raw(
                'COUNT(i.id) as item_dilaporkan'
            )

        )

        ->groupBy(

            'r.id',

            'r.kode_faskes',

            'r.nama_faskes',

            'r.nomor_lplpo',

            'r.report_status',

            'r.created_at'

        )

        ->orderByDesc(
            'r.created_at'
        )

        ->get();


        /*
        |--------------------------------------------------------------------------
        | STATUS SUMMARY
        |--------------------------------------------------------------------------
        */

        $statusSummary = [



            'SUBMITED' => (clone $reportQuery)
                ->where('report_status', 'SUBMITED')
                ->count(),

            'VERIFIED' => (clone $reportQuery)
                ->where('report_status', 'VERIFIED')
                ->count(),

            'REJECTED' => (clone $reportQuery)
                ->whereIn(
                    'report_status',
                    [
                        'REJECTED',
                        'Rejected'
                    ]
                )
                ->count(),

            'FINAL' => (clone $reportQuery)
                ->where('report_status', 'FINAL')
                ->count(),

        ];


        return view(
            'newlplpo.dashboard',
            compact(

                'bulan',

                'tahun',

                'jumlahPuskesmas',

                'jumlahVerified',

                'jumlahFinal',

                'chartDinkes',

                'rekapDinkes',

                'statusSummary'

            )
        );
    }


    /**
     * ============================================================
     * DASHBOARD PUSKESMAS
     * ============================================================
     */
    private function dashboardPuskesmas(
        $user,
        int $bulan,
        int $tahun
    ) {

        /*
        |--------------------------------------------------------------------------
        | KODE FASKES USER
        |--------------------------------------------------------------------------
        |
        | Sesuaikan field jika di users nama field berbeda.
        |
        */

        $kodeFaskes =
            $user->kode_faskes
            ?? $user->kodeFaskes
            ?? null;


        /*
        |--------------------------------------------------------------------------
        | BASE REPORT QUERY
        |--------------------------------------------------------------------------
        */

        $reportQuery = DB::table(
            'new_lplpo_reports'
        )

        ->where(
            'bulan',
            $bulan
        )

        ->where(
            'tahun',
            $tahun
        );


        /*
        |--------------------------------------------------------------------------
        | FILTER FASKES
        |--------------------------------------------------------------------------
        */

        if ($kodeFaskes) {

            $reportQuery->where(
                'kode_faskes',
                $kodeFaskes
            );

        }


        /*
        |--------------------------------------------------------------------------
        | STATUS BOX
        |--------------------------------------------------------------------------
        */

        $statusSummary = [

            'DRAFT' => (clone $reportQuery)
                ->where(
                    'report_status',
                    'DRAFT'
                )
                ->count(),

            'SUBMITED' => (clone $reportQuery)
                ->where(
                    'report_status',
                    'SUBMITED'
                )
                ->count(),

            'VERIFIED' => (clone $reportQuery)
                ->where(
                    'report_status',
                    'VERIFIED'
                )
                ->count(),

            'REJECTED' => (clone $reportQuery)
                ->whereIn(
                    'report_status',
                    [
                        'REJECTED',
                        'Rejected'
                    ]
                )
                ->count(),

            'FINAL' => (clone $reportQuery)
                ->where(
                    'report_status',
                    'FINAL'
                )
                ->count(),

        ];


        /*
        |--------------------------------------------------------------------------
        | TOP 10 PEMAKAIAN OBAT
        |--------------------------------------------------------------------------
        |
        | pemakaian_program_pkd
        | +
        | pemakaian_jkn
        |
        */

        $chartPuskesmas = DB::table(
            'new_lplpo_itemlist as i'
        )

        ->join(
            'new_lplpo_reports as r',
            'r.id',
            '=',
            'i.report_id'
        )

        ->where(
            'r.bulan',
            $bulan
        )

        ->where(
            'r.tahun',
            $tahun
        );


        if ($kodeFaskes) {

            $chartPuskesmas->where(
                'r.kode_faskes',
                $kodeFaskes
            );

        }


        $chartPuskesmas = $chartPuskesmas

        ->select(

            'i.kode_obat',

            'i.nama_obat',

            DB::raw(
                '
                SUM(
                    COALESCE(i.pemakaian_program_pkd, 0)
                    +
                    COALESCE(i.pemakaian_jkn, 0)
                ) as total_pemakaian
                '
            )

        )

        ->groupBy(

            'i.kode_obat',

            'i.nama_obat'

        )

        ->having(
            'total_pemakaian',
            '>',
            0
        )

        ->orderByDesc(
            'total_pemakaian'
        )

        ->limit(10)

        ->get();


        /*
        |--------------------------------------------------------------------------
        | REKAP REPORT PUSKESMAS
        |--------------------------------------------------------------------------
        */

        $rekapPuskesmas = (clone $reportQuery)

            ->orderByDesc(
                'created_at'
            )

            ->get();


        return view(
            'newlplpo.dashboard',
            compact(

                'bulan',

                'tahun',

                'statusSummary',

                'chartPuskesmas',

                'rekapPuskesmas',

                'kodeFaskes'

            )
        );
    }
}
