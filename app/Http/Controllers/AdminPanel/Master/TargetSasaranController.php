<?php

namespace App\Http\Controllers\AdminPanel\Master;

use App\Http\Controllers\Controller;
use App\Models\TargetSasaran;
use App\Services\TargetSasaranService;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TargetSasaranController extends Controller
{
    protected TargetSasaranService $service;


    public function __construct(
        TargetSasaranService $service
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
            'adminpanel.master.target-sasaran.index',
            [
                'posyandu' =>
                    $this->service->getPosyandu($user),

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


        $query = $this->service->getData(
            $user,
            $request->only([
                'posyandu',
                'bulan',
                'tahun',
            ])
        );


        return DataTables::of($query)

            ->addIndexColumn()

            ->editColumn(
                'bulan',
                function ($row) {

                    return $row->nama_bulan;
                }
            )

            ->addColumn(
                'posyandu',
                function ($row) {

                    return $row->namaPosyandu ?? '-';
                }
            )

            ->addColumn(
                'action',
                function ($row) {

                    return view(
                        'adminpanel.master.target-sasaran.action',
                        compact('row')
                    );
                }
            )

            ->rawColumns([
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
        $user = auth()->user();

        return view(
            'adminpanel.master.target-sasaran.create',
            [
                'posyandu' =>
                    $this->service->getPosyandu($user),

                'isGroup3' =>
                    $this->service->isGroup3($user),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'posyandu_id' => [
                'required',
                'exists:master_posyandu,id',
            ],

            'bulan' => [
                'required',
            ],

            'tahun' => [
                'required',
            ],

            'rw' => [
                'required',
            ],

            'rt' => [
                'required',
            ],

            'sasaran_ibu_hamil' => [
                'required',
                'numeric',
                'min:0',
            ],

            'sasaran_ibu_melahirkan' => [
                'required',
                'numeric',
                'min:0',
            ],

            'sasaran_bayi_baru_lahir' => [
                'required',
                'numeric',
                'min:0',
            ],

        ]);


        try {

            $this->service->store(
                $validated,
                auth()->user()
            );


            return redirect()
                ->route(
                    'master.target-sasaran.index'
                )
                ->with(
                    'success',
                    'Data berhasil disimpan.'
                );

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        TargetSasaran $target_sasaran
    ) {

        /*
        |--------------------------------------------------------------------------
        | Pastikan Group 3 hanya bisa edit miliknya
        |--------------------------------------------------------------------------
        */

        $this->service->ensureAllowedTarget(
            $target_sasaran,
            auth()->user()
        );


        return view(
            'adminpanel.master.target-sasaran.edit',
            [
                'target_sasaran' =>
                    $target_sasaran,

                'posyandu' =>
                    $this->service->getPosyandu(
                        auth()->user()
                    ),

                'isGroup3' =>
                    $this->service->isGroup3(
                        auth()->user()
                    ),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        TargetSasaran $target_sasaran
    ) {

        $validated = $request->validate([

            'posyandu_id' => [
                'required',
                'exists:master_posyandu,id',
            ],

            'bulan' => [
                'required',
            ],

            'tahun' => [
                'required',
            ],

            'rw' => [
                'required',
            ],

            'rt' => [
                'required',
            ],

            'sasaran_ibu_hamil' => [
                'required',
                'numeric',
                'min:0',
            ],

            'sasaran_ibu_melahirkan' => [
                'required',
                'numeric',
                'min:0',
            ],

            'sasaran_bayi_baru_lahir' => [
                'required',
                'numeric',
                'min:0',
            ],

        ]);


        try {

            $this->service->update(
                $target_sasaran,
                $validated,
                auth()->user()
            );


            return redirect()
                ->route(
                    'master.target-sasaran.index'
                )
                ->with(
                    'success',
                    'Data berhasil diperbarui.'
                );

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        TargetSasaran $target_sasaran
    ) {

        try {

            $this->service->delete(
                $target_sasaran,
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
                    $e->getMessage(),

            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SELECT POSYANDU
    |--------------------------------------------------------------------------
    */

    public function selectPosyandu(
        Request $request
    ) {

        $data =
            $this->service->getPosyandu(
                auth()->user(),
                $request->q
            );


        return response()->json(

            $data->map(function ($item) {

                return [

                    'id' =>
                        $item->id,

                    'text' =>
                        $item->namaPosyandu,

                ];

            })

        );
    }
}
