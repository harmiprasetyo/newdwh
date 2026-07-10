<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dashboard\Encounter;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //


public function index(Request $request)
{

//dd($request->all());
$groupId = $request->group_id ?? 2;

    $query = Encounter::query();

    // FILTER
    if ($request->bulan) {
        $query->whereMonth('encounter_date', $request->bulan);
    }

    if ($request->tahun) {
        $query->whereYear('encounter_date', $request->tahun);
    }

    if ($request->faskes) {
        $query->where('service_provider', 'like', '%' . $request->faskes . '%');
    }
   // dd($query->toSql(), $query->getBindings());

    $result = [];

    /*
|--------------------------------------------------------------------------
| Grafik ANC K1 (%)
|--------------------------------------------------------------------------
|
| Menghitung jumlah encounter yang mengandung
| ANC dan K1 pada raw_json
| lalu dibagi konstanta 1200 menjadi persen.
|
*/
/*
$ancK1 = Encounter::query();

if ($request->tahun) {
    $ancK1->whereYear('encounter_date', $request->tahun);
}

if ($request->faskes) {
    $ancK1->where('service_provider', 'like', '%' . $request->faskes . '%');
}

$result['anc_k1'] = $ancK1
    ->selectRaw("
        MONTH(encounter_date) as bulan,
        COUNT(*) as total,
        ROUND((COUNT(*)/1200)*100,2) as persen
    ")
    ->where(function($q){

        $q->where('raw_json','like','%ANC%')
          ->where('raw_json','like','%K1%');

    })
    ->groupByRaw('MONTH(encounter_date)')
    ->orderByRaw('MONTH(encounter_date)')
    ->get(); */

    if ($groupId == 2) {

        $result['per_provider'] = (clone $query)
            ->selectRaw('service_provider, COUNT(*) total')
            ->groupBy('service_provider')
            ->get();

        $result['per_location'] = (clone $query)
            ->selectRaw('location, COUNT(*) total')
            ->groupBy('location')
            ->get();
    }

    if ($groupId == 3) {

        $result['per_location'] = (clone $query)
            ->selectRaw('location, COUNT(*) total')
            ->groupBy('location')
            ->get();
    }


    /*
|--------------------------------------------------------------------------
| ANC K1 Coverage per Month
|--------------------------------------------------------------------------
*/

$ancQuery = Encounter::query();

if ($request->tahun) {
    $ancQuery->whereYear('encounter_date', $request->tahun);
}

if ($request->faskes) {
    $ancQuery->where('service_provider', 'like', '%' . $request->faskes . '%');
}

$result['anc_k1'] = $ancQuery
    ->selectRaw("
        MONTH(encounter_date) as bulan,
        COUNT(*) as total,
        ROUND((COUNT(*) / 50) * 100,2) as percentage
    ")
    ->where(function($q){

        $q->where('raw_json','like','%ANC%')
          ->where('raw_json','like','%K1%');

    })
    ->groupBy(DB::raw('MONTH(encounter_date)'))
    ->orderBy(DB::raw('MONTH(encounter_date)'))
    ->get();

    return response()->json([
        'data' => $result
    ]);
}
}
