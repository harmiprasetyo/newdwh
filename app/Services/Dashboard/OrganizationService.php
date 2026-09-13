<?php

namespace App\Services\Dashboard;

use Illuminate\Support\Facades\Http;

class OrganizationService
{
    /**
     * Mengambil daftar Organization dari FHIR Server.
     */
    public function getOrganizations(?string $search = null): array
    {
        $token = env('FHIR_API_TOKEN');
        $server = env('FHIR_API_URL');

        if (empty($server)) {
            return [];
        }

        $response = Http::withToken($token)
            ->timeout(30)
            ->get(
                rtrim($server, '/') . '/Organization',
                [
                    'name'   => $search,
                    '_count' => 30,
                ]
            );

        if (!$response->successful()) {
            return [];
        }

        $bundle = $response->json();

        $data = [];

        foreach ($bundle['entry'] ?? [] as $entry) {

            $org = $entry['resource'] ?? [];

            if (
                empty($org['id']) ||
                empty($org['name'])
            ) {
                continue;
            }

            $data[] = [
                'id'   => $org['id'],
                'name' => $org['name'],
            ];
        }

        return $data;
    }
}
