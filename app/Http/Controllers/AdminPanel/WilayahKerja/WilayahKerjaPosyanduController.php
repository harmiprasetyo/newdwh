<?php

namespace App\Http\Controllers\AdminPanel\WilayahKerja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\MasterPosyandu;
use App\Models\AdminPanel\WilayahKerja\WilayahKerjaPosyandu;

use App\Http\Requests\AdminPanel\WilayahKerja\WilayahKerjaPosyanduRequest;



class WilayahKerjaPosyanduController extends Controller
{
    public function index()
{
    $posyandu = MasterPosyandu::orderBy('namaPosyandu')->get();

    return view(
        'adminpanel.wilayahkerja.index',
        compact('posyandu')
    );
}

 public function datatable()
{
    $query = WilayahKerjaPosyandu::with([
        'posyandu',
        'desa.district.city.province'
    ]);

    return DataTables::of($query)

        ->addIndexColumn()

        ->addColumn('nama_posyandu', function ($row) {

            return data_get(
                $row,
                'posyandu.namaPosyandu',
                '-'
            );

        })

        ->addColumn('desa', function ($row) {

            return data_get(
                $row,
                'desa.name',
                '-'
            );

        })

        ->addColumn('kecamatan', function ($row) {

            return data_get(
                $row,
                'desa.district.name',
                '-'
            );

        })

        ->addColumn('kabupaten', function ($row) {

            return data_get(
                $row,
                'desa.district.city.name',
                '-'
            );

        })

        ->addColumn('provinsi', function ($row) {

            return data_get(
                $row,
                'desa.district.city.province.name',
                '-'
            );

        })

        ->editColumn('rw', function ($row) {

            if (!$row->rw) {
                return '-';
            }

            return collect(explode(',', $row->rw))
                ->filter()
                ->map(function ($rw) {

                    return '<span class="badge bg-primary me-1">'
                        . e(trim($rw))
                        . '</span>';

                })
                ->implode(' ');

        })

        ->addColumn('aksi', function ($row) {

            return '
                <div class="action-wrapper">

                    <button
                        type="button"
                        class="btn btn-warning btn-action btnEdit"
                        data-id="' . $row->id . '"
                        data-url="' . route(
                            'wilayahkerja.edit',
                            $row->id
                        ) . '"
                        title="Edit Data"
                        data-bs-toggle="tooltip">

                        <i class="fas fa-edit"></i>

                    </button>

                    <button
                        type="button"
                        class="btn btn-danger btn-action btnDelete"
                        data-id="' . $row->id . '"
                        data-url="' . route(
                            'wilayahkerja.destroy',
                            $row->id
                        ) . '"
                        title="Hapus Data"
                        data-bs-toggle="tooltip">

                        <i class="fas fa-trash-alt"></i>

                    </button>

                </div>
            ';
        })

        ->rawColumns([
            'rw',
            'aksi'
        ])

        ->make(true);
}



    public function create()
    {

    }

    public function store(WilayahKerjaPosyanduRequest $request)
{

    $posyandu = MasterPosyandu::where(
        'kodePosyandu',
        $request->kodePosyandu
    )->firstOrFail();

    $rw = collect(explode(',', $request->rw))
        ->map(fn($item)=>str_pad(trim($item),2,'0',STR_PAD_LEFT))
        ->unique()
        ->sort()
        ->implode(',');

    WilayahKerjaPosyandu::create([

        'kodePosyandu'=>$request->kodePosyandu,

        'village_code'=>$posyandu->village_code,

        'rw'=>$rw

    ]);

    return response()->json([

        'status'=>true,

        'message'=>'Data berhasil disimpan.'

    ]);

}

   public function edit($id)
{

    $data = WilayahKerjaPosyandu::with('posyandu')
                ->findOrFail($id);

    return response()->json($data);

}
    public function update(
    WilayahKerjaPosyanduRequest $request,
    $id
)
{

    $data = WilayahKerjaPosyandu::findOrFail($id);

    $posyandu = MasterPosyandu::where(
        'kodePosyandu',
        $request->kodePosyandu
    )->firstOrFail();

    $rw = collect(explode(',', $request->rw))
        ->map(fn($item)=>str_pad(trim($item),2,'0',STR_PAD_LEFT))
        ->unique()
        ->sort()
        ->implode(',');

    $data->update([

        'kodePosyandu'=>$request->kodePosyandu,

        'village_code'=>$posyandu->village_code,

        'rw'=>$rw

    ]);

    return response()->json([

        'status'=>true,

        'message'=>'Data berhasil diperbarui.'

    ]);

}

    public function destroy($id)
{

    WilayahKerjaPosyandu::findOrFail($id)->delete();

    return response()->json([

        'status'=>true,

        'message'=>'Data berhasil dihapus.'

    ]);

}


    public function selectPosyandu(Request $request)
{
    $search = $request->q;

    return MasterPosyandu::where('namaPosyandu','like',"%{$search}%")
        ->limit(20)
        ->get([
            'kodePosyandu as id',
            'namaPosyandu as text'
        ]);
}
}
