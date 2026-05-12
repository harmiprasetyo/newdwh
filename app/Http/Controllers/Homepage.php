<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Homepage extends Controller
{
    //
    public function index(){

    die(auth()->user()->groupid);
        if(auth()->user()->groupid == 3) {
            return view('homepage');
        }elseif(auth()->user()->groupid == 1) {
            return view('homepageadmin');
        }else{
            return view('homepage');
        }
    }
}
