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

        $this->authorizeGroup($groupId);

        return view(
            'newlplpo.stokesensial.index',
            [
                'groupId' => $groupId,

                'bulanMulai' =>
                    (int) $request->input(
                        'bulan_mulai',
                        now()->month
                    ),

                'tahunMulai' =>
                    (int) $request->input(
                        'tahun_mulai',
                        now()->year
                    ),

                'bulanSampai' =>
                    (int) $request->input(
                        'bulan_sampai',
                        now()->month
                    ),

                'tahunSampai' =>
                    (int) $request->input(
                        'tahun_sampai',
                        now()->year
                    ),

                'bulan' =>
                    (int) $request->input(
                        'bulan',
                        now()->month
                    ),

                'tahun' =>
                    (int) $request->input(
                        'tahun',
                        now()->year
                    ),

                'faskes' =>
                    in_array(
                        $groupId,
                        [1, 2],
                        true
                    )
                        ? $this->service
                            ->getFaskesForUser($user)
                        : collect()
            ]
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

        try {

            $this->authorizeGroup($groupId);

            /*
            |--------------------------------------------------------------------------
            | GROUP 3,4,5
            |--------------------------------------------------------------------------
            */
            if (
                in_array(
                    $groupId,
                    [3, 4, 5],
                    true
                )
            ) {

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

                $this->validateMonthYear(
                    $bulanMulai,
                    $tahunMulai
                );

                $this->validateMonthYear(
                    $bulanSampai,
                    $tahunSampai
                );

                $this->validateRange(
                    $bulanMulai,
                    $tahunMulai,
                    $bulanSampai,
                    $tahunSampai
                );

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
                    'data' => $result
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | GROUP 1,2
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

            $this->validateMonthYear(
                $bulan,
                $tahun
            );

            $kodeFaskes =
                $request->input(
                    'kode_faskes'
                );

            /*
            |--------------------------------------------------------------------------
            | GROUP 2
            |--------------------------------------------------------------------------
            | KATEGORI OBAT
            |--------------------------------------------------------------------------
            */
            if ($groupId === 2) {

                $result =
                    $this->service->getHeatmapPerKategori(
                        $user,
                        $bulan,
                        $tahun,
                        $kodeFaskes
                    );

                return response()->json([
                    'success' => true,
                    'mode' => 'kategori',
                    'group_id' => $groupId,
                    'data' => $result
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | GROUP 1
            |--------------------------------------------------------------------------
            | PER OBAT
            |--------------------------------------------------------------------------
            */
            $result =
                $this->service->getHeatmapPerObat(
                    $user,
                    $bulan,
                    $tahun,
                    $kodeFaskes
                );

            return response()->json([
                'success' => true,
                'mode' => 'obat',
                'group_id' => $groupId,
                'data' => $result
            ]);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * ==========================================================
     * AUTHORIZE GROUP
     * ==========================================================
     */
    protected function authorizeGroup(int $groupId): void
    {
        if (!in_array(
            $groupId,
            [1, 2, 3, 4, 5],
            true
        )) {

            abort(
                403,
                'Anda tidak memiliki akses ke monitoring stok obat esensial.'
            );
        }
    }

    /**
     * ==========================================================
     * VALIDATE MONTH YEAR
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

    /**
     * ==========================================================
     * VALIDATE RANGE
     * ==========================================================
     */
    protected function validateRange(
        int $bulanMulai,
        int $tahunMulai,
        int $bulanSampai,
        int $tahunSampai
    ): void {

        $start =
            ($tahunMulai * 100)
            + $bulanMulai;

        $end =
            ($tahunSampai * 100)
            + $bulanSampai;

        if ($start > $end) {

            throw new \InvalidArgumentException(
                'Periode mulai tidak boleh lebih besar dari periode sampai.'
            );
        }
    }
}