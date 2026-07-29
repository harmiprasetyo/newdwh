<?php

namespace App\Http\Controllers\AdminPanel\Master;

use App\Http\Controllers\Controller;
use App\Models\TargetSasaran;
use App\Models\MasterPosyandu;
use App\Services\TargetSasaranService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TargetSasaranController extends Controller
{

    protected $service;

    public function __construct(TargetSasaranService $service)
    {
        $this->service=$service;
    }

   public function index()
{
    return view(
        'adminpanel.master.target-sasaran.index',
        [
            'posyandu' => $this->service->getPosyandu()
        ]
    );
}

    public function datatable(Request $request)
{
    $query = TargetSasaran::query();

    // Filter Posyandu
    if ($request->filled('posyandu')) {
        $query->where('posyandu_id', $request->posyandu);
    }

    // Filter Bulan
    if ($request->filled('bulan')) {
        $query->where('bulan', $request->bulan);
    }

    // Filter Tahun
    if ($request->filled('tahun')) {
        $query->where('tahun', $request->tahun);
    }

    return DataTables::of($query)

        ->addIndexColumn()

        ->editColumn('bulan', function ($row) {
            return $row->nama_bulan;
        })

        ->addColumn('posyandu', function ($row) {
            return $row->namaPosyandu;
            // atau $row->posyandu->namaPosyandu jika tidak menyimpan snapshot
        })

        ->addColumn('action', function ($row) {
            return view(
                'adminpanel.master.target-sasaran.action',
                compact('row')
            );
        })

        ->rawColumns(['action'])

        ->make(true);
}

    public function create()
    {

        $posyandu=MasterPosyandu::where('isActive',1)
                    ->orderBy('namaPosyandu')
                    ->get();

        return view(
            'adminpanel.master.target-sasaran.create',
            compact('posyandu')
        );

    }

    public function store(Request $request)
    {


        $request->validate([

            'posyandu_id'=>'required|exists:master_posyandu,id',

            'bulan'=>'required',

            'tahun'=>'required',

            'rw'=>'required',

            'rt'=>'required',

            'sasaran_ibu_hamil'=>'required|numeric|min:0',

            'sasaran_ibu_melahirkan'=>'required|numeric|min:0',

            'sasaran_bayi_baru_lahir'=>'required|numeric|min:0'

        ]);

        $result=$this->service->store(
            $request->all()
        );


        if($result['success']){

            return redirect()

                ->route('master.target-sasaran.index')

                ->with('success',$result['message']);

        }

        return back()

            ->withInput()

            ->with('error',$result['message']);

    }

    public function edit(TargetSasaran $target_sasaran)
    {

        $posyandu=MasterPosyandu::where('isActive',1)
                    ->orderBy('namaPosyandu')
                    ->get();

        return view(

            'adminpanel.master.target-sasaran.edit',

            compact(
                'target_sasaran',
                'posyandu'
            )

        );

    }

    public function update(Request $request,TargetSasaran $target_sasaran)
    {

        $request->validate([

            'posyandu_id'=>'required',

            'bulan'=>'required',

            'tahun'=>'required',

            'rw'=>'required',

            'rt'=>'required',

            'sasaran_ibu_hamil'=>'required|numeric',

            'sasaran_ibu_melahirkan'=>'required|numeric',

            'sasaran_bayi_baru_lahir'=>'required|numeric'

        ]);

        $result=$this->service->update(

            $target_sasaran,

            $request->all()

        );

        return redirect()

            ->route('adminpanel.master.target-sasaran.index')

            ->with(

                $result['success']
                    ? 'success'
                    : 'error',

                $result['message']

            );

    }

    public function destroy(TargetSasaran $target_sasaran)
    {

        $result=$this->service->delete($target_sasaran);

        return response()->json($result);

    }

}
