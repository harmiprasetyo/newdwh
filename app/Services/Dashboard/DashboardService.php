<?php

namespace App\Services\Dashboard;

use App\Models\Dashboard\Encounter;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Mengambil seluruh data dashboard.
     */
    public function getDashboard(
        int $groupId,
        ?int $bulan = null,
        ?int $tahun = null,
        ?string $faskes = null
    ): array {
        $query = Encounter::query();

        /*
        |--------------------------------------------------------------------------
        | Filter utama
        |--------------------------------------------------------------------------
        */

        if ($bulan) {
            $query->whereMonth('encounter_date', $bulan);
        }

        if ($tahun) {
            $query->whereYear('encounter_date', $tahun);
        }

        if ($faskes) {
            $query->where(
                'service_provider',
                'like',
                '%' . $faskes . '%'
            );
        }

        $result = [];

        /*
        |--------------------------------------------------------------------------
        | Group 1 / 2
        |--------------------------------------------------------------------------
        |
        | Dashboard tingkat Dinkes:
        | - per_provider
        | - per_location
        |
        */

        if (in_array($groupId, [1, 2])) {

            $result['per_provider'] = $this->getPerProvider(
                $query
            );

            $result['per_location'] = $this->getPerLocation(
                $query
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Group 3
        |--------------------------------------------------------------------------
        |
        | Dashboard tingkat Puskesmas:
        | - per_location
        |
        */

        if ($groupId === 3) {

            $result['per_location'] = $this->getPerLocation(
                $query
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ANC K1
        |--------------------------------------------------------------------------
        */

        $result['anc_k1'] = $this->getAncK1(
            $tahun,
            $faskes
        );

        return $result;
    }

    /**
     * Rekap berdasarkan service provider.
     */
    protected function getPerProvider($query)
    {
        return (clone $query)
            ->selectRaw('service_provider, COUNT(*) as total')
            ->groupBy('service_provider')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Rekap berdasarkan location.
     */
    protected function getPerLocation($query)
    {
        return (clone $query)
            ->selectRaw('location, COUNT(*) as total')
            ->groupBy('location')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Grafik ANC K1 per bulan.
     *
     * Rumus dipertahankan sama dengan API lama:
     *
     * (COUNT(*) / 50) * 100
     */
    protected function getAncK1(
        ?int $tahun = null,
        ?string $faskes = null
    ) {
        $query = Encounter::query();

        /*
        |--------------------------------------------------------------------------
        | Catatan:
        | ANC K1 pada API lama hanya menggunakan filter tahun
        | dan faskes, bukan filter bulan.
        |--------------------------------------------------------------------------
        */

        if ($tahun) {
            $query->whereYear('encounter_date', $tahun);
        }

        if ($faskes) {
            $query->where(
                'service_provider',
                'like',
                '%' . $faskes . '%'
            );
        }

        return $query
            ->selectRaw("
                MONTH(encounter_date) as bulan,
                COUNT(*) as total,
                ROUND((COUNT(*) / 50) * 100, 2) as percentage
            ")
            ->where(function ($q) {

                $q->where('raw_json', 'like', '%ANC%')
                  ->where('raw_json', 'like', '%K1%');

            })
            ->groupBy(DB::raw('MONTH(encounter_date)'))
            ->orderBy(DB::raw('MONTH(encounter_date)'))
            ->get();
    }

    /**
     * Daftar faskes untuk filter dashboard.
     *
     * Karena dashboard sekarang full-stack,
     * tidak lagi mengambil data melalui /api/organizations.
     *
     * Sumber faskes diambil dari service_provider
     * yang terdapat pada dashboard_encounters.
     */

}
