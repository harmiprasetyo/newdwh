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

        /*
        |--------------------------------------------------------------------------
        | FILTER KODE FASKES
        |--------------------------------------------------------------------------
        |
        | Jika controller mengirim kode_faskes,
        | maka laporan hanya diambil dari faskes tersebut.
        |
        */

        ->when(
            $request->kode_faskes,
            function ($q) use ($request) {

                $q->where(
                    'kode_faskes',
                    $request->kode_faskes
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

 public function update(Report $report, array $data)
{
    /*
    |--------------------------------------------------------------------------
    | Laporan yang sudah approved / submitted tidak boleh diedit
    |--------------------------------------------------------------------------
    */
    if (in_array($report->lplpo_status, [
        'waiting',
        'approved',
    ], true)) {

        throw ValidationException::withMessages([
            'report' =>
                'Laporan tidak dapat diubah karena sedang dalam proses approval atau sudah disetujui.'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REJECTED boleh diperbaiki
    |--------------------------------------------------------------------------
    */
    $report->update($data);

    return $report->fresh();
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
