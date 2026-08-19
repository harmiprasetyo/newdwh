<?php

namespace App\Http\Controllers\AdminPanel\WilayahKerja;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

use Yajra\DataTables\Facades\DataTables;

use App\Models\WilayahKerja\WilayahKerjaPuskesmas;

use App\Services\AdminPanel\WilayahKerjaPuskesmasService;

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
        return view(
            'adminpanel.wilayahkerja.puskesmas.index'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    public function datatable(Request $request)
    {
        $query = $this->service->getData(
            $request->only([
                'kodeFaskes',
                'kodeDesa',
            ])
        );

        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn(
                'namaFaskes',
                function ($row) {

                    return $row->faskes?->namaFaskes ?? '-';
                }
            )

            ->addColumn(
                'namaDesa',
                function ($row) {

                    return $row->desa?->name ?? '-';
                }
            )

            ->addColumn(
                'kecamatan',
                function ($row) {

                    return $row->desa?->district?->name ?? '-';
                }
            )

            ->addColumn(
                'kota',
                function ($row) {

                    return $row->desa?->district?->city?->name ?? '-';
                }
            )

            ->addColumn(
                'provinsi',
                function ($row) {

                    return $row->desa
                        ?->district
                        ?->city
                        ?->province
                        ?->name ?? '-';
                }
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
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $data = WilayahKerjaPuskesmas::with([
            'faskes',
            'desa.district.city.province',
        ])->findOrFail($id);

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
        $validated = $request->validate([
            'kodeFaskes' => [
                'required',
                'string',
                'max:20',
                'exists:master_faskes,kodeFaskes',
            ],

            'kodeDesa' => [
                'required',
                'string',
                'max:10',
                'exists:indonesia_villages,code',

                Rule::unique(
                    'master_wilayah_kerja_puskesmas',
                    'kodeDesa'
                ),
            ],
        ], [
            'kodeFaskes.required' =>
                'Puskesmas wajib dipilih.',

            'kodeFaskes.exists' =>
                'Puskesmas tidak ditemukan.',

            'kodeDesa.required' =>
                'Desa wajib dipilih.',

            'kodeDesa.exists' =>
                'Desa tidak ditemukan.',

            'kodeDesa.unique' =>
                'Desa tersebut sudah memiliki wilayah kerja Puskesmas.',
        ]);

        try {

            $data = $this->service->create(
                $validated
            );

            return response()->json([
                'success' => true,
                'message' =>
                    'Wilayah kerja Puskesmas berhasil ditambahkan.',
                'data' => $data,
            ]);

        } catch (\Throwable $e) {

            Log::error(
                'Gagal CREATE Wilayah Kerja Puskesmas.',
                [
                    'payload' => $validated,
                    'user_id' => auth()->id(),
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'Gagal menambahkan wilayah kerja Puskesmas.',
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

        $wilayah =
            WilayahKerjaPuskesmas::findOrFail($id);

        $validated = $request->validate([
            'kodeFaskes' => [
                'required',
                'string',
                'max:20',
                'exists:master_faskes,kodeFaskes',
            ],

            'kodeDesa' => [
                'required',
                'string',
                'max:10',
                'exists:indonesia_villages,code',

                Rule::unique(
                    'master_wilayah_kerja_puskesmas',
                    'kodeDesa'
                )->ignore($wilayah->id),
            ],
        ], [
            'kodeFaskes.required' =>
                'Puskesmas wajib dipilih.',

            'kodeFaskes.exists' =>
                'Puskesmas tidak ditemukan.',

            'kodeDesa.required' =>
                'Desa wajib dipilih.',

            'kodeDesa.exists' =>
                'Desa tidak ditemukan.',

            'kodeDesa.unique' =>
                'Desa tersebut sudah memiliki wilayah kerja Puskesmas lain.',
        ]);

        try {

            $data = $this->service->update(
                $wilayah,
                $validated
            );

            return response()->json([
                'success' => true,
                'message' =>
                    'Wilayah kerja Puskesmas berhasil diperbarui.',
                'data' => $data,
            ]);

        } catch (\Throwable $e) {

            Log::error(
                'Gagal UPDATE Wilayah Kerja Puskesmas.',
                [
                    'id' => $id,
                    'payload' => $validated,
                    'user_id' => auth()->id(),
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'Gagal memperbarui wilayah kerja Puskesmas.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Request $request,
        $id
    ) {

        $wilayah =
            WilayahKerjaPuskesmas::findOrFail($id);

        try {

            $this->service->delete($wilayah);

            return response()->json([
                'success' => true,
                'message' =>
                    'Wilayah kerja Puskesmas berhasil dihapus.',
            ]);

        } catch (\Illuminate\Database\QueryException $e) {

            Log::error(
                'FK constraint DELETE Wilayah Kerja Puskesmas.',
                [
                    'id' => $id,
                    'user_id' => auth()->id(),
                    'error' => $e->getMessage(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'Data wilayah kerja Puskesmas tidak dapat dihapus karena masih digunakan oleh data lain.',
            ], 409);

        } catch (\Throwable $e) {

            Log::error(
                'Gagal DELETE Wilayah Kerja Puskesmas.',
                [
                    'id' => $id,
                    'user_id' => auth()->id(),
                    'error' => $e->getMessage(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'Wilayah kerja Puskesmas tidak dapat dihapus.',
            ], 500);
        }
    }
}
