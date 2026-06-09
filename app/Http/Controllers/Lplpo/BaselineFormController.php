<?php

namespace App\Http\Controllers\Lplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaselineFormController extends Controller
{
    //
    public function index()
{
    return view('lplpo.baseline_form');
}
}
