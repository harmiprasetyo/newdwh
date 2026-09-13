<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use App\Models\NewLplpo\Report;
use App\Models\NewLplpo\Kunjungan;
use App\Services\NewLplpo\KunjunganService;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    protected KunjunganService $service;

    public function __construct(KunjunganService $service)
    {
        $this->service = $service;
    }


    /**
     * ==========================================================
     * FORM INPUT KUNJUNGAN
     * ==========================================================
     *
     * Jika report sudah memiliki data kunjungan:
     *   -> tampilkan data tersebut
     *
     * Jika belum:
     *   -> cari report lain dengan:
     *      - faskes sama
     *      - bulan sama
     *      - tahun sama
     *
     *   -> jika ditemukan data kunjungan,
     *      gunakan sebagai template/default form.
     *
     * Data template TIDAK dianggap sebagai data milik report
     * yang sedang dibuat.
     */
    /**
 * ==========================================================
 * FORM INPUT KUNJUNGAN
 * ==========================================================
 */
public function create($reportId)
{
    $report = Report::findOrFail($reportId);


    /*
     * ==========================================================
     * CEK DATA KUNJUNGAN REPORT SAAT INI
     * ==========================================================
     */
    $kunjungan = $report->kunjungan;


    /*
     * ==========================================================
     * JIKA SUDAH ADA
     *
     * Jangan tampilkan form create.
     * Langsung arahkan ke form edit.
     * ==========================================================
     */
    if ($kunjungan) {

        return redirect()->route(
            'newlplpo.kunjungan.edit',
            $report->id
        );
    }


    /*
     * ==========================================================
     * CARI TEMPLATE
     *
     * Cari report lain dengan:
     *
     * kode_faskes sama
     * bulan sama
     * tahun sama
     *
     * dan sudah memiliki kunjungan.
     * ==========================================================
     */
    $kunjunganTemplate = null;

    $reportSumber = Report::where(
            'id',
            '!=',
            $report->id
        )
        ->where(
            'kode_faskes',
            $report->kode_faskes
        )
        ->where(
            'bulan',
            $report->bulan
        )
        ->where(
            'tahun',
            $report->tahun
        )
        ->whereHas('kunjungan')
        ->latest('id')
        ->first();


    /*
     * ==========================================================
     * JIKA TEMPLATE DITEMUKAN
     * ==========================================================
     */
    if ($reportSumber) {

        $kunjunganTemplate =
            $reportSumber->kunjungan;

    }


    return view(
        'newlplpo.kunjungan.form',
        compact(
            'report',
            'kunjungan',
            'kunjunganTemplate'
        )
    );
}

    /**
     * ==========================================================
     * SIMPAN KUNJUNGAN
     * ==========================================================
     */
    public function store(
        Request $request,
        $reportId
    ) {

        $report = Report::findOrFail($reportId);

        $validated = $request->validate([

            'kunjungan_jkn' =>
                'required|integer|min:0',

            'kunjungan_tunai' =>
                'required|integer|min:0',

            'kunjungan_gratis' =>
                'required|integer|min:0',

            'kunjungan_anak' =>
                'required|integer|min:0',

            'kunjungan_dewasa' =>
                'required|integer|min:0',

            'kunjungan_lab' =>
                'required|integer|min:0',

            'kunjungan_gigi' =>
                'required|integer|min:0',

            'kunjungan_poned' =>
                'required|integer|min:0',

            'kunjungan_rawatinap' =>
                'required|integer|min:0',

            'kunjungan_rawatjalan' =>
                'required|integer|min:0',

        ]);


        $this->service->create(
            $report->id,
            $validated
        );


        return redirect()
            ->route(
                'newlplpo.edit',
                $report->id
            )
            ->with(
                'success',
                'Data kunjungan berhasil disimpan.'
            );
    }


    /**
     * ==========================================================
     * FORM EDIT
     * ==========================================================
     */
    public function edit($reportId)
    {
        $report = Report::findOrFail($reportId);

        $kunjungan = Kunjungan::where(
            'report_id',
            $reportId
        )->firstOrFail();


        return view(
            'newlplpo.kunjungan.form',
            compact(
                'report',
                'kunjungan'
            )
        );
    }


    /**
     * ==========================================================
     * UPDATE
     * ==========================================================
     */
    public function update(
        Request $request,
        $reportId
    ) {

        $report = Report::findOrFail($reportId);

        $kunjungan = Kunjungan::where(
            'report_id',
            $reportId
        )->firstOrFail();


        $validated = $request->validate([

            'kunjungan_jkn' =>
                'required|integer|min:0',

            'kunjungan_tunai' =>
                'required|integer|min:0',

            'kunjungan_gratis' =>
                'required|integer|min:0',

            'kunjungan_anak' =>
                'required|integer|min:0',

            'kunjungan_dewasa' =>
                'required|integer|min:0',

            'kunjungan_lab' =>
                'required|integer|min:0',

            'kunjungan_gigi' =>
                'required|integer|min:0',

            'kunjungan_poned' =>
                'required|integer|min:0',

            'kunjungan_rawatinap' =>
                'required|integer|min:0',

            'kunjungan_rawatjalan' =>
                'required|integer|min:0',

        ]);


        $this->service->update(
            $kunjungan,
            $validated
        );


        return redirect()
            ->route(
                'newlplpo.edit',
                $report->id
            )
            ->with(
                'success',
                'Data kunjungan berhasil diperbarui.'
            );
    }


    /**
     * ==========================================================
     * HAPUS
     * ==========================================================
     */
    public function destroy($reportId)
    {
        $kunjungan = Kunjungan::where(
            'report_id',
            $reportId
        )->firstOrFail();


        $this->service->delete(
            $kunjungan
        );


        return redirect()
            ->route(
                'newlplpo.edit',
                $reportId
            )
            ->with(
                'success',
                'Data kunjungan berhasil dihapus.'
            );
    }
}
