<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;
use App\Services\Dashboard\OrganizationService;
use Illuminate\Http\Request;

class DashboardPageController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService,
        protected OrganizationService $organizationService
    ) {
    }

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | User
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();

        $groupId = $user?->groupid
            ?? session('group')
            ?? 2;

        /*
        |--------------------------------------------------------------------------
        | Filter
        |--------------------------------------------------------------------------
        */

        $bulan = $request->filled('bulan')
            ? (int) $request->bulan
            : null;

        $tahun = $request->filled('tahun')
            ? (int) $request->tahun
            : now()->year;

        $faskes = $request->filled('faskes')
            ? trim($request->faskes)
            : null;


        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        if ($bulan !== null && ($bulan < 1 || $bulan > 12)) {
            $bulan = null;
        }

        if (
            $tahun !== null &&
            ($tahun < 2020 || $tahun > now()->year + 1)
        ) {
            $tahun = now()->year;
        }


        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        $dashboardData = $this->dashboardService->getDashboard(
            (int) $groupId,
            $bulan,
            $tahun,
            $faskes
        );


        /*
        |--------------------------------------------------------------------------
        | Faskes
        |--------------------------------------------------------------------------
        |
        | Sebelumnya:
        |
        | JavaScript
        |     ↓
        | /api/organizations
        |     ↓
        | OrganizationController
        |     ↓
        | FHIR
        |
        | Sekarang:
        |
        | DashboardPageController
        |     ↓
        | OrganizationService
        |     ↓
        | FHIR
        |
        */

        $faskesList = [];

        if (in_array((int) $groupId, [1, 2])) {

            $faskesList =
                $this->organizationService
                    ->getOrganizations();
        }

    $dashboardFilter = [
    'groupId' => (int) $groupId,
    'bulan'   => $bulan,
    'tahun'   => $tahun,
    'faskes'  => $faskes,
];


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

       return view('dashboard.index', [
    'groupId'        => (int) $groupId,
    'bulan'          => $bulan,
    'tahun'          => $tahun,
    'faskes'         => $faskes,
    'faskesList'     => $faskesList,
    'dashboardData'  => $dashboardData,
    'dashboardFilter'=> $dashboardFilter,
]);
    }


    public function realtime()
    {
        return view('dashboard.realtime');
    }
}
