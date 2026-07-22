<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use App\Services\NewLplpo\ReportService;

class DashboardController extends Controller
{
    protected ReportService $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->dashboard();

        return view('newlplpo.dashboard', $data);
    }
}
