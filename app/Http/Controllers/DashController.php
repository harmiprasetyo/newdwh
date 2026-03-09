<?php

namespace App\Http\Controllers;

use App\Charts\DistribusiCharts;
use Illuminate\Http\Request;
use App\Models\tbfact_indikator_k1;
//use App\Charts\TestChart;
class DashController extends Controller
{
    //
    public function index(){
        $sasaran_bumil = "4430";
        $sasaran_bulin = "3120";
        $jmlfaskes = "5";
        $labels = ['Jan', 'Feb', 'Mar', 'Apr'];
    $data = [10, 50, 25, 70];

   // $indikatorK1 = tbfact_indikator_k1::latest();
        //render view with products
//dd($chart);
return view('home', ['sasaranbumil'=>$sasaran_bumil,'sasaranbulin'=>$sasaran_bulin,'jmlfaskes'=>$jmlfaskes



]);

    }


    public function anc(){
        return view('dashboard.homeanc');
    }

    public function skrining(){
        return view('dashboard.homeskrin');
    }


    public function nifas(){
        return view('dashboard.homenifas');
    }
     public function anak(){
        $akb = "55";
        $akn = "60";
        $asb = "65";
        return view('dashboard.homeanak',[
            "akb"=>$akb,"akn"=>$akn,"asb"=>$asb
        ]);
    }

    public function anakimd(){
         return view('dashboard.homeanakimd');

    }

    public function anakmk(){
         return view('dashboard.homeanakmk');

    }
}
