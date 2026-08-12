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
     * Form input kunjungan
     */
    public function create($reportId)
    {
        $report = Report::findOrFail($reportId);

        $kunjungan = Kunjungan::where(
            'report_id',
            $reportId
        )->first();

        return view(
            'newlplpo.kunjungan.form',
            compact(
                'report',
                'kunjungan'
            )
        );
    }


    /**
     * Simpan kunjungan
     */
    public function store(Request $request, $reportId)
    {
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
     * Form edit
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
     * Update
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
     * Hapus
     */
    public function destroy($reportId)
    {
        $kunjungan = Kunjungan::where(
            'report_id',
            $reportId
        )->firstOrFail();

        $this->service->delete($kunjungan);

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
