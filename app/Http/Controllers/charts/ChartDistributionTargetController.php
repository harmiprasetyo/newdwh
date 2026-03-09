<?php

namespace App\Http\Controllers\charts;

use App\Charts\DistribusiCharts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use App\Charts\DitsribusiTarget;
use App\Charts\TestChart;

class ChartDistributionTargetController extends Controller
{
    //
   public function index()
   {
    //$cbs = $chart->build();
   // dd($cbs);
    return view('dashboardku');
    }
}
