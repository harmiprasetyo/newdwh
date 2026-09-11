<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LplpoReportController extends Controller
{
    /**
     * Halaman monitoring report LPLPO
     */
    public function monitoring()
    {
        return view('newlplpo.report_monitoring');
    }

    /**
     * Data monitoring report LPLPO
     */
    public function monitoringData(Request $request)
    {
        $tahun = (int) $request->input('tahun', now()->year);

        /*
         * Ambil semua report yang:
         * - tahun sesuai filter
         * - status bukan DRAFT
         */
        $reports = DB::table('new_lplpo_reports')
            ->select([
                'id',
                'kode_faskes',
                'nama_faskes',
                'bulan',
                'tahun',
                'report_status',
                'updated_at',
            ])
            ->where('tahun', $tahun)
            ->where('report_status', '!=', 'DRAFT')
            ->orderBy('nama_faskes')
            ->orderBy('bulan')
            ->orderByDesc('updated_at')
            ->get();

        /*
         * Jika terdapat lebih dari satu report untuk:
         *
         * faskes + bulan + tahun
         *
         * maka yang digunakan adalah report dengan
         * updated_at paling baru.
         */
        $data = [];

        foreach ($reports as $report) {

            $key = $report->kode_faskes . '_' . $report->bulan;

            if (!isset($data[$key])) {

                $data[$key] = [
                    'kode_faskes'  => $report->kode_faskes,
                    'nama_faskes'  => $report->nama_faskes,
                    'bulan'        => (int) $report->bulan,
                    'tahun'        => (int) $report->tahun,
                    'report_status'=> $report->report_status,
                    'updated_at'   => $report->updated_at
                        ? Carbon::parse($report->updated_at)->format('d/m/Y')
                        : null,
                ];
            }
        }

        /*
         * Susun berdasarkan faskes
         */
        $faskes = [];

        foreach ($data as $item) {

            $kode = $item['kode_faskes'];

            if (!isset($faskes[$kode])) {

                $faskes[$kode] = [
                    'kode_faskes' => $kode,
                    'nama_faskes' => $item['nama_faskes'],
                    'bulan'       => [],
                ];
            }

            $faskes[$kode]['bulan'][$item['bulan']] = [
                'tanggal' => $item['updated_at'],
                'status'  => $item['report_status'],
            ];
        }

        return response()->json([
            'success' => true,
            'tahun'   => $tahun,
            'data'    => array_values($faskes),
        ]);
    }
}
