<?php

namespace App\Http\Controllers\gis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExportGeojsonController extends Controller
{
    //



public function geojsonProvinsi()
{
    // 🔥 ambil geojson mentah
    $geojson = json_decode(file_get_contents(public_path('geojson/provinces_raw.geojson')), true);

    // 🔥 ambil mapping dari database
    $provinces = DB::table('indonesia_provinces')
        ->pluck('code', 'name'); // [name => code]

    // 🔥 inject code ke geojson
    foreach ($geojson['features'] as &$feature) {

        $nama = strtoupper($feature['properties']['Propinsi'] ?? $feature['properties']['name'] ?? '');

        foreach ($provinces as $dbName => $code) {

            if (strtoupper($dbName) == $nama) {
                $feature['properties']['code'] = $code;
                break;
            }
        }
    }

    return response()->json($geojson);
}
}
