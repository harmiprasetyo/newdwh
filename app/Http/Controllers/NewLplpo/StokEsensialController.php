<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Models\NewLplpo\StokMinimalObat;
use App\Models\NewLplpo\MasterObat;
use App\Models\Master\MasterFaskes;
use Illuminate\Validation\ValidationException;

class StokEsensialController extends Controller
{

public function setting(Request $request)
{
    $request->validate([
        'kode_obat'  => 'required|string',
        'kodeFaskes' => 'required|string',
        'tahun'      => 'required|integer',
    ]);


    $data = StokMinimalObat::query()

        ->where(
            'kode_obat',
            $request->kode_obat
        )

        ->where(
            'kodeFaskes',
            $request->kodeFaskes
        )

        ->where(
            'tahun',
            $request->tahun
        )

        ->first();


    /*
    |--------------------------------------------------------------------------
    | BELUM ADA SETTING
    |--------------------------------------------------------------------------
    */

    if (!$data) {

        return response()->json([

            'exists' => false,

            'stok_minimal' => 0,

            'stok_optimum' => 0,

            'obat_esensial' => 'noe',

            'obat_formularium_puskesmas' => 'false',

        ]);

    }


    /*
    |--------------------------------------------------------------------------
    | SETTING DITEMUKAN
    |--------------------------------------------------------------------------
    */

    return response()->json([

        'exists' => true,

        'stok_minimal' =>
            $data->stok_minimal ?? 0,

        'stok_optimum' =>
            $data->stok_optimum ?? 0,

        'obat_esensial' =>
            $data->obat_esensial ?? 'noe',

        'obat_formularium_puskesmas' =>
            $data->obat_formularium_puskesmas ?? 'false',

    ]);
}


public function duplicate(Request $request)
{
    $request->validate([
        'dari_tahun' => [
            'required',
            'integer',
            'min:2000'
        ],

        'ke_tahun' => [
            'required',
            'integer',
            'min:2000'
        ],
    ]);


    $dariTahun = (int) $request->dari_tahun;
    $keTahun   = (int) $request->ke_tahun;


    if ($dariTahun === $keTahun) {

        throw ValidationException::withMessages([
            'ke_tahun' =>
                'Tahun sumber dan tahun tujuan tidak boleh sama.'
        ]);

    }


    $user = auth()->user();


    /*
    |--------------------------------------------------------------------------
    | TENTUKAN FASKES
    |--------------------------------------------------------------------------
    */

    if (
        in_array(
            (int) $user->groupid,
            [3, 5]
        )
    ) {

        $kodeFaskes = $user->kodeFaskes;

    } else {

        /*
        |--------------------------------------------------------------------------
        | Untuk Dinkes sebaiknya jangan langsung melakukan
        | duplikasi semua faskes tanpa filter.
        |--------------------------------------------------------------------------
        */

        $kodeFaskes = $request->kodeFaskes;

        if (!$kodeFaskes) {

            throw ValidationException::withMessages([
                'kodeFaskes' =>
                    'Faskes harus dipilih.'
            ]);

        }

    }


    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA TAHUN SUMBER
        |--------------------------------------------------------------------------
        */

        $query = DB::table(
            'master_stokminimal_obat'
        )
        ->where(
            'tahun',
            $dariTahun
        )
        ->where(
            'kodeFaskes',
            $kodeFaskes
        );


        $sourceData = $query->get();


        if ($sourceData->isEmpty()) {

            throw ValidationException::withMessages([
                'dari_tahun' =>
                    "Tidak ditemukan data Stok & Esensial tahun {$dariTahun}."
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | CEK DATA TUJUAN
        |--------------------------------------------------------------------------
        */

        $existingCount = DB::table(
            'master_stokminimal_obat'
        )
        ->where(
            'tahun',
            $keTahun
        )
        ->where(
            'kodeFaskes',
            $kodeFaskes
        )
        ->count();


        /*
        |--------------------------------------------------------------------------
        | INSERT / UPDATE
        |--------------------------------------------------------------------------
        */

        $inserted = 0;
        $updated  = 0;


        foreach ($sourceData as $source) {

            $data = [

                'kode_obat' =>
                    $source->kode_obat,

                'kodeFaskes' =>
                    $source->kodeFaskes,

                'stok_minimal' =>
                    $source->stok_minimal,

                'stok_optimum' =>
                    $source->stok_optimum,

                'obat_esensial' =>
                    $source->obat_esensial,

                'obat_formularium_puskesmas' =>
                    $source->obat_formularium_puskesmas,

                'tahun' =>
                    $keTahun,

                'updated_at' =>
                    now(),

            ];


            /*
            |--------------------------------------------------------------------------
            | UNIQUE:
            | kode_obat + kodeFaskes + tahun
            |--------------------------------------------------------------------------
            */

            $exists = DB::table(
                'master_stokminimal_obat'
            )
            ->where(
                'kode_obat',
                $source->kode_obat
            )
            ->where(
                'kodeFaskes',
                $source->kodeFaskes
            )
            ->where(
                'tahun',
                $keTahun
            )
            ->first();


            if ($exists) {

                DB::table(
                    'master_stokminimal_obat'
                )
                ->where(
                    'id',
                    $exists->id
                )
                ->update($data);

                $updated++;

            } else {

                $data['created_at'] = now();

                DB::table(
                    'master_stokminimal_obat'
                )
                ->insert($data);

                $inserted++;

            }

        }


        DB::commit();


        return response()->json([

            'success' => true,

            'message' =>
                "Duplikasi berhasil. " .
                "{$inserted} data ditambahkan dan " .
                "{$updated} data diperbarui " .
                "dari tahun {$dariTahun} ke {$keTahun}.",

            'dari_tahun' =>
                $dariTahun,

            'ke_tahun' =>
                $keTahun,

            'total_source' =>
                $sourceData->count(),

            'inserted' =>
                $inserted,

            'updated' =>
                $updated,

        ]);

    } catch (
        ValidationException $e
    ) {

        DB::rollBack();

        throw $e;

    } catch (\Throwable $e) {

        DB::rollBack();

        report($e);

        return response()->json([

            'success' => false,

            'message' =>
                'Duplikasi gagal: ' .
                $e->getMessage()

        ], 500);

    }
}
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | FASKES
        |--------------------------------------------------------------------------
        */

        $faskes = MasterFaskes::query()
            ->when(
                $user->groupid != 1 &&
                $user->groupid != 2,
                function ($query) use ($user) {

                    $query->where(
                        'kodeFaskes',
                        $user->kodeFaskes
                    );

                }
            )
            ->orderBy('namaFaskes')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TAHUN
        |--------------------------------------------------------------------------
        */

        $tahunSekarang = now()->year;

        $tahunList = range(
            $tahunSekarang - 5,
            $tahunSekarang + 1
        );


        return view(
            'newlplpo.stok-esensial.index',
            compact(
                'faskes',
                'tahunList'
            )
        );
    }


 /*
|--------------------------------------------------------------------------
| DATATABLE
|--------------------------------------------------------------------------
*/

public function datatable(Request $request)
{
    $user = auth()->user();

    $query = StokMinimalObat::query()

        ->from('master_stokminimal_obat as s')

        ->leftJoin(
            'master_obat as o',
            'o.kode_obat',
            '=',
            's.kode_obat'
        )

        ->leftJoin(
            'master_faskes as f',
            'f.kodeFaskes',
            '=',
            's.kodeFaskes'
        )

        ->select([
            's.id',
            's.kode_obat',
            's.kodeFaskes',
            's.stok_minimal',
            's.stok_optimum',
            's.obat_esensial',
            's.obat_formularium_puskesmas',
            's.tahun',

            'o.nama_obat',

            'f.namaFaskes',
        ]);


    /*
    |--------------------------------------------------------------------------
    | BATASI FASKES GROUP 3 / 4 / 5
    |--------------------------------------------------------------------------
    |
    | Group 3, 4, dan 5 hanya boleh melihat data milik
    | faskes user yang sedang login.
    |
    */

    if (
        in_array(
            (int) $user->groupid,
            [3, 4, 5],
            true
        )
    ) {

        $query->where(
            's.kodeFaskes',
            $user->kodeFaskes
        );

    }


    /*
    |--------------------------------------------------------------------------
    | FILTER TAHUN
    |--------------------------------------------------------------------------
    */

    if ($request->filled('tahun')) {

        $query->where(
            's.tahun',
            $request->tahun
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    return datatables()
        ->of($query)

        ->addIndexColumn()


        /*
        |--------------------------------------------------------------------------
        | OBAT
        |--------------------------------------------------------------------------
        */

        ->addColumn(
            'obat',
            function ($row) {

                return $row->nama_obat ?? '-';

            }
        )


        /*
        |--------------------------------------------------------------------------
        | FASKES
        |--------------------------------------------------------------------------
        */

        ->addColumn(
            'faskes',
            function ($row) {

                return $row->namaFaskes ?? '-';

            }
        )


        /*
        |--------------------------------------------------------------------------
        | AKSI
        |--------------------------------------------------------------------------
        */

        ->addColumn(
            'aksi',
            function ($row) {

                return '
                    <div class="btn-group btn-group-sm">

                        <button
                            type="button"
                            class="btn btn-warning btn-edit"
                            data-id="' . $row->id . '">

                            <i class="bi bi-pencil-square"></i>

                        </button>

                        <button
                            type="button"
                            class="btn btn-danger btn-delete"
                            data-id="' . $row->id . '">

                            <i class="bi bi-trash"></i>

                        </button>

                    </div>
                ';

            }
        )


        /*
        |--------------------------------------------------------------------------
        | OBAT ESENSIAL
        |--------------------------------------------------------------------------
        */

        ->editColumn(
            'obat_esensial',
            function ($row) {

                if ($row->obat_esensial === 'oe') {

                    return '
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>
                            Esensial
                        </span>
                    ';

                }

                return '
                    <span class="badge bg-secondary">
                        <i class="bi bi-dash-circle me-1"></i>
                        Non Esensial
                    </span>
                ';

            }
        )


        /*
        |--------------------------------------------------------------------------
        | FORMULARIUM PUSKESMAS
        |--------------------------------------------------------------------------
        */

        ->editColumn(
            'obat_formularium_puskesmas',
            function ($row) {

                if (
                    $row->obat_formularium_puskesmas === 'true'
                ) {

                    return '
                        <span class="badge bg-success">
                            Ya
                        </span>
                    ';

                }

                return '
                    <span class="badge bg-secondary">
                        Tidak
                    </span>
                ';

            }
        )


        /*
        |--------------------------------------------------------------------------
        | RAW COLUMNS
        |--------------------------------------------------------------------------
        */

        ->rawColumns([
            'aksi',
            'obat_esensial',
            'obat_formularium_puskesmas'
        ])

        ->make(true);
}

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = auth()->user();


        $rules = [

            'kode_obat' => [
                'required',
                'string',
                'max:50',
            ],

            'kodeFaskes' => [
                'required',
                'string',
                'max:255',
            ],

            'stok_minimal' => [
                'required',
                'integer',
                'min:0',
            ],

            'stok_optimum' => [
                'required',
                'integer',
                'min:0',
                'gte:stok_minimal',
            ],

            'obat_esensial' => [
                'required',
                Rule::in([
                    'oe',
                    'noe'
                ])
            ],
             'obat_formularium_puskesmas' => [
                'required',
                Rule::in([
                    'true',
                    'false'
                ])
            ],

            'tahun' => [
                'required',
                'integer',
                'min:2000',
                'max:2100'
            ],

        ];


        $validated = $request->validate($rules);


        /*
        |--------------------------------------------------------------------------
        | USER NON ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $user->groupid != 1 &&
            $user->groupid != 2
        ) {

            if (
                $validated['kodeFaskes']
                !=
                $user->kodeFaskes
            ) {

                return response()->json([

                    'success' => false,

                    'message' =>
                        'Anda tidak memiliki akses ke faskes tersebut.'

                ], 403);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | CEK DUPLICATE
        |--------------------------------------------------------------------------
        */

        $exists = StokMinimalObat::where(
                'kode_obat',
                $validated['kode_obat']
            )

            ->where(
                'kodeFaskes',
                $validated['kodeFaskes']
            )

            ->where(
                'tahun',
                $validated['tahun']
            )

            ->exists();


        if ($exists) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Setting obat untuk faskes dan tahun tersebut sudah tersedia.'

            ], 422);

        }


        DB::beginTransaction();

        try {

            $data = StokMinimalObat::create(
                $validated
            );

            DB::commit();


            return response()->json([

                'success' => true,

                'message' =>
                    'Data stok obat berhasil ditambahkan.',

                'data' => $data

            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([

                'success' => false,

                'message' =>
                    'Gagal menyimpan data.',

                'error' =>
                    $e->getMessage()

            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $user = auth()->user();


        $query = StokMinimalObat::query()
            ->with([
                'obat',
                'faskes'
            ])
            ->where('id', $id);


        /*
        |--------------------------------------------------------------------------
        | BATASI USER PUSKESMAS
        |--------------------------------------------------------------------------
        */

        if (
            $user->groupid != 1 &&
            $user->groupid != 2
        ) {

            $query->where(
                'kodeFaskes',
                $user->kodeFaskes
            );

        }


        $data = $query->firstOrFail();


        return response()->json([

            'success' => true,

            'data' => $data

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $user = auth()->user();


        $query = StokMinimalObat::query()
            ->where('id', $id);


        if (
            $user->groupid != 1 &&
            $user->groupid != 2
        ) {

            $query->where(
                'kodeFaskes',
                $user->kodeFaskes
            );

        }


        $data = $query->firstOrFail();


        $validated = $request->validate([

            'kode_obat' => [
                'required',
                'string',
                'max:50',
            ],

            'kodeFaskes' => [
                'required',
                'string',
                'max:255',
            ],

            'stok_minimal' => [
                'required',
                'integer',
                'min:0',
            ],

            'stok_optimum' => [
                'required',
                'integer',
                'min:0',
                'gte:stok_minimal',
            ],

            'obat_esensial' => [
                'required',
                Rule::in([
                    'oe',
                    'noe'
                ])
            ],
                'obat_formularium_puskesmas' => [
                    'required',
                    Rule::in([
                        'true',
                        'false'
                    ])
                ],

            'tahun' => [
                'required',
                'integer',
                'min:2000',
                'max:2100'
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | BATASI FASKES
        |--------------------------------------------------------------------------
        */

        if (
            $user->groupid != 1 &&
            $user->groupid != 2
        ) {

            $validated['kodeFaskes'] =
                $user->kodeFaskes;

        }


        /*
        |--------------------------------------------------------------------------
        | DUPLICATE
        |--------------------------------------------------------------------------
        */

        $duplicate = StokMinimalObat::where(
                'kode_obat',
                $validated['kode_obat']
            )

            ->where(
                'kodeFaskes',
                $validated['kodeFaskes']
            )

            ->where(
                'tahun',
                $validated['tahun']
            )

            ->where(
                'id',
                '!=',
                $id
            )

            ->exists();


        if ($duplicate) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Setting obat untuk faskes dan tahun tersebut sudah tersedia.'

            ], 422);

        }


        DB::beginTransaction();

        try {

            $data->update(
                $validated
            );

            DB::commit();


            return response()->json([

                'success' => true,

                'message' =>
                    'Data stok obat berhasil diperbarui.',

                'data' => $data

            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([

                'success' => false,

                'message' =>
                    'Gagal memperbarui data.',

                'error' =>
                    $e->getMessage()

            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $user = auth()->user();


        $query = StokMinimalObat::query()
            ->where('id', $id);


        if (
            $user->groupid != 1 &&
            $user->groupid != 2
        ) {

            $query->where(
                'kodeFaskes',
                $user->kodeFaskes
            );

        }


        $data = $query->firstOrFail();


        try {

            $data->delete();


            return response()->json([

                'success' => true,

                'message' =>
                    'Data berhasil dihapus.'

            ]);

        } catch (\Throwable $e) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Data gagal dihapus.',

                'error' =>
                    $e->getMessage()

            ], 500);
        }
    }
}
