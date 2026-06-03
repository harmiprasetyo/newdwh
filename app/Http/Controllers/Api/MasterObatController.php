<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\MasterObat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MasterObatController extends Controller
{
    public function index()
    {
        return MasterObat::latest()->paginate(10);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_obat' => 'required|unique:master_obat',
            'nama_obat' => 'required',
            'satuan' => 'required',
        ]);

        $data = MasterObat::create($request->all());

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        return MasterObat::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $data = MasterObat::findOrFail($id);

        $data->update($request->all());

        return response()->json([
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        MasterObat::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
