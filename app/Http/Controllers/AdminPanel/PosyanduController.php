<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\PosyanduService;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class PosyanduController extends Controller
{
    protected PosyanduService $service;


    public function __construct(
        PosyanduService $service
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
            'adminpanel.posyandu.index',
            [
                'isGroup3' =>
                    $this->service->isGroup3(auth()->user()),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    public function data(Request $request)
    {
        $query = $this->service
            ->getDatatableQuery(auth()->user());

        return datatables()
            ->of($query)

            ->addColumn('status', function ($row) {

                return $row->isActive
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-danger">Non Aktif</span>';
            })

            ->addColumn('action', function ($row) {

                return '
                    <a
                        href="/adminpanel/posyandu/edit/' . $row->id . '"
                        class="btn btn-warning btn-sm"
                    >
                        Edit
                    </a>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm btn-delete"
                        data-id="' . $row->id . '"
                    >
                        Hapus
                    </button>
                ';
            })

            ->rawColumns([
                'status',
                'action',
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
        $data = $this->service
            ->getCreateData(auth()->user());

        return view(
            'adminpanel.posyandu.create',
            $data
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROVINCES
    |--------------------------------------------------------------------------
    */

    public function provinces()
    {
        return response()->json([
            'data' => $this->service->getProvinces(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CITIES
    |--------------------------------------------------------------------------
    */

    public function cities(Request $request)
    {
        $request->validate([
            'province_code' => 'required',
        ]);

        return response()->json([
            'data' => $this->service->getCities(
                $request->province_code
            ),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DISTRICTS
    |--------------------------------------------------------------------------
    */

    public function districts(Request $request)
    {
        $request->validate([
            'city_code' => 'required',
        ]);

        return response()->json([
            'data' => $this->service->getDistricts(
                $request->city_code
            ),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | VILLAGES
    |--------------------------------------------------------------------------
    */

    public function villages(Request $request)
    {
        $request->validate([
            'district_code' => 'required',
        ]);

        return response()->json([
            'data' => $this->service->getVillages(
                $request->district_code,
                auth()->user()
            ),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | FASKES
    |--------------------------------------------------------------------------
    */

    public function faskes(Request $request)
    {
        return response()->json([
            'data' => $this->service->getFaskes(
                $request->district_code,
                auth()->user()
            ),
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
                'unique:master_posyandu,kodePosyandu',
            ],

            'namaPosyandu' => [
                'required',
                'string',
            ],

            'village_code' => [
                'required',
            ],
        ]);

        try {

            $this->service->store(
                $request->all(),
                auth()->user()
            );

            return response()->json([
                'success' => true,
                'message' => 'Posyandu berhasil disimpan.',
            ]);

        } catch (\InvalidArgumentException $e) {

            throw ValidationException::withMessages([
                'village_code' => $e->getMessage(),
            ]);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Posyandu gagal disimpan.',
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
        $this->service->delete(
            $id,
            auth()->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Posyandu berhasil dihapus.',
        ]);
    }



    /*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/

public function edit($id)
{
    $data = $this->service->getEditData(
        $id,
        auth()->user()
    );

    return view(
        'adminpanel.posyandu.edit',
        $data
    );
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
            Rule::unique(
                'master_posyandu',
                'kodePosyandu'
            )->ignore($id),
        ],

        'namaPosyandu' => [
            'required',
            'string',
        ],

        'village_code' => [
            'required',
        ],

    ]);


    try {

        $this->service->update(
            $id,
            $request->all(),
            auth()->user()
        );


        return response()->json([

            'success' => true,

            'message' =>
                'Posyandu berhasil diperbarui.',

        ]);


    } catch (\InvalidArgumentException $e) {

        throw ValidationException::withMessages([

            'village_code' =>
                $e->getMessage(),

        ]);


    } catch (\Throwable $e) {

        report($e);


        return response()->json([

            'success' => false,

            'message' =>
                'Posyandu gagal diperbarui.',

        ], 500);
    }
}

}
