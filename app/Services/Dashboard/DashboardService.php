<?php

namespace App\Services\Dashboard;

#use App\Models\Dashboard\Encounter;
use App\Models\fhir\FhirEncounter;
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
        $query = FhirEncounter::query();

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
     * Jumlah absolut Encounter ANC K1 per bulan dari fhir_encounter.
     * Menghitung record, bukan pasien unik.
     */

/**
 * Grafik ANC K1 berdasarkan data FHIR Encounter.
 * Menghasilkan jumlah absolut K1 per bulan.
 */
protected function getAncK1(
    ?int $tahun = null,
    ?string $faskes = null
) {
    $query = DB::table('fhir_encounter')
        ->where('identifier_system', 'LIKE', '%ANC%')
        ->where('identifier_value', 'K1')
        ->whereNotNull('encounter_date');

    if ($tahun !== null) {
        $query->whereYear('encounter_date', $tahun);
    }

    if (!empty($faskes)) {
        $query->where(
            'service_provider',
            'LIKE',
            '%' . $faskes . '%'
        );
    }

    return $query
        ->selectRaw('MONTH(encounter_date) AS bulan')
        ->selectRaw('COUNT(*) AS total')
        ->groupByRaw('MONTH(encounter_date)')
        ->orderBy('bulan')
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
