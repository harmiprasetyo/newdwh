<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use App\Services\NewLplpo\LplpoBekasiService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class LplpoBekasiController extends Controller
{
    protected LplpoBekasiService $lplpoService;

    public function __construct(
        LplpoBekasiService $lplpoService
    ) {
        $this->lplpoService = $lplpoService;
    }


    private function getLplpoRekap(
    string $startDate,
    string $endDate
) {
    $limit = 100;

    $page = 1;

    $allData = collect();

    do {

        $response = Http::withHeaders([
            'X-API-KEY' => config('services.lplpo.api_key'),
            'Cookie' => config('services.lplpo.cookie'),
        ])
        ->timeout(120)
        ->get(
            config('services.lplpo.url'),
            [
                'limit' => $limit,
                'page' => $page,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]
        );


        if (!$response->successful()) {

            throw new \Exception(
                'API LPLPO mengembalikan HTTP ' .
                $response->status()
            );
        }


        $json = $response->json();


        /*
        |--------------------------------------------------------------------------
        | API saat ini mengembalikan object:
        |
        | {
        |   "0": {...},
        |   "1": {...}
        | }
        |
        |--------------------------------------------------------------------------
        */

        $data = collect(
            array_values(
                $json
            )
        );


        if ($data->isEmpty()) {
            break;
        }


        $allData = $allData->merge(
            $data
        );


        /*
        |--------------------------------------------------------------------------
        | Jika jumlah data < limit berarti halaman terakhir
        |--------------------------------------------------------------------------
        */

        if ($data->count() < $limit) {
            break;
        }


        $page++;

    } while (true);


    /*
    |--------------------------------------------------------------------------
    | GROUP BY
    |
    | nama_pkm
    | kode_obat_kfa
    |--------------------------------------------------------------------------
    */

    return $allData
    ->groupBy(function ($row) {

        return implode('|', [
            $row['smiley_kode_sarana'] ?? '',
            $row['nama_pkm'] ?? '',
            $row['kode_obat_kfa'] ?? '',
        ]);

    })
    ->map(function ($rows) {

        $first = $rows->first();


        /*
        |--------------------------------------------------------------------------
        | STOK AWAL
        |--------------------------------------------------------------------------
        */

        $stokAwalRutin = $rows->sum(
            fn ($row) =>
                (float) ($row['stok_awal_rutin'] ?? 0)
        );

        $stokAwalProgram = $rows->sum(
            fn ($row) =>
                (float) ($row['stok_awal_program'] ?? 0)
        );

        $stokAwalJkn = $rows->sum(
            fn ($row) =>
                (float) ($row['stok_awal_jkn'] ?? 0)
        );


        /*
        |--------------------------------------------------------------------------
        | PENERIMAAN
        |--------------------------------------------------------------------------
        */

        $penerimaanRutin = $rows->sum(
            fn ($row) =>
                (float) ($row['penerimaan_rutin_pkd'] ?? 0)
        );

        $penerimaanProgram = $rows->sum(
            fn ($row) =>
                (float) ($row['penerimaan_program'] ?? 0)
        );

        $penerimaanJkn = $rows->sum(
            fn ($row) =>
                (float) ($row['penerimaan_jkn'] ?? 0)
        );


        /*
        |--------------------------------------------------------------------------
        | PENGGUNAAN
        |--------------------------------------------------------------------------
        */

        $penggunaanRutin = $rows->sum(
            fn ($row) =>
                (float) ($row['penggunaan_rutin'] ?? 0)
        );

        $penggunaanProgram = $rows->sum(
            fn ($row) =>
                (float) ($row['penggunaan_program'] ?? 0)
        );

        $penggunaanJkn = $rows->sum(
            fn ($row) =>
                (float) ($row['penggunaan_jkn'] ?? 0)
        );


        /*
        |--------------------------------------------------------------------------
        | STOK AKHIR
        |--------------------------------------------------------------------------
        */

        $stokAkhirRutin =
            $stokAwalRutin
            + $penerimaanRutin
            - $penggunaanRutin;


        $stokAkhirProgram =
            $stokAwalProgram
            + $penerimaanProgram
            - $penggunaanProgram;


        $stokAkhirJkn =
            $stokAwalJkn
            + $penerimaanJkn
            - $penggunaanJkn;


        $stokAkhir =
            $stokAkhirRutin
            + $stokAkhirProgram
            + $stokAkhirJkn;


        return [

            /*
            |--------------------------------------------------------------------------
            | IDENTITAS
            |--------------------------------------------------------------------------
            */

            'smiley_kode_sarana' =>
                $first['smiley_kode_sarana'] ?? null,

            'smiley_nama_fasyankes' =>
                $first['smiley_nama_fasyankes'] ?? null,

            'nama_pkm' =>
                $first['nama_pkm'] ?? null,

            'kode_obat_kfa' =>
                $first['kode_obat_kfa'] ?? null,

            'nama_obat' =>
                $first['nama_obat'] ?? null,

            'satuan' =>
                $first['satuan'] ?? null,


            /*
            |--------------------------------------------------------------------------
            | STOK AWAL
            |--------------------------------------------------------------------------
            */

            'stok_awal_rutin' =>
                $stokAwalRutin,

            'stok_awal_program' =>
                $stokAwalProgram,

            'stok_awal_jkn' =>
                $stokAwalJkn,


            /*
            |--------------------------------------------------------------------------
            | PENERIMAAN
            |--------------------------------------------------------------------------
            */

            'penerimaan_rutin_pkd' =>
                $penerimaanRutin,

            'penerimaan_program' =>
                $penerimaanProgram,

            'penerimaan_jkn' =>
                $penerimaanJkn,


            /*
            |--------------------------------------------------------------------------
            | PENGGUNAAN
            |--------------------------------------------------------------------------
            */

            'penggunaan_rutin' =>
                $penggunaanRutin,

            'penggunaan_program' =>
                $penggunaanProgram,

            'penggunaan_jkn' =>
                $penggunaanJkn,


            /*
            |--------------------------------------------------------------------------
            | STOK AKHIR
            |--------------------------------------------------------------------------
            */

            'stok_akhir_rutin' =>
                $stokAkhirRutin,

            'stok_akhir_program' =>
                $stokAkhirProgram,

            'stok_akhir_jkn' =>
                $stokAkhirJkn,

            'stok_akhir' =>
                $stokAkhir,


            /*
            |--------------------------------------------------------------------------
            | LAINNYA
            |--------------------------------------------------------------------------
            */

            'stok_optimum' =>
                (float) ($first['stok_optimum'] ?? 0),

            'permintaan' =>
                $rows->sum(
                    fn ($row) =>
                        (float) ($row['permintaan'] ?? 0)
                ),

        ];

    })
    ->values();
}



    /**
     * Halaman utama LPLPO Bekasi.
     */
    public function index(Request $request): View
    {
        $startDate = $request->input(
            'start_date',
            now()->startOfMonth()->format('Y-m-d')
        );

        $endDate = $request->input(
            'end_date',
            now()->endOfMonth()->format('Y-m-d')
        );


        return view(
            'newlplpo.bekasi.index',
            [
                'startDate' => $startDate,
                'endDate'   => $endDate,
            ]
        );
    }


    public function rekap(Request $request)
{
    $startDate = $request->start_date
        ?? Carbon::now()->startOfMonth()->format('Y-m-d');

    $endDate = $request->end_date
        ?? Carbon::now()->endOfMonth()->format('Y-m-d');

    return view(
        'newlplpo.bekasi.rekap',
        compact(
            'startDate',
            'endDate'
        )
    );
}


public function rekapData(Request $request)
{
    $startDate = $request->start_date
        ?? Carbon::now()->startOfMonth()->format('Y-m-d');

    $endDate = $request->end_date
        ?? Carbon::now()->endOfMonth()->format('Y-m-d');

    try {

        $result = $this->getLplpoRekap(
            $startDate,
            $endDate
        );

        return response()->json([
            'success' => true,
            'data' => $result,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

    } catch (\Throwable $e) {

        Log::error(
            'LPLPO Bekasi Rekap Error',
            [
                'message' => $e->getMessage(),
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]
        );

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil rekap LPLPO.'
        ], 500);
    }
}



    /**
     * Data LPLPO.
     */
    public function data(Request $request): JsonResponse
    {
        /*
        |--------------------------------------------------------------------------
        | PAGE
        |--------------------------------------------------------------------------
        */

        $page = max(
            1,
            (int) $request->input('page', 1)
        );


        /*
        |--------------------------------------------------------------------------
        | LIMIT
        |--------------------------------------------------------------------------
        |
        | Hanya izinkan:
        | 25
        | 50
        | 100
        |
        */

        $allowedLimits = [
            25,
            50,
            100,
        ];


        $limit = (int) $request->input(
            'limit',
            50
        );


        if (!in_array(
            $limit,
            $allowedLimits,
            true
        )) {

            $limit = 50;
        }


        /*
        |--------------------------------------------------------------------------
        | DEFAULT DATE
        |--------------------------------------------------------------------------
        */

        $defaultStartDate = now()
            ->startOfMonth()
            ->format('Y-m-d');


        $defaultEndDate = now()
            ->endOfMonth()
            ->format('Y-m-d');


        $startDate = $request->input(
            'start_date',
            $defaultStartDate
        );


        $endDate = $request->input(
            'end_date',
            $defaultEndDate
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDATE DATE
        |--------------------------------------------------------------------------
        */

        try {

            $startDate = Carbon::createFromFormat(
                'Y-m-d',
                $startDate
            )->format('Y-m-d');


            $endDate = Carbon::createFromFormat(
                'Y-m-d',
                $endDate
            )->format('Y-m-d');

        } catch (Throwable $e) {

            return response()->json(
                [
                    'success' => false,

                    'message' =>
                        'Format tanggal harus YYYY-MM-DD.',

                    'data' => [],

                    'page' => $page,

                    'limit' => $limit,

                    'hasNext' => false,
                ],
                422
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE DATE RANGE
        |--------------------------------------------------------------------------
        */

        if ($startDate > $endDate) {

            return response()->json(
                [
                    'success' => false,

                    'message' =>
                        'Tanggal mulai tidak boleh lebih besar ' .
                        'dari tanggal akhir.',

                    'data' => [],

                    'page' => $page,

                    'limit' => $limit,

                    'hasNext' => false,
                ],
                422
            );
        }


        /*
        |--------------------------------------------------------------------------
        | GET DATA FROM SERVICE
        |--------------------------------------------------------------------------
        */

        $result = $this->lplpoService->getData(
            page: $page,
            limit: $limit,
            startDate: $startDate,
            endDate: $endDate
        );


        /*
        |--------------------------------------------------------------------------
        | API ERROR
        |--------------------------------------------------------------------------
        */

        if (!$result['success']) {

            return response()->json(
                [
                    'success' => false,

                    'message' =>
                        $result['message']
                        ?? 'Gagal mengambil data LPLPO.',

                    'data' => [],

                    'page' => $page,

                    'limit' => $limit,

                    'hasNext' => false,

                    'start_date' => $startDate,

                    'end_date' => $endDate,
                ],
                502
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return response()->json(
            [
                'success' => true,

                'data' => $result['data'],

                'page' => $page,

                'limit' => $limit,

                'hasNext' => $result['hasNext'],

                'start_date' => $startDate,

                'end_date' => $endDate,
            ]
        );
    }
}
