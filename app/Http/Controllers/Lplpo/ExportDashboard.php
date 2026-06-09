<?php

namespace App\Http\Controllers\Lplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
    use Maatwebsite\Excel\Facades\Excel;

class ExportDashboard extends Controller
{
    //


public function export(Request $request)
{
    return Excel::download(
        new DashboardExport($request->bulan, $request->tahun),
        'dashboard_lplpo.xlsx'
    );
}
}
