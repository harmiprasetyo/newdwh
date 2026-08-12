<?php

namespace App\Services\NewLplpo;

use App\Models\NewLplpo\Report;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReportService
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        return [

            'draft' =>
                Report::where(
                    'report_status',
                    'DRAFT'
                )->count(),

            'terkirim' =>
                Report::where(
                    'report_status',
                    'SUBMITED'
                )->count(),

            'terverifikasi' =>
                Report::where(
                    'report_status',
                    'VERIFIED'
                )->count(),

            'ditolak' =>
                Report::where(
                    'report_status',
                    'REJECTED'
                )->count(),

            'selesai' =>
                Report::where(
                    'report_status',
                    'FINAL'
                )->count(),

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | LIST
    |--------------------------------------------------------------------------
    */

    public function list($request)
    {
        return Report::query()

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

            ->orderByDesc('id')

            ->paginate(20);
    }


    /*
    |--------------------------------------------------------------------------
    | LAPORAN
    |--------------------------------------------------------------------------
    */

    public function laporan($request)
    {
        return Report::withCount('items')

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

            ->orderByDesc('created_at')

            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE REPORT
    |--------------------------------------------------------------------------
    */

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            return Report::create([

                'kode_faskes' =>
                    $data['kode_faskes'],

                'nama_faskes' =>
                    $data['nama_faskes'],

                'bulan' =>
                    $data['bulan'],

                'tahun' =>
                    $data['tahun'],

                'nomor_lplpo' =>
                    $data['nomor_lplpo'],

                /*
                |--------------------------------------------------------------------------
                | HARUS UPPERCASE SESUAI ENUM DATABASE
                |--------------------------------------------------------------------------
                */

                'report_status' => 'DRAFT',

            ]);

        });
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Report $report,
        array $data
    ) {

        /*
        |--------------------------------------------------------------------------
        | LAPORAN SUDAH SUBMIT
        |--------------------------------------------------------------------------
        */

        if (
            $report->report_status === 'SUBMITED'
            &&
            ($data['report_status'] ?? null) !== 'DRAFT'
        ) {

            throw ValidationException::withMessages([

                'report' =>
                    'Laporan sudah disubmit.'

            ]);

        }

        $report->update($data);

        return $report;
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(Report $report)
    {
        return $report->delete();
    }
}
