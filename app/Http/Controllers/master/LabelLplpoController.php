<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\LabelLplpo;
use Illuminate\Support\Facades\DB;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class LabelLplpoController extends Controller
{
    public function index()
    {
        $data = LabelLplpo::with('kabupaten')->latest()->get();

        $provinsi = \Indonesia::allProvinces();

        return view('admin.labeling', compact('data', 'provinsi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kodeKab' => 'required|unique:label_lplpo,kodeKab',
            'field1' => 'required',
            'field2' => 'required',
            'field3' => 'required',
        ]);

        LabelLplpo::create($request->all());

        return back()->with('success', 'Data berhasil disimpan');
    }

    // 🔥 ambil kabupaten by kode provinsi
    public function getKabupaten($province_code)
    {
        return City::where('province_code', $province_code)->orderBy('name')->get();
    }
}
