<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dashboard\Encounter;
use Carbon\Carbon;

class EncounterController extends Controller
{
    //
    public function realtime()
{
    $now = Carbon::now('Asia/Jakarta');

    // ======================
    // DATA TERBARU
    // ======================
    $data = Encounter::with('patient')
        ->latest('encounter_date')
        ->limit(20)
        ->get();

    // ======================
    // STATUS HARI INI
    // ======================
    $stats = Encounter::whereDate('encounter_date', $now->toDateString())
        ->selectRaw('status, COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

    // ======================
    // 🔥 PER MENIT (60 MENIT TERAKHIR)
    // ======================
    $start = $now->copy()->subMinutes(59);

    $raw = Encounter::whereBetween('encounter_date', [$start, $now])
        ->selectRaw('DATE_FORMAT(encounter_date, "%H:%i") as menit, COUNT(*) as total')
        ->groupBy('menit')
        ->pluck('total', 'menit');

    // generate 60 menit lengkap
    $chart = [];

    for ($i = 59; $i >= 0; $i--) {

        $time = $now->copy()->subMinutes($i)->format('H:i');

        $chart[] = [
            'time' => $time,
            'total' => $raw[$time] ?? 0
        ];
    }

    return response()->json([
        'latest' => $data,
        'stats' => $stats,
        'chart' => $chart
    ]);
}
}
