<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Obat\laporanLplpo as Lplpo;

class LplpoController extends Controller
{
    public function index(Request $request)
    {
        $query = Lplpo::with('obat', 'faskes');

        if ($request->bulan) {
            $query->where('bulan', $request->bulan);
        }

        if ($request->tahun) {
            $query->where('tahun', $request->tahun);
        }

        return response()->json([
            'data' => $query->get()
        ]);
    }

    public function store(Request $request)
    {
        Lplpo::create($request->all());

        return response()->json(['message' => 'created']);
    }

    public function show($id)
    {
        return response()->json([
            'data' => Lplpo::with('obat', 'faskes')->findOrFail($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = Lplpo::findOrFail($id);
        $data->update($request->all());

        return response()->json(['message' => 'updated']);
    }

    public function destroy($id)
    {
        Lplpo::findOrFail($id)->delete();

        return response()->json(['message' => 'deleted']);
    }
}
