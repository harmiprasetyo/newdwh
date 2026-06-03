<?php

namespace App\Http\Controllers\Lplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LplpoService;
use App\Models\Lplpo\Lplpo;
use App\Models\Master\MasterFaskes;
use App\Models\Lplpo\LplpoHeaderReport;
use App\Models\Lplpo\LplpoFinal;
use Illuminate\Support\Facades\DB;

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


if (session('group') == 3) {
        $faskes = MasterFaskes::where('kodeFaskes', session('kodeFaskes'))
            ->select('kodeFaskes','namaFaskes')
            ->get();
    } elseif(session('group') == 2) {
         $faskes = MasterFaskes::where('kodeKabupaten', session('kab'))
            ->select('kodeFaskes','namaFaskes')
            ->get();


    }else {
        $faskes = MasterFaskes::select('kodeFaskes','namaFaskes')->get();
    }

return view('lplpo.dataview', compact('faskes'));
}

public function data(Request $request)
{

$query = Lplpo::query()
    ->leftJoin('master_faskes', 'lplpo_temp.kode_faskes', '=', 'master_faskes.kodeFaskes')
    // ->join('lplpo_header_report', 'lplpo_temp.header_id', '=', 'lplpo_header_report.id')
    ->select(
        'lplpo_temp.*',
        'master_faskes.namaFaskes'
    )->orderBy('lplpo_temp.id', 'desc');

//$query = Lplpo::orderBy('id', 'desc');

    // FILTER
     if (session('group') == 3) {
        $query->where('lplpo_temp.kode_faskes', session('kodeFaskes'));
    }elseif(session('group') == 2) {
        $query->whereIn('lplpo_temp.kode_faskes', function($q) {
            $q->select('kodeFaskes')
              ->from('master_faskes')
              ->where('kodeKabupaten', session('kab'));
        });
    }


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

/*
public function bulkUpdatePemberian(Request $request)
{
    foreach ($request->data as $item) {

     $pemberian = $item['pemberian'];

        // default 0 jika kosong/null
        if ($pemberian === null || $pemberian === '') {
            $pemberian = 0;
        }
        Lplpo::where('id', $item['id'])
            ->update([
                'pemberian' => $pemberian
            ]);
    }

    return response()->json([
        'success' => true
    ]);
}*/




public function bulkUpdatePemberian(Request $request)
{
    DB::beginTransaction();

    try {

        $headerId = null;

        foreach ($request->data as $item) {

            $pemberian = $item['pemberian'];

            if ($pemberian === null || $pemberian === '') {
                $pemberian = 0;
            }

            $row = Lplpo::find($item['id']);

            if (!$row) continue;

            // simpan header_id
            $headerId = $row->header_id;

            // update pemberian
            $row->update([
                'pemberian' => $pemberian
            ]);
        }

        // 🔥 VALIDASI HEADER
        $header = LplpoHeaderReport::find($headerId);

        if (!$header) {
            throw new \Exception('Header tidak ditemukan');
        }

        if ($header->final) {
            throw new \Exception('Laporan sudah final');
        }

        // 🔥 COPY KE FINAL TABLE
        $data = Lplpo::where('header_id', $headerId)->get();

        foreach ($data as $row) {

            LplpoFinal::create($row->toArray());
        }

        // 🔥 UPDATE HEADER FINAL
        $header->update([
            'final' => true
        ]);

        // 🔥 HAPUS TEMP
        Lplpo::where('header_id', $headerId)->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil difinalisasi'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

}
