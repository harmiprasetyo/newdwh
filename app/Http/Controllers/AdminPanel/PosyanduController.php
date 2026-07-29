<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\MasterPosyandu;
use App\Models\Master\MasterFaskes as MasterFasyankes;

use Illuminate\Http\Request;

use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class PosyanduController extends Controller
{
    public function index()
    {
        return view('adminpanel.posyandu.index');
    }

    public function data(Request $request)
{
    $query = MasterPosyandu::query()
        ->leftJoin(
            'indonesia_provinces',
            'master_posyandu.province_code',
            '=',
            'indonesia_provinces.code'
        )
        ->leftJoin(
            'indonesia_cities',
            'master_posyandu.city_code',
            '=',
            'indonesia_cities.code'
        )
        ->leftJoin(
            'indonesia_districts',
            'master_posyandu.district_code',
            '=',
            'indonesia_districts.code'
        )
        ->leftJoin(
            'indonesia_villages',
            'master_posyandu.village_code',
            '=',
            'indonesia_villages.code'
        )
        ->leftJoin(
            'master_faskes',
            'master_posyandu.kodeFaskes',
            '=',
            'master_faskes.kodeFaskes'
        )
        ->select([
            'master_posyandu.*',

            'indonesia_provinces.name as province_name',
            'indonesia_cities.name as city_name',
            'indonesia_districts.name as district_name',
            'indonesia_villages.name as village_name',

            'master_faskes.namaFaskes'
        ]);

    return datatables()
        ->of($query)

        ->addColumn('status', function($row){

            return $row->isActive
                ? '<span class="badge bg-success">Aktif</span>'
                : '<span class="badge bg-danger">Non Aktif</span>';

        })

        ->addColumn('action', function($row){

            return '
                <a href="/adminpanel/posyandu/edit/'.$row->id.'"
                   class="btn btn-warning btn-sm">
                    Edit
                </a>

                <button
                    class="btn btn-danger btn-sm btn-delete"
                    data-id="'.$row->id.'">
                    Hapus
                </button>
            ';
        })

        ->rawColumns([
            'status',
            'action'
        ])

        ->make(true);
}

public function destroy($id)
{
    MasterPosyandu::findOrFail($id)->delete();

    return response()->json([
        'success'=>true
    ]);
}

public function create()
{
    return view(
        'adminpanel.posyandu.create'
    );
}


    public function provinces()
    {
        return Province::orderBy('name')
            ->get(['code','name']);
    }

    public function cities(Request $request)
    {
        $data = City::where(
                'province_code',
                $request->province_code
            )
            ->orderBy('name')
            ->get([
                'code',
                'name'
            ]);

        return response()->json([
            'data'=>$data
        ]);
    }

    public function districts(Request $request)
    {
        $data = District::where(
                'city_code',
                $request->city_code
            )
            ->orderBy('name')
            ->get([
                'code',
                'name'
            ]);

        return response()->json([
            'data'=>$data
        ]);
    }

    public function villages(Request $request)
    {
        $data = Village::where(
                'district_code',
                $request->district_code
            )
            ->orderBy('name')
            ->get([
                'code',
                'name'
            ]);

        return response()->json([
            'data'=>$data
        ]);
    }

    public function faskes(Request $request)
    {
        $data = MasterFasyankes::where(
                'kodeKecamatan',
                $request->district_code
            )
            ->orderBy('namaFaskes')
            ->get([
                'kodeFaskes',
                'namaFaskes'
            ]);

        return response()->json([
            'data'=>$data
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([

            'province_code'=>'required',
            'city_code'=>'required',
            'district_code'=>'required',
            'village_code'=>'required',

            'kodeFaskes'=>'required',

            'kodePosyandu'=>'required|unique:master_posyandu,kodePosyandu',

            'namaPosyandu'=>'required'
        ]);

        MasterPosyandu::create([

            'province_code'=>$request->province_code,
            'city_code'=>$request->city_code,
            'district_code'=>$request->district_code,
            'village_code'=>$request->village_code,

            'kodeFaskes'=>$request->kodeFaskes,

            'kodePosyandu'=>$request->kodePosyandu,
            'namaPosyandu'=>$request->namaPosyandu
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Posyandu berhasil disimpan'
        ]);
    }
}
