<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use App\Services\NewLplpo\LplpoBekasiService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LplpoBekasiController extends Controller
{
    protected LplpoBekasiService $service;

    public function __construct(LplpoBekasiService $service)
    {
        $this->service = $service;
    }

    /**
     * Halaman utama.
     */
    public function index(Request $request)
    {
        $startDate = $request->input(
            'start_date',
            now()->startOfMonth()->format('Y-m-d')
        );

        $endDate = $request->input(
            'end_date',
            now()->endOfMonth()->format('Y-m-d')
        );

        return view('newlplpo.bekasi.index', [
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]);
    }

    /**
     * DataTables server-side.
     */
    public function datatable(Request $request)
    {
        /*
         * DataTables:
         *
         * start  = posisi record
         * length = jumlah record
         */
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 25);

        if ($length < 1) {
            $length = 25;
        }

        /*
         * API menggunakan page 1, 2, 3...
         */
        $page = (int) floor($start / $length) + 1;

        /*
         * Validasi tanggal.
         */
        try {

            $startDate = $request->input(
                'start_date',
                now()->startOfMonth()->format('Y-m-d')
            );

            $endDate = $request->input(
                'end_date',
                now()->endOfMonth()->format('Y-m-d')
            );

            $startDate = Carbon::createFromFormat(
                'Y-m-d',
                $startDate
            )->format('Y-m-d');

            $endDate = Carbon::createFromFormat(
                'Y-m-d',
                $endDate
            )->format('Y-m-d');

        } catch (\Throwable $e) {

            return response()->json([
                'draw'            => (int) $request->input('draw'),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'error'           => 'Format tanggal tidak valid.',
            ]);
        }

        /*
         * Pastikan start <= end.
         */
        if ($startDate > $endDate) {

            return response()->json([
                'draw'            => (int) $request->input('draw'),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'error'           => 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.',
            ]);
        }

        /*
         * Puskesmas.
         *
         * Filter ini dilakukan di aplikasi karena endpoint
         * API yang diberikan baru mendukung start_date/end_date.
         */
        $puskesmas = trim(
            (string) $request->input('puskesmas', '')
        );

        /*
         * Request ke API.
         */
        $result = $this->service->getData(
            page: $page,
            limit: $length,
            startDate: $startDate,
            endDate: $endDate
        );

        if (!$result['success']) {

            return response()->json([
                'draw'            => (int) $request->input('draw'),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'error'           => $result['message'],
            ]);
        }

        $data = collect($result['data']);

        /*
         * Filter Puskesmas.
         *
         * API field:
         * nama_pkm
         */
        if ($puskesmas !== '') {

            $data = $data->filter(function ($row) use ($puskesmas) {

                return strcasecmp(
                    trim((string) ($row['nama_pkm'] ?? '')),
                    $puskesmas
                ) === 0;

            })->values();
        }

        /*
         * Search DataTables.
         */
        $search = trim(
            (string) data_get($request->input('search'), 'value', '')
        );

        if ($search !== '') {

            $searchLower = mb_strtolower($search);

            $data = $data->filter(function ($row) use ($searchLower) {

                $fields = [
                    $row['smiley_kode_sarana'] ?? '',
                    $row['smiley_nama_fasyankes'] ?? '',
                    $row['nama_pkm'] ?? '',
                    $row['nomor_lplpo'] ?? '',
                    $row['tanggal'] ?? '',
                    $row['nama_obat'] ?? '',
                    $row['kode_obat_kfa'] ?? '',
                    $row['satuan'] ?? '',
                ];

                foreach ($fields as $field) {

                    if (
                        str_contains(
                            mb_strtolower((string) $field),
                            $searchLower
                        )
                    ) {
                        return true;
                    }
                }

                return false;
            })->values();
        }

        $recordsFiltered = $data->count();

        /*
         * Data sudah dipaginasi oleh API.
         *
         * Untuk saat ini kita menggunakan jumlah data
         * yang diterima sebagai recordsTotal.
         */
        $recordsTotal = $result['total'];

        return response()->json([
            'draw'            => (int) $request->input('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data->values()->all(),
        ]);
    }
}
