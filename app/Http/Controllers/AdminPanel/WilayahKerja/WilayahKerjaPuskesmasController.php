<?php

namespace App\Http\Controllers\AdminPanel\WilayahKerja;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\WilayahKerjaPuskesmasService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

use Yajra\DataTables\Facades\DataTables;

use App\Models\WilayahKerja\WilayahKerjaPuskesmas;

class WilayahKerjaPuskesmasController extends Controller
{
    protected WilayahKerjaPuskesmasService $service;

    public function __construct(
        WilayahKerjaPuskesmasService $service
    ) {
        $this->service = $service;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = auth()->user();

        return view(
            'adminpanel.wilayahkerja.puskesmas.index',
            [
                'isGroup3' =>
                    $this->service->isGroup3($user),
            ]
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

        $query =
            $this->service->getData(
                $request->only([
                    'kodeFaskes',
                    'kodeDesa',
                ]),
                $user
            );


        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn(
                'namaFaskes',
                fn ($row) =>
                    $row->faskes?->namaFaskes ?? '-'
            )

            ->addColumn(
                'namaDesa',
                fn ($row) =>
                    $row->desa?->name ?? '-'
            )

            ->addColumn(
                'kecamatan',
                fn ($row) =>
                    $row->desa
                        ?->district
                        ?->name ?? '-'
            )

            ->addColumn(
                'kota',
                fn ($row) =>
                    $row->desa
                        ?->district
                        ?->city
                        ?->name ?? '-'
            )

            ->addColumn(
                'provinsi',
                fn ($row) =>
                    $row->desa
                        ?->district
                        ?->city
                        ?->province
                        ?->name ?? '-'
            )

            ->addColumn(
                'action',
                function ($row) {

                    return '
                        <div class="d-flex justify-content-center gap-1">

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary btn-edit"
                                data-id="' . $row->id . '"
                                title="Edit"
                            >
                                <i class="fas fa-edit"></i>
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger btn-delete"
                                data-id="' . $row->id . '"
                                title="Hapus"
                            >
                                <i class="fas fa-trash"></i>
                            </button>

                        </div>
                    ';
                }
            )

            ->rawColumns([
                'action',
            ])

            ->make(true);
    }


    /*
    |--------------------------------------------------------------------------
    | FASKES
    |--------------------------------------------------------------------------
    */

    public function faskes(Request $request)
    {
        $data =
            $this->service->getFaskes(
                auth()->user(),
                $request->q
            );

        return response()->json(
            $data->map(function ($item) {

                return [
                    'id' =>
                        $item->kodeFaskes,

                    'text' =>
                        $item->namaFaskes,

                    'kodePropinsi' =>
                        $item->kodePropinsi,

                    'kodeKabupaten' =>
                        $item->kodeKabupaten,

                    'kodeKecamatan' =>
                        $item->kodeKecamatan,
                ];

            })
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DESA BERDASARKAN FASKES
    |--------------------------------------------------------------------------
    */

    public function desaByFaskes(
        Request $request
    ) {

        $request->validate([
            'kodeFaskes' => [
                'required',
                'string',
            ],
        ]);


        $data =
            $this->service->getDesaByFaskes(
                $request->kodeFaskes,
                auth()->user()
            );


        return response()->json(
            $data->map(function ($item) {

                return [
                    'id' =>
                        $item->code,

                    'text' =>
                        $item->name,
                ];

            })
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $data =
            $this->service->find(
                $id,
                auth()->user()
            );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = auth()->user();


        $validated =
            $request->validate([

                'kodeFaskes' => [
                    'nullable',
                    'string',
                    'max:20',
                ],

                'kodeDesa' => [
                    'required',
                    'string',
                    'max:20',
                    'exists:indonesia_villages,code',

                    Rule::unique(
                        'master_wilayah_kerja_puskesmas',
                        'kodeDesa'
                    ),
                ],

            ], [

                'kodeDesa.required' =>
                    'Desa wajib dipilih.',

                'kodeDesa.exists' =>
                    'Desa tidak ditemukan.',

                'kodeDesa.unique' =>
                    'Desa tersebut sudah memiliki wilayah kerja Puskesmas.',
            ]);


        try {

            $data =
                $this->service->create(
                    $validated,
                    $user
                );


            return response()->json([

                'success' =>
                    true,

                'message' =>
                    'Wilayah kerja Puskesmas berhasil ditambahkan.',

                'data' =>
                    $data,

            ]);

        } catch (\Throwable $e) {

            Log::error(
                'Gagal CREATE Wilayah Kerja Puskesmas.',
                [
                    'payload' =>
                        $validated,

                    'user_id' =>
                        auth()->id(),

                    'error' =>
                        $e->getMessage(),

                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine(),
                ]
            );


            return response()->json([

                'success' =>
                    false,

                'message' =>
                    $e->getMessage(),

            ], 500);
        }
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


        $wilayah =
            $this->service->find(
                $id,
                $user
            );


        $validated =
            $request->validate([

                'kodeFaskes' => [
                    'nullable',
                    'string',
                    'max:20',
                ],

                'kodeDesa' => [
                    'required',
                    'string',
                    'max:20',
                    'exists:indonesia_villages,code',

                    Rule::unique(
                        'master_wilayah_kerja_puskesmas',
                        'kodeDesa'
                    )->ignore($wilayah->id),
                ],

            ], [

                'kodeDesa.required' =>
                    'Desa wajib dipilih.',

                'kodeDesa.exists' =>
                    'Desa tidak ditemukan.',

                'kodeDesa.unique' =>
                    'Desa tersebut sudah memiliki wilayah kerja Puskesmas lain.',
            ]);


        try {

            $data =
                $this->service->update(
                    $wilayah,
                    $validated,
                    $user
                );


            return response()->json([

                'success' =>
                    true,

                'message' =>
                    'Wilayah kerja Puskesmas berhasil diperbarui.',

                'data' =>
                    $data,

            ]);

        } catch (\Throwable $e) {

            Log::error(
                'Gagal UPDATE Wilayah Kerja Puskesmas.',
                [
                    'id' =>
                        $id,

                    'payload' =>
                        $validated,

                    'user_id' =>
                        auth()->id(),

                    'error' =>
                        $e->getMessage(),
                ]
            );


            return response()->json([

                'success' =>
                    false,

                'message' =>
                    $e->getMessage(),

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


        try {

            $wilayah =
                $this->service->find(
                    $id,
                    $user
                );


            $this->service->delete(
                $wilayah,
                $user
            );


            return response()->json([

                'success' =>
                    true,

                'message' =>
                    'Wilayah kerja Puskesmas berhasil dihapus.',

            ]);

        } catch (\Illuminate\Database\QueryException $e) {

            Log::error(
                'FK constraint DELETE Wilayah Kerja Puskesmas.',
                [
                    'id' =>
                        $id,

                    'user_id' =>
                        auth()->id(),

                    'error' =>
                        $e->getMessage(),
                ]
            );


            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'Data wilayah kerja Puskesmas tidak dapat dihapus karena masih digunakan oleh data lain.',

            ], 409);

        } catch (\Throwable $e) {

            Log::error(
                'Gagal DELETE Wilayah Kerja Puskesmas.',
                [
                    'id' =>
                        $id,

                    'user_id' =>
                        auth()->id(),

                    'error' =>
                        $e->getMessage(),
                ]
            );


            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'Wilayah kerja Puskesmas tidak dapat dihapus.',

            ], 500);
        }
    }
}
