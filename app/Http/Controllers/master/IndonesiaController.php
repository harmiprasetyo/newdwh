<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use Illuminate\Support\Facades\DB;

class IndonesiaController extends Controller
{
    // ✅ PROVINSI
    public function provinces()
    {
        return Province::select('code', 'name')->get();
    }

    // ✅ KOTA
    public function cities(Request $request)
    {
        $request->validate([
            'province_code' => 'required'
        ]);

        $province = Province::where('code', $request->province_code)->first();

        if (!$province) {
            return response()->json(['message' => 'Provinsi tidak ditemukan'], 404);
        }

        return City::where('province_code', $province->code)
            ->select('code', 'name')
            ->get();
    }

    // ✅ KECAMATAN
    public function districts(Request $request)
    {
        $request->validate([
            'city_code' => 'required'
        ]);

        return District::where('city_code', $request->city_code)
            ->select('code', 'name')
            ->get();
    }

    // ✅ DESA
    public function villages(Request $request)
    {
        $request->validate([
            'district_code' => 'required'
        ]);

        return \Laravolt\Indonesia\Models\Village::where('district_code', $request->district_code)
            ->select('code', 'name')
            ->get();
    }



    public function listprovince()
    {
        $provinces =\Indonesia::allProvinces();
          return response()->json([
        'data' => $provinces->map(function($item){
            return [
                'code' => $item->code,
                'name' => $item->name,
                'lat'  => $item->meta['lat'] ?? null,
                'lon'  => $item->meta['long'] ?? null,
            ];
        })
    ]);
    }


     public function mapprovince()
    {
         $data = \Indonesia::allProvinces();

    return response()->json(
        $data->map(function($item){
            return [
                'code' => $item->code,
                'name' => $item->name,
                'lat'  => $item->meta['lat'] ?? null,
                'lon'  => $item->meta['long'] ?? null,
            ];
        })
    );
    }



 public function listkota(Request $request)
{
    $code = $request->province_code;

    if (!$code) {
        return response()->json(['data' => []]);
    }

    $cities = City::where('province_code', $code)->get();

    return response()->json([
        'data' => $cities->map(function($c){
            return [
                'code' => $c->code,
                'name' => $c->name,
                'province_code' => $c->province_code,
                'lat'  => $c->meta['lat'] ?? null,
                'lon'  => $c->meta['long'] ?? null,
            ];
        })
    ]);
}








public function listkecamatan(Request $request)
{
    $provinceCode = $request->province_code;
    $cityCode     = $request->city_code;

    $query = District::query();

    // 🔥 PRIORITAS: filter kota
    if (!empty($cityCode)) {

        $query->where('city_code', $cityCode);

    }
    // 🔥 fallback: filter provinsi
    elseif (!empty($provinceCode)) {

        $query->whereHas('city', function($q) use ($provinceCode){
            $q->where('province_code', $provinceCode);
        });

    }
    // 🔥 jika dua-duanya kosong → return kosong (WAJIB)
    else {
        return response()->json(['data' => []]);
    }

    $data = $query->get();

    return response()->json([
        'data' => $data->map(function($d){
            return [
                'code' => $d->code,
                'name' => $d->name,
                'city_code' => $d->city_code,
                'lat'  => $d->meta['lat'] ?? null,
                'lon'  => $d->meta['long'] ?? null,
            ];
        })
    ]);
}








public function listdesa(Request $request)
{
    $provinceCode = $request->province_code;
    $cityCode     = $request->city_code;
    $districtCode = $request->district_code;

    $query = Village::query();

    // 🔥 PRIORITAS PALING SPESIFIK
    if (!empty($districtCode)) {

        $query->where('district_code', $districtCode);

    } elseif (!empty($cityCode)) {

        $query->whereHas('district.city', function($q) use ($cityCode){
            $q->where('code', $cityCode);
        });

    } elseif (!empty($provinceCode)) {

        $query->whereHas('district.city', function($q) use ($provinceCode){
            $q->where('province_code', $provinceCode);
        });

    } else {
        return response()->json(['data' => []]);
    }

    $data = $query->get();

    return response()->json([
        'data' => $data->map(function($v){
            return [
                'code' => $v->code,
                'name' => $v->name,
                'district_code' => $v->district_code,
                'lat'  => $v->meta['lat'] ?? null,
                'lon'  => $v->meta['long'] ?? null,
            ];
        })
    ]);
}




public function geojsonProvinsi()
{

    // 🔥 ambil geojson mentah
    $geojson = json_decode(file_get_contents(public_path('geojson/propinsi.geojson')), true);

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
