<?php

namespace App\Http\Controllers;

use App\Charts\DistribusiCharts;
use Illuminate\Http\Request;
//use App\Models\tbfact_indikator_k1;
class DashboardController extends Controller
{
    //
    public function index(DistribusiCharts $chart){


        //render view with products
return view('dashboardku', [
    'chart'=>$chart->build()



]);

    }
}
