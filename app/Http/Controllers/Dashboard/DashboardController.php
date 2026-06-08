<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dashboard\Encounter;

class DashboardController extends Controller
{
    //


public function index(Request $request)
{
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

    $result = [];

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

    return response()->json([
        'data' => $result
    ]);
}
}
