<?php

namespace App\Http\Controllers\AdminPanel\WilayahKerja;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\WilayahKerjaPosyanduService;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WilayahKerjaPosyanduController extends Controller
{
    protected WilayahKerjaPosyanduService $service;


    public function __construct(
        WilayahKerjaPosyanduService $service
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
            'adminpanel.wilayahkerja.index',
            [
                'isGroup3' =>
                    $this->service->isGroup3($user),

                'posyandu' =>
                    $this->service->getPosyandu($user),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    public function datatable()
    {
        $query = $this->service
            ->getDatatableQuery(auth()->user());


        return DataTables::of($query)

            ->addIndexColumn()


            ->addColumn(
                'nama_posyandu',
                function ($row) {

                    return $row->namaPosyandu ?? '-';
                }
            )


            ->addColumn(
                'desa',
                function ($row) {

                    return $row->village_name ?? '-';
                }
            )


            ->addColumn(
                'kecamatan',
                function ($row) {

                    return $row->district_name ?? '-';
                }
            )


            ->addColumn(
                'kabupaten',
                function ($row) {

                    return $row->city_name ?? '-';
                }
            )


            ->addColumn(
                'provinsi',
                function ($row) {

                    return $row->province_name ?? '-';
                }
            )


            ->editColumn(
                'rw',
                function ($row) {

                    if (!$row->rw) {
                        return '-';
                    }


                    return collect(
                        explode(',', $row->rw)
                    )
                        ->filter()
                        ->map(function ($rw) {

                            return
                                '<span class="badge bg-primary me-1">'
                                . e(trim($rw))
                                . '</span>';

                        })
                        ->implode(' ');
                }
            )


            ->addColumn(
                'aksi',
                function ($row) {

                    return '
                        <div class="action-wrapper">

                            <button
                                type="button"
                                class="btn btn-warning btn-action btnEdit"
                                data-id="' . $row->id . '"
                                data-url="' .
                                    route(
                                        'adminpanel.posyandu.wilayahkerja.edit',
                                        $row->id
                                    ) . '"
                                title="Edit Data">

                                <i class="fas fa-edit"></i>

                            </button>


                            <button
                                type="button"
                                class="btn btn-danger btn-action btnDelete"
                                data-id="' . $row->id . '"
                                data-url="' .
                                    route(
                                        'adminpanel.posyandu.wilayahkerja.destroy',
                                        $row->id
                                    ) . '"
                                title="Hapus Data">

                                <i class="fas fa-trash-alt"></i>

                            </button>

                        </div>
                    ';
                }
            )


            ->rawColumns([
                'rw',
                'aksi',
            ])

            ->make(true);
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return response()->json([
            'success' => true,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'kodePosyandu' => [
                'required',
                'string',
            ],

            'rw' => [
                'nullable',
                'string',
            ],

        ]);


        try {

            $this->service->store(
                $request->only([
                    'kodePosyandu',
                    'rw',
                ]),
                auth()->user()
            );


            return response()->json([

                'success' => true,

                'message' =>
                    'Data berhasil disimpan.',

            ]);


        } catch (\Throwable $e) {

            report($e);


            return response()->json([

                'success' => false,

                'message' =>
                    'Data gagal disimpan.',

            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $data = $this->service->find(
            $id,
            auth()->user()
        );


        return response()->json($data);
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

        $request->validate([

            'kodePosyandu' => [
                'required',
                'string',
            ],

            'rw' => [
                'nullable',
                'string',
            ],

        ]);


        try {

            $this->service->update(

                $id,

                $request->only([
                    'kodePosyandu',
                    'rw',
                ]),

                auth()->user()

            );


            return response()->json([

                'success' => true,

                'message' =>
                    'Data berhasil diperbarui.',

            ]);


        } catch (\Throwable $e) {

            report($e);


            return response()->json([

                'success' => false,

                'message' =>
                    'Data gagal diperbarui.',

            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        try {

            $this->service->delete(
                $id,
                auth()->user()
            );


            return response()->json([

                'success' => true,

                'message' =>
                    'Data berhasil dihapus.',

            ]);


        } catch (\Throwable $e) {

            report($e);


            return response()->json([

                'success' => false,

                'message' =>
                    'Data gagal dihapus.',

            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SELECT POSYANDU
    |--------------------------------------------------------------------------
    */

    public function selectPosyandu(Request $request)
    {
        $data = $this->service->getPosyandu(
            auth()->user(),
            $request->q
        );


        return response()->json(
            $data->map(function ($item) {

                return [

                    'id' =>
                        $item->kodePosyandu,

                    'text' =>
                        $item->namaPosyandu,

                ];

            })
        );
    }
}
