<?php

namespace App\Http\Controllers\Dashboard;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardPageController extends Controller
{
    public function index()
    {
        $groupId = session('group'); // atau auth()->user()->groupid
        $faskes = session('faskes'); // atau auth()->user()->faskes

       // dd($groupId);

        return view('dashboard.index', [
            'groupId' => $groupId,
            'faskes' => $faskes
        ]);
    }

    public function realtime()
    {
        return view('dashboard.realtime');
    }
}
