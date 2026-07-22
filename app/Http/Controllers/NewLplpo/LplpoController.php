<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\NewLplpo\ReportService;

use App\Models\NewLplpo\Report;
use App\Services\NewLplpo\ItemService;
use App\Models\NewLplpo\Program;
use App\Models\Master\MasterFaskes;
use Carbon\Carbon;
use App\Models\ListTypeFaskes;
use Yajra\DataTables\Facades\DataTables;
use App\Models\NewLplpo\Item;


class LplpoController extends Controller
{

    protected ReportService $service;
    protected ItemService $itemService;

    public function __construct(ReportService $service,ItemService $itemService){
    $this->service=$service;
    $this->itemService=$itemService;
}

public function detail($id)
{
    $report = Report::findOrFail($id);

    $items = $this->itemService->listByReport($id);

    $faskes = MasterFaskes::with([
        'type',
        'provinsi',
        'kota',
        'kecamatan'
    ])
    ->where('kodeFaskes',$report->kode_faskes)
    ->firstOrFail();

    return view('newlplpo.detail',[
        'report'=>$report,
        'items'=>$items,
        'faskes'=>$faskes
    ]);
}





public function laporan()
{
    return view('newlplpo.laporan');
}

public function laporanDatatable(Request $request)
{
    $request->merge([

        'bulan' => $request->bulan ?? Carbon::now()->month,

        'tahun' => $request->tahun ?? Carbon::now()->year,

    ]);

    $data = $this->service->laporan($request);

    return DataTables::of($data)

        ->addIndexColumn()

        ->editColumn('created_at', function ($row) {

            return $row->created_at
                ->format('d-m-Y H:i');

        })

        ->addColumn('status_badge', function ($row) {

            $color = match($row->report_status){

                'DRAFT'      => 'warning',

                'SUBMITED'  => 'info',

                'VERIFIED'   => 'primary',
                'REJECTED'=>'danger',

                'FINAL'   => 'success',

                default       => 'dark'

            };
            $status = match($row->report_status){
                'DRAFT' =>'DRAFT',
                'SUBMITED'=>'TERKIRIM',
                'VERIFIED' =>'TERVERIFIKASI',
                'REJECTED'=>'DITOLAK',
                'FINAL'=>'SELESAI',
                default => 'NEW'

            };

            return '<span class="badge bg-'.$color.'">'.
                    $status.
                   '</span>';

        })

    /*    ->addColumn('action', function ($row){

            return '

            <a href="'.route('newlplpo.edit',$row->id).'"
               class="btn btn-sm btn-primary">

                <i class="bi bi-eye"></i>

            </a>

            ';

        })*/

            ->addColumn('action', function ($row){

    if(in_array($row->report_status,['DRAFT'])){

         $btn = '

        <a href="'.route('newlplpo.edit',$row->id).'"
            class="btn btn-warning btn-sm"
            title="Edit">

            <i class="bi bi-pencil-square"></i>

        </a>

        ';

         $btn .= '

            <button
                class="btn btn-danger btn-sm btnDelete"
                data-id="'.$row->id.'"
                title="Hapus">

                <i class="bi bi-trash"></i>

            </button>

            ';

             return $btn;

    }

    return '

    <a
        href="'.route('newlplpo.detail',$row->id).'"
        class="btn btn-primary btn-sm"
        title="Detail">

        <i class="bi bi-eye"></i>

    </a>

    ';

})

        ->rawColumns([
            'status_badge',
            'action'
        ])

        ->make(true);
}


    /**
     * daftar lplpo
     */
    public function index(Request $request)
    {

        $reports=$this->service->list($request);

        return view(
            'newlplpo.listlplpo',
            compact('reports')
        );

    }


    public function items($reportId)
{
    $items = $this->itemService->listByReport($reportId);

    return view('newlplpo.partials.items_table', compact('items'));
}

public function lastlplpo(){

}

    /**
     * form membuat lplpo
     */


public function create()
{
    $programs = Program::orderBy('program_name')->get();

    $kodeFaskes = session('kodeFaskes');

    $faskes = MasterFaskes::with([
        'type',
        'provinsi',
        'kota',
        'kecamatan'
    ])
    ->where('kodeFaskes',$kodeFaskes)
    ->firstOrFail();

    return view('newlplpo.buatlplpo',[

        'programs'=>$programs,

        'nomorLplpo'=>'LPLPO-'.$kodeFaskes.'-'.Carbon::now()->format('YdmHis'),

        'faskes'=>$faskes

    ]);

}

    /**
     * simpan header lplpo
     */
    public function store(Request $request)
    {

        $request->validate([

            'kode_faskes'=>'required',

            'nama_faskes'=>'required',

            'bulan'=>'required|numeric',

            'tahun'=>'required|numeric',

            'nomor_lplpo'=>'required'

        ]);

        $request->merge([

    'kode_faskes' => session('kodeFaskes'),

    'nama_faskes' => MasterFaskes::where(
        'kodeFaskes',
        session('kodeFaskes')
    )->value('namaFaskes')

]);

        $report=$this->service->create(

            $request->all()

        );

        return redirect()

            ->route(

                'newlplpo.edit',

                $report->id

            )

            ->with(

                'success',

                'Laporan berhasil dibuat.'

            );

    }

    /**
     * edit laporan
     */
   public function edit($id)
{
    $report = Report::findOrFail($id);

    $items = $this->itemService->listByReport($id);

//dd($items);

    $programs = Program::orderBy('program_name')->get();

    // Ambil data faskes berdasarkan laporan
    $faskes = MasterFaskes::with([
        'type',
        'provinsi',
        'kota',
        'kecamatan'
    ])
    ->where('kodeFaskes', $report->kode_faskes)
    ->firstOrFail();
$mode='edit';
    return view('newlplpo.buatlplpo', [

        'report'      => $report,
        'items'       => $items,
        'programs'    => $programs,
        'faskes'      => $faskes,

        // gunakan nomor dari database, bukan generate ulang
        'nomorLplpo'  => $report->nomor_lplpo,
        'mode'=>$mode

    ]);
}

    /**
     * update laporan
     */
    public function update(Request $request,$id)
    {

        $report=Report::findOrFail($id);

        $this->service->update(

            $report,

            $request->all()

        );

        return back()

            ->with(

                'success',

                'Data berhasil diupdate.'

            );

    }

    /**
     * arsip
     */
    public function arsip(Request $request)
    {

        $reports=Report::

            where('report_status','FINAL')

            ->paginate(20);

        return view(

            'newlplpo.arsiplplpo',

            compact('reports')

        );

    }

    /**
     * pemberian obat
     */
    public function pemberian()
    {

        return view(

            'newlplpo.pemberian'

        );

    }

    /**
     * hapus
     */
    public function destroy($id)
    {

        $report=Report::findOrFail($id);

        $this->service->delete($report);

        return back()

            ->with(

                'success',

                'Laporan berhasil dihapus.'

            );

    }


    public function storeItem(Request $request)
{
    $request->validate([
        'report_id'  => 'required',
        'program_id' => 'required',
        'kode_obat'  => 'required',
        'nama_obat'  => 'required'
    ]);

    $item = $this->itemService->create($request->all());

    if ($request->ajax()) {

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil disimpan',
            'data'    => $item
        ]);

    }

    return back()->with('success','Item berhasil disimpan');
}

public function updateItem(Request $request,$id)
{

    $item=Item::findOrFail($id);

    $this->itemService->update(

        $item,

        $request->all()

    );

    return back()

        ->with(

            'success',

            'Item berhasil diupdate.'

        );

}


public function destroyItem($id)
{

    $item=Item::findOrFail($id);

    $this->itemService->delete($item);

    return back()

        ->with(

            'success',

            'Item berhasil dihapus.'

        );

}

}
