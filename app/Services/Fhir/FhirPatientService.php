<?php
namespace App\Services\Fhir;

use App\Models\FhirPatient;
use App\Models\Fhir\FhirEncounter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Fhir\FhirCondition;
use App\Models\Fhir\FhirProcedure;
use App\Models\Fhir\FhirImmunization;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Throwable;

use RuntimeException;

class FhirPatientService
{
    protected string $token;
    protected string $server;

    public function __construct(
        protected FhirPatientMapper $mapper
    ) {
        $this->token = trim(
            (string) config('fhir.token')
        );

        $this->server = rtrim(
            trim((string) config('fhir.base_url')),
            '/'
        );

        if ($this->server === '') {
            throw new RuntimeException(
                'FHIR_API_URL belum dikonfigurasi.'
            );
        }

        if ($this->token === '') {
            throw new RuntimeException(
                'FHIR_API_TOKEN kosong atau tidak terbaca.'
            );
        }

        $parts = parse_url($this->server);

        if (
            ($parts['scheme'] ?? '') !== 'https' ||
            strtolower((string) ($parts['host'] ?? '')) !==
                'fhir.sisfomedika.co.id'
        ) {
            throw new RuntimeException(
                'URL FHIR harus menggunakan HTTPS dan host resmi.'
            );
        }
    }

    /**
     * Request ke server FHIR.
     */
    protected function request(string $url): array
    {
        $parts = parse_url($url);

        if (
            !is_array($parts) ||
            strtolower((string) ($parts['scheme'] ?? '')) !== 'https' ||
            strtolower((string) ($parts['host'] ?? '')) !==
                'fhir.sisfomedika.co.id'
        ) {
            throw new RuntimeException(
                'URL request FHIR tidak valid atau tidak diizinkan.'
            );
        }

        $response = Http::acceptJson()
            ->withToken($this->token)
            ->timeout((int) config('fhir.timeout', 60))
            ->get($url);

        if ($response->failed()) {
            Log::error('FHIR Patient request gagal', [
                'url' => $url,
                'status' => $response->status(),
            ]);

            throw new RuntimeException(
                'FHIR API gagal: HTTP ' . $response->status()
            );
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new RuntimeException(
                'Respons FHIR bukan JSON yang valid.'
            );
        }

        return $data;
    }

    /**
     * Memastikan URL pagination menggunakan HTTPS
     * dan host FHIR yang diizinkan.
     */
    protected function normalizeNextUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            throw new RuntimeException(
                'URL pagination FHIR kosong.'
            );
        }

        $parts = parse_url($url);

        if (!is_array($parts)) {
            throw new RuntimeException(
                'URL pagination FHIR tidak valid.'
            );
        }

        if (empty($parts['host'])) {
            $origin = 'https://fhir.sisfomedika.co.id';

            if (str_starts_with($url, '/')) {
                $url = $origin . $url;
            } else {
                $url = $this->server . '/' . ltrim($url, '/');
            }

            $parts = parse_url($url);
        }

        if (
            strtolower((string) ($parts['host'] ?? '')) !==
                'fhir.sisfomedika.co.id' ||
            !in_array(
                strtolower((string) ($parts['scheme'] ?? '')),
                ['http', 'https'],
                true
            )
        ) {
            throw new RuntimeException(
                'Host pagination FHIR tidak diizinkan.'
            );
        }

        $path = $parts['path'] ?? '/';
        $query = isset($parts['query'])
            ? '?' . $parts['query']
            : '';

        return 'https://fhir.sisfomedika.co.id'
            . $path
            . $query;
    }

    /**
     * Mengambil dan menyimpan satu halaman Patient.
     */
    public function syncNextPage(): array
    {
        $state = DB::table('fhir_sync_states')
            ->where('resource_type', 'Patient')
            ->first();

        $baseUrl = $this->server . '/Patient';

        if ($state && $state->next_url) {
            $url = $this->normalizeNextUrl(
                $state->next_url
            );
        } else {
            $url = $baseUrl . '?' . http_build_query([
                '_count' => (int) config(
                    'fhir.patient_page_size',
                    100
                ),
            ]);
        }

        $bundle = $this->request($url);

        if (($bundle['resourceType'] ?? null) !== 'Bundle') {
            throw new RuntimeException(
                'Respons FHIR bukan Bundle yang valid.'
            );
        }

        $entries = $bundle['entry'] ?? [];
        $rows = [];

        foreach ($entries as $entry) {
            $patient = $entry['resource'] ?? [];

            if (($patient['resourceType'] ?? null) !== 'Patient') {
                continue;
            }

            if (empty($patient['id'])) {
                continue;
            }

            $row = $this->mapper->map($patient);

            if (empty($row['fhir_id'])) {
                throw new RuntimeException(
                    'Mapper Patient tidak menghasilkan fhir_id.'
                );
            }

            $rows[] = $row;
        }

        $nextUrl = null;

        foreach ($bundle['link'] ?? [] as $link) {
            if (($link['relation'] ?? null) === 'next') {
                $nextUrl = $this->normalizeNextUrl(
                    (string) ($link['url'] ?? '')
                );

                break;
            }
        }

        DB::transaction(function () use ($rows, $nextUrl) {
            foreach (array_chunk($rows, 100) as $chunk) {
                if (empty($chunk)) {
                    continue;
                }

                $updateColumns = array_values(array_diff(
                    array_keys($chunk[0]),
                    ['fhir_id', 'created_at']
                ));

                FhirPatient::upsert(
                    $chunk,
                    ['fhir_id'],
                    $updateColumns
                );
            }

            DB::table('fhir_sync_states')->updateOrInsert(
                ['resource_type' => 'Patient'],
                [
                    'next_url' => $nextUrl,
                    'last_completed_at' => $nextUrl
                        ? null
                        : now(),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        });

        return [
            'received' => count($entries),
            'saved' => count($rows),
            'next_page' => $nextUrl !== null,
        ];
    }
}
