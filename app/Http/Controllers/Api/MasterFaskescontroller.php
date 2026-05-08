<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\MasterFaskes;
class MasterFaskesController extends Controller
{
 public function index(Request $request)
{
    $query = MasterFaskes::with([
        'type',
        'provinsi',
        'kota',
        'kecamatan'
    ]);

    if ($request->provinsi) {
        $query->where('kodePropinsi', $request->provinsi);
    }

    if ($request->kota) {
        $query->where('kodeKabupaten', $request->kota);
    }

    if ($request->kecamatan) {
        $query->where('kodeKecamatan', $request->kecamatan);
    }

    if ($request->kepemilikan) {
        $query->where('kepemilikan', $request->kepemilikan);
    }

    if ($request->type) {
        $query->where('typeFaskes', $request->type);
    }

    return response()->json([
        'data' => $query->get()
    ]);
}

    public function show($id)
{
    $data = MasterFaskes::with('type')->find($id);

    if (!$data) {
        return response()->json([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'data' => $data
    ]);
}



    public function store(Request $request)
    {
        MasterFaskes::create($request->all());

        return response()->json(['message' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $data = MasterFaskes::findOrFail($id);
        $data->update($request->all());

        return response()->json(['message' => 'updated']);
    }

    public function destroy($id)
    {
        MasterFaskes::findOrFail($id)->delete();

        return response()->json(['message' => 'deleted']);
    }
}
