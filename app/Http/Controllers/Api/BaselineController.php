<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\MasterBaselinePemakaian;

class BaselineController extends Controller
{
    // ======================
    // LIST (DATATABLE)
    // ======================
   public function index()
{
    $kodeFaskes = auth()->user()->kodeFaskes;

    $query = MasterBaselinePemakaian::with('faskes')
        ->where('kode_faskes', $kodeFaskes);

    return datatables()->of($query)
        ->addIndexColumn()
        ->addColumn('namaFaskes', fn($row) => $row->faskes->namaFaskes ?? '-')
        ->make(true);
}

    // ======================
    // STORE
    // ======================
   public function store(Request $request)
{
    $request->validate([
        'kode_obat' => 'required',
        'nama_obat' => 'required',
        'bulan' => 'required|integer',
        'tahun' => 'required|integer',
        'rerata_pemakaian' => 'required|numeric'
    ]);

    $kodeFaskes = auth()->user()->kodeFaskes;

    // CEK DUPLIKAT
    $exists = MasterBaselinePemakaian::where([
        'kode_faskes' => $kodeFaskes,
        'kode_obat' => $request->kode_obat,
        'bulan' => $request->bulan,
        'tahun' => $request->tahun
    ])->exists();

    if ($exists) {
        return response()->json(['message' => 'Data sudah ada'], 422);
    }

    MasterBaselinePemakaian::create([
        'kode_faskes' => $kodeFaskes,
        'kode_obat' => $request->kode_obat,
        'nama_obat' => $request->nama_obat,
        'bulan' => $request->bulan,
        'tahun' => $request->tahun,
        'rerata_pemakaian' => $request->rerata_pemakaian
    ]);

    return response()->json(['message' => 'created']);
}

    // ======================
    // SHOW (EDIT)
    // ======================
    public function show($id)
    {
        $kodeFaskes = auth()->user()->kodeFaskes;

        $data = MasterBaselinePemakaian::where('id', $id)
            ->where('kode_faskes', $kodeFaskes)
            ->firstOrFail();

        return response()->json([
            'data' => $data
        ]);
    }

    // ======================
    // UPDATE
    // ======================
    public function update(Request $request, $id)
{
    $kodeFaskes = auth()->user()->kodeFaskes;

    $data = MasterBaselinePemakaian::where('id', $id)
        ->where('kode_faskes', $kodeFaskes)
        ->firstOrFail();

    $data->update([
        'kode_obat' => $request->kode_obat,
        'nama_obat' => $request->nama_obat,
        'bulan' => $request->bulan,
        'tahun' => $request->tahun,
        'rerata_pemakaian' => $request->rerata_pemakaian
    ]);

    return response()->json(['message' => 'updated']);
}
    // ======================
    // DELETE
    // ======================
    public function destroy($id)
{
    $kodeFaskes = auth()->user()->kodeFaskes;

    $data = MasterBaselinePemakaian::where('id', $id)
        ->where('kode_faskes', $kodeFaskes)
        ->firstOrFail();

    $data->delete();

    return response()->json(['message' => 'deleted']);
}
}
