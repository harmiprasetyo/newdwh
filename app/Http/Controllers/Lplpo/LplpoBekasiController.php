<?php

namespace App\Http\Controllers\Lplpo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class LplpoBekasiController extends Controller
{
    public function index()
    {
        return view('lplpo.bekasi');
    }

    public function data(Request $request)
    {
        $response = Http::withHeaders([
            'X-API-KEY' => 'EEAED6CEAA6BCE19EBFAE71E659748D2',
            'Cookie' => 'aksara_beY76xxoDJiV9xyr=eoqi5gh8prq9e5v8tdjfrcme2q3u4it5'
        ])->get('https://sipo.bekasikota.go.id/api/sisfomedika/lplpo',[
            'tahun' => $request->tahun ?? 2026,
            'bulan' => $request->bulan ?? 6,
            'sub_unit' => $request->sub_unit ?? 5,
            'limit' => 5000,
            'page' => 1
        ]);

        if(!$response->successful()){
            return response()->json([]);
        }

        return response()->json(array_values($response->json()));
    }
}
