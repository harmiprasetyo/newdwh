<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use App\Services\NewLplpo\LplpoStokEsensialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LplpoStokEsensialController extends Controller
{
    protected LplpoStokEsensialService $service;

    public function __construct(
        LplpoStokEsensialService $service
    ) {
        $this->service = $service;
    }

    /**
     * ==========================================================
     * INDEX
     * ==========================================================
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $groupId = (int) $user->groupid;

        if (!in_array($groupId, [1, 2, 3, 4, 5], true)) {
            abort(
                403,
                'Anda tidak memiliki akses ke monitoring stok obat esensial.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | GROUP 3, 4, 5
        |--------------------------------------------------------------------------
        |
        | Default:
        | bulan mulai  = bulan sekarang
        | tahun mulai  = tahun sekarang
        | bulan sampai = bulan sekarang
        | tahun sampai = tahun sekarang
        |
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
        | GROUP 1, 2
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

        $this->validateMonthYear(
            $bulanMulai,
            $tahunMulai
        );

        $this->validateMonthYear(
            $bulanSampai,
            $tahunSampai
        );

        $this->validateMonthYear(
            $bulan,
            $tahun
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDASI RANGE
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
        | DATA FASKES
        |--------------------------------------------------------------------------
        */

        $faskes = collect();

        if (in_array($groupId, [1, 2], true)) {

            $faskes = $this->service->getFaskesForUser(
                $user
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'newlplpo.stokesensial.index',
            compact(
                'groupId',
                'bulanMulai',
                'tahunMulai',
                'bulanSampai',
                'tahunSampai',
                'bulan',
                'tahun',
                'faskes'
            )
        );
    }

    /**
     * ==========================================================
     * DATA
     * ==========================================================
     */
    public function data(Request $request)
    {
        $user = Auth::user();

        $groupId = (int) $user->groupid;

        if (!in_array($groupId, [1, 2, 3, 4, 5], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses.'
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | GROUP 3, 4, 5
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
        | GROUP 1, 2
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

        try {

            $this->validateMonthYear(
                $bulanMulai,
                $tahunMulai
            );

            $this->validateMonthYear(
                $bulanSampai,
                $tahunSampai
            );

            $this->validateMonthYear(
                $bulan,
                $tahun
            );

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI RANGE
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
                    'Periode mulai tidak boleh lebih besar dari periode sampai.'
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | MODE
        |--------------------------------------------------------------------------
        */

        if (in_array($groupId, [3, 4, 5], true)) {

            $result =
                $this->service->getHeatmapPeriode(
                    $user,
                    $bulanMulai,
                    $tahunMulai,
                    $bulanSampai,
                    $tahunSampai
                );

            return response()->json([
                'success' => true,
                'mode' => 'periode',
                'group_id' => $groupId,
                'bulan_mulai' => $bulanMulai,
                'tahun_mulai' => $tahunMulai,
                'bulan_sampai' => $bulanSampai,
                'tahun_sampai' => $tahunSampai,
                'data' => $result
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | GROUP 1, 2
        |--------------------------------------------------------------------------
        */

        $kodeFaskes =
            $request->input('kode_faskes');

        $result =
            $this->service->getHeatmapPerFaskes(
                $user,
                $bulan,
                $tahun,
                $kodeFaskes
            );

        return response()->json([
            'success' => true,
            'mode' => 'faskes',
            'group_id' => $groupId,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'data' => $result
        ]);
    }

    /**
     * ==========================================================
     * VALIDATE MONTH / YEAR
     * ==========================================================
     */
    protected function validateMonthYear(
        int $bulan,
        int $tahun
    ): void {

        if ($bulan < 1 || $bulan > 12) {
            throw new \InvalidArgumentException(
                'Bulan tidak valid.'
            );
        }

        if ($tahun < 2000 || $tahun > 2100) {
            throw new \InvalidArgumentException(
                'Tahun tidak valid.'
            );
        }
    }
}
