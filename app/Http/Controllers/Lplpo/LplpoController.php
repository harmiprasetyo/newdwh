<?php

namespace App\Http\Controllers\Lplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LplpoService;
use App\Models\Lplpo\Lplpo;
use App\Models\Master\MasterFaskes;

class LplpoController extends Controller
{
    protected $service;

    public function __construct(LplpoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {

        return view('lplpo.dashboard');
    }


    public function dataview()
{
  $faskes = MasterFaskes::select('kodeFaskes','namaFaskes')->get();
return view('lplpo.dataview', compact('faskes'));
}

public function data(Request $request)
{

$query = Lplpo::query()
    ->leftJoin('master_faskes', 'lplpo_temp.kode_faskes', '=', 'master_faskes.kodeFaskes')
    ->select(
        'lplpo_temp.*',
        'master_faskes.namaFaskes'
    )->orderBy('lplpo_temp.id', 'desc');

//$query = Lplpo::orderBy('id', 'desc');

    // FILTER
    if ($request->bulan) {
        $query->where('bulan', $request->bulan);
    }

    if ($request->tahun) {
        $query->where('tahun', $request->tahun);
    }

    if ($request->faskes) {
        $query->where('kode_faskes', $request->faskes);
    }

  /*  return datatables()->of($query)
        ->addIndexColumn()
        ->make(true);*/

        return datatables()->of($query)
    ->addIndexColumn()
    ->rawColumns([])
    ->make(true);
}



    public function uploadPage()
    {
        return view('lplpo.upload');
    }

  public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls',
        'bulan' => 'required|integer|min:1|max:12',
        'tahun' => 'required|integer|min:'.date('Y')
    ]);

    $result = $this->service->import(
        $request->file('file'),
        $request->bulan,
        $request->tahun
    );

    if (!empty($result['errors'])) {
        return back()
            ->with('import_error', $result['errors'])
            ->with('error_file', $result['file']); // ✅ dynamic file
    }

    return back()->with('success', 'Data berhasil diimport!');
}
}
