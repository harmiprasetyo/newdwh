<?php

namespace App\Services\Fhir;

use App\Models\Fhir\FhirEncounter;
use App\Models\Fhir\FhirLocation;
use App\Models\Fhir\FhirOrganization;
use App\Models\Fhir\FhirObservation;
use App\Models\Fhir\FhirPatient;
use App\Models\Fhir\FhirPractitioner;

use App\Models\Fhir\FhirCondition;
use App\Models\Fhir\FhirProcedure;
use App\Models\Fhir\FhirImmunization;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class FhirImportService
{
    protected string $token;
    protected string $server;

    protected const FHIR_HOST = 'fhir.sisfomedika.co.id';

    public function __construct(
        protected FhirPatientMapper $patientMapper
    ) {
        $this->token = trim((string) config('fhir.token'));
        $this->server = rtrim(trim((string) config('fhir.base_url')), '/');

        if ($this->server === '') {
            throw new RuntimeException('FHIR_API_URL belum dikonfigurasi.');
        }

        if ($this->token === '') {
            throw new RuntimeException('FHIR_API_TOKEN kosong atau tidak terbaca.');
        }

        $parts = parse_url($this->server);

        if (
            !is_array($parts) ||
            strtolower((string) ($parts['scheme'] ?? '')) !== 'https' ||
            strtolower((string) ($parts['host'] ?? '')) !== self::FHIR_HOST
        ) {
            throw new RuntimeException(
                'FHIR_API_URL harus menggunakan HTTPS dan host resmi.'
            );
        }
    }

/**
 * Mengubah tanggal FHIR menjadi Carbon.
 */
protected function parseFhirDate(mixed $value): ?Carbon
{
    if (!is_string($value) || trim($value) === '') {
        return null;
    }

    try {
        return Carbon::parse($value);
    } catch (Throwable $e) {
        Log::warning('Tanggal FHIR tidak valid', [
            'value' => $value,
            'message' => $e->getMessage(),
        ]);

        return null;
    }
}

/**
 * Mengambil coding pertama dari CodeableConcept.
 */
protected function fhirCoding(array $concept): array
{
    $coding = data_get($concept, 'coding.0', []);

    return [
        'system' => data_get($coding, 'system'),
        'code' => data_get($coding, 'code'),
        'display' => data_get($coding, 'display')
            ?? data_get($concept, 'text'),
    ];
}

/**
 * Mengambil referensi actor dari array performer FHIR.
 */
protected function fhirPerformers(array $performers): array
{
    return array_values(array_map(
        fn (array $item) => [
            'reference' => data_get($item, 'actor.reference'),
            'display' => data_get($item, 'actor.display'),
            'function' => data_get($item, 'function'),
        ],
        $performers
    ));
}

/**
 * Mengambil referensi Patient dan Encounter.
 *
 * Immunization menggunakan patient.reference.
 * Condition dan Procedure menggunakan subject.reference.
 */
protected function fhirClinicalReferences(
    array $resource,
    string $resourceType
): array {
    $patientReference = $resourceType === 'Immunization'
        ? data_get($resource, 'patient.reference')
        : data_get($resource, 'subject.reference');

    $patientFhirId = $this->referenceId(
        $patientReference,
        'Patient'
    );

    $encounterFhirId = $this->referenceId(
        data_get($resource, 'encounter.reference'),
        'Encounter'
    );

    return [
        'patient_id' => $patientFhirId
            ? FhirPatient::where('fhir_id', $patientFhirId)->value('id')
            : null,

        'fhir_patient_id' => $patientFhirId,

        'encounter_id' => $encounterFhirId
            ? FhirEncounter::where('fhir_id', $encounterFhirId)->value('id')
            : null,

        'fhir_encounter_id' => $encounterFhirId,
    ];
}

/**
 * Hanya mengirim kolom yang tersedia di tabel aktual.
 *
 * Ini memungkinkan sinkronisasi tetap berjalan jika tabel yang
 * sudah ada belum memiliki semua kolom opsional.
 */
protected function filterExistingColumns(
    string $modelClass,
    array $attributes
): array {
    $model = new $modelClass();
    $table = $model->getTable();

    $columns = Schema::getColumnListing($table);
    $filtered = array_intersect_key(
        $attributes,
        array_flip($columns)
    );

    $ignored = array_diff(
        array_keys($attributes),
        array_keys($filtered)
    );

    if ($ignored !== []) {
        Log::warning('Kolom FHIR tidak tersedia di tabel; field dilewati.', [
            'table' => $table,
            'columns' => array_values($ignored),
        ]);
    }

    if (!in_array('fhir_id', $columns, true)) {
        throw new \RuntimeException(
            "Tabel {$table} harus memiliki kolom fhir_id."
        );
    }

    return $filtered;
}

/**
 * Metadata bersama seluruh resource.
 */
protected function fhirCommonAttributes(
    array $resource,
    string $resourceType
): array {
    return array_merge(
        $this->fhirClinicalReferences($resource, $resourceType),
        [
            'version_id' => data_get($resource, 'meta.versionId'),
            'fhir_last_updated' => $this->parseFhirDate(
                data_get($resource, 'meta.lastUpdated')
            ),
            'raw_resource' => $resource,
            'synced_at' => now(),
        ]
    );
}

private function resourceModel(string $resourceType): string
{
    return match ($resourceType) {
        'Condition'    => FhirCondition::class,
        'Procedure'    => FhirProcedure::class,
        'Immunization' => FhirImmunization::class,
        default => throw new \InvalidArgumentException(
            "FHIR resource tidak didukung: {$resourceType}"
        ),
    };
}

public function importCondition(): array
{
    $page = $this->fetchPage(
        'Condition',
        (int) config('fhir.condition_page_size', 100)
    );

    $resources = $page['resources'] ?? [];
    $processed = 0;

    DB::transaction(function () use ($resources, $page, &$processed) {
        foreach ($resources as $resource) {
            if (($resource['resourceType'] ?? null) !== 'Condition') {
                continue;
            }

            $fhirId = $resource['id'] ?? null;

            if (!$fhirId) {
                Log::warning('Condition tanpa ID FHIR dilewati.');
                continue;
            }

            // Ambil coding dalam bentuk scalar untuk kolom varchar/text.
            $clinicalStatus = $this->fhirCoding(
                data_get($resource, 'clinicalStatus', [])
            );

            $verificationStatus = $this->fhirCoding(
                data_get($resource, 'verificationStatus', [])
            );

            $categoryCoding = $this->fhirCoding(
                data_get($resource, 'category.0', [])
            );

            $severity = $this->fhirCoding(
                data_get($resource, 'severity', [])
            );

            $code = $this->fhirCoding(
                data_get($resource, 'code', [])
            );

            $attributes = array_merge(
                $this->fhirCommonAttributes($resource, 'Condition'),
                [
                    'clinical_status' => $clinicalStatus['code'] ?? null,
                    'verification_status' => $verificationStatus['code'] ?? null,

                    'category_code' => $categoryCoding['code'] ?? null,
                    'category' => data_get($resource, 'category', []),

                    'severity_code' => $severity['code'] ?? null,
                    'severity_display' => $severity['display'] ?? null,

                    'code' => $code['code'] ?? null,
                    'code_display' => $code['display'] ?? null,

                    'body_site' => data_get($resource, 'bodySite', []),
                    'stage' => data_get($resource, 'stage', []),
                    'evidence' => data_get($resource, 'evidence', []),
                    'note' => data_get($resource, 'note', []),

                    'onset_datetime' => $this->parseFhirDate(
                        data_get($resource, 'onsetDateTime')
                    ),

                    'abatement_datetime' => $this->parseFhirDate(
                        data_get($resource, 'abatementDateTime')
                    ),

                    'recorded_date' => $this->parseFhirDate(
                        data_get($resource, 'recordedDate')
                    ),
                ]
            );

            // Pastikan hanya kolom yang benar-benar ada yang dikirim.
            $attributes = $this->filterExistingColumns(
                FhirCondition::class,
                $attributes
            );

            // Jangan JSON-encode atribut array di sini.
            // Cast pada model FhirCondition menangani serialisasi JSON.
            FhirCondition::updateOrCreate(
                ['fhir_id' => (string) $fhirId],
                $attributes
            );

            $processed++;
        }

        $this->saveCursor('Condition', $page['next_url'] ?? null);
    });

    return [
        'resource' => 'Condition',
        'processed' => $processed,
        'next_url' => $page['next_url'] ?? null,
        'completed' => empty($page['next_url']),
    ];
}


public function importProcedure(): array
{
    $page = $this->fetchPage(
        'Procedure',
        (int) config('fhir.procedure_page_size', 100)
    );

    $resources = $page['resources'] ?? [];
    $processed = 0;

    DB::transaction(function () use ($resources, $page, &$processed) {
        foreach ($resources as $resource) {
            if (($resource['resourceType'] ?? null) !== 'Procedure') {
                continue;
            }

            $fhirId = $resource['id'] ?? null;

            if (!$fhirId) {
                Log::warning('Procedure tanpa ID FHIR dilewati.');
                continue;
            }

            $code = $this->fhirCoding(
                data_get($resource, 'code', [])
            );

            $category = $this->fhirCoding(
                data_get($resource, 'category', [])
            );

            $statusReason = $this->fhirCoding(
                data_get($resource, 'statusReason', [])
            );

            $performedPeriod = data_get($resource, 'performedPeriod', []);

            $performers = $this->fhirPerformers(
                data_get($resource, 'performer', [])
            );

            $attributes = array_merge(
                $this->fhirCommonAttributes($resource, 'Procedure'),
                [
                    'identifier_system' => data_get(
                        $resource,
                        'identifier.0.system'
                    ),
                    'identifier_value' => data_get(
                        $resource,
                        'identifier.0.value'
                    ),

                    'status' => data_get($resource, 'status'),
                    'status_reason' => $statusReason,
                    'category' => $category,

                    'code_system' => $code['system'],
                    'code' => $code['code'],
                    'code_display' => $code['display'],

                    'performed_datetime' => $this->parseFhirDate(
                        data_get($resource, 'performedDateTime')
                    ),
                    'performed_start' => $this->parseFhirDate(
                        data_get($performedPeriod, 'start')
                    ),
                    'performed_end' => $this->parseFhirDate(
                        data_get($performedPeriod, 'end')
                    ),

                    'performer_reference' => data_get(
                        $resource,
                        'performer.0.actor.reference'
                    ),
                    'performer_display' => data_get(
                        $resource,
                        'performer.0.actor.display'
                    ),
                    'performer_data' => $performers,

                    'location_reference' => data_get(
                        $resource,
                        'location.reference'
                    ),

                    'reason_code' => data_get($resource, 'reasonCode', []),
                    'reason_reference' => data_get(
                        $resource,
                        'reasonReference',
                        []
                    ),

                    'body_site' => data_get($resource, 'bodySite', []),
                    'outcome' => data_get($resource, 'outcome'),
                    'complication' => data_get(
                        $resource,
                        'complication',
                        []
                    ),
                    'note' => data_get($resource, 'note', []),
                ]
            );

            $attributes = $this->filterExistingColumns(
                FhirProcedure::class,
                $attributes
            );

            FhirProcedure::updateOrCreate(
                ['fhir_id' => $fhirId],
                $attributes
            );

            $processed++;
        }

        $this->saveCursor('Procedure', $page['next_url'] ?? null);
    });

    return [
        'resource' => 'Procedure',
        'processed' => $processed,
        'next_url' => $page['next_url'] ?? null,
        'completed' => empty($page['next_url']),
    ];
}

public function importImmunization(): array
{
    $page = $this->fetchPage(
        'Immunization',
        (int) config('fhir.immunization_page_size', 100)
    );

    $resources = $page['resources'] ?? [];
    $processed = 0;

    DB::transaction(function () use ($resources, $page, &$processed) {
        foreach ($resources as $resource) {
            if (($resource['resourceType'] ?? null) !== 'Immunization') {
                continue;
            }

            $fhirId = $resource['id'] ?? null;

            if (!$fhirId) {
                Log::warning('Immunization tanpa ID FHIR dilewati.');
                continue;
            }

            $vaccine = $this->fhirCoding(
                data_get($resource, 'vaccineCode', [])
            );

            $site = $this->fhirCoding(
                data_get($resource, 'site', [])
            );

            $route = $this->fhirCoding(
                data_get($resource, 'route', [])
            );

            $performers = $this->fhirPerformers(
                data_get($resource, 'performer', [])
            );

            $dose = data_get($resource, 'doseQuantity', []);

            $attributes = array_merge(
                $this->fhirCommonAttributes($resource, 'Immunization'),
                [
                    'identifier_system' => data_get(
                        $resource,
                        'identifier.0.system'
                    ),
                    'identifier_value' => data_get(
                        $resource,
                        'identifier.0.value'
                    ),

                    'status' => data_get($resource, 'status'),
                    'status_reason' => data_get(
                        $resource,
                        'statusReason'
                    ),

                    'vaccine_code_system' => $vaccine['system'],
                    'vaccine_code' => $vaccine['code'],
                    'vaccine_display' => $vaccine['display'],

                    'occurrence_datetime' => $this->parseFhirDate(
                        data_get($resource, 'occurrenceDateTime')
                    ),

                    'recorded_date' => $this->parseFhirDate(
                        data_get($resource, 'recorded')
                    ),

                    'primary_source' => data_get(
                        $resource,
                        'primarySource'
                    ),

                    'lot_number' => data_get($resource, 'lotNumber'),

                    'expiration_date' => $this->parseFhirDate(
                        data_get($resource, 'expirationDate')
                    ),

                    'site' => $site,
                    'route' => $route,

                    'dose_quantity' => data_get($dose, 'value'),
                    'dose_unit' => data_get($dose, 'unit'),

                    'performer_reference' => data_get(
                        $resource,
                        'performer.0.actor.reference'
                    ),
                    'performer_display' => data_get(
                        $resource,
                        'performer.0.actor.display'
                    ),
                    'performer_data' => $performers,

                    'protocol_applied' => data_get(
                        $resource,
                        'protocolApplied',
                        []
                    ),

                    'reaction' => data_get($resource, 'reaction', []),
                    'note' => data_get($resource, 'note', []),
                ]
            );

            $attributes = $this->filterExistingColumns(
                FhirImmunization::class,
                $attributes
            );

            FhirImmunization::updateOrCreate(
                ['fhir_id' => $fhirId],
                $attributes
            );

            $processed++;
        }

        $this->saveCursor('Immunization', $page['next_url'] ?? null);
    });

    return [
        'resource' => 'Immunization',
        'processed' => $processed,
        'next_url' => $page['next_url'] ?? null,
        'completed' => empty($page['next_url']),
    ];
}

    /**
     * Normalisasi URL agar token hanya dikirim ke host FHIR yang diizinkan.
     */
    protected function normalizeFhirUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            throw new RuntimeException('URL FHIR kosong.');
        }

        $parts = parse_url($url);

        if (!is_array($parts)) {
            throw new RuntimeException('URL FHIR tidak valid.');
        }

        if (empty($parts['host'])) {
            if (str_starts_with($url, '?')) {
                throw new RuntimeException(
                    'URL pagination hanya berisi query dan tidak didukung.'
                );
            }

            if (str_starts_with($url, '/')) {
                $url = 'https://' . self::FHIR_HOST . $url;
            } else {
                $url = $this->server . '/' . ltrim($url, '/');
            }

            $parts = parse_url($url);
        }

        if (
            !is_array($parts) ||
            strtolower((string) ($parts['host'] ?? '')) !== self::FHIR_HOST ||
            !in_array(strtolower((string) ($parts['scheme'] ?? '')), ['http', 'https'], true) ||
            isset($parts['user']) ||
            isset($parts['pass'])
        ) {
            throw new RuntimeException('Host atau format URL FHIR tidak diizinkan.');
        }

        $path = $parts['path'] ?? '/';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';

        return 'https://' . self::FHIR_HOST . $path . $query;
    }

    /**
     * Melakukan satu HTTP request dan mengembalikan JSON.
     * HTTP error dilempar sebagai RuntimeException dengan status HTTP.
     */
    protected function request(string $url): array
    {
        $url = $this->normalizeFhirUrl($url);

        try {
            $response = Http::acceptJson()
                ->withToken($this->token)
                ->connectTimeout(20)
                ->timeout((int) config('fhir.timeout', 120))
                ->retry(
                    3,
                    1000,
                    fn ($exception) => $exception instanceof ConnectionException,
                    throw: false
                )
                ->get($url);
        } catch (Throwable $e) {
            Log::error('FHIR koneksi gagal', [
                'url' => $url,
                'message' => $e->getMessage(),
            ]);

            throw new RuntimeException('Koneksi FHIR gagal: ' . $e->getMessage(), 0, $e);
        }

        if ($response->failed()) {
            $body = substr($response->body(), 0, 1000);

            Log::error('FHIR request gagal', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $body,
            ]);

            throw new RuntimeException(
                'FHIR API gagal: HTTP ' . $response->status()
            );
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new RuntimeException('Respons FHIR bukan JSON yang valid.');
        }

        return $data;
    }

    /**
     * Mengambil halaman Patient/Encounter.
     * Cursor HAPI yang kedaluwarsa (HTTP 410) akan di-reset dengan aman
     * dan pencarian dimulai ulang dari halaman pertama.
     */
    protected function fetchPage(string $resourceType, int $pageSize): array
    {
       if (!in_array($resourceType, [
    'Patient',
    'Encounter',
    'Observation',
    'Condition',
    'Procedure',
    'Immunization',
], true)) {
    throw new RuntimeException('Resource FHIR tidak didukung.');
}

        $pageSize = max(1, $pageSize);

        $state = DB::table('fhir_sync_states')
            ->where('resource_type', $resourceType)
            ->first();

        $cursorUrl = trim((string) ($state?->next_url ?? ''));
        $usingCursor = $cursorUrl !== '';

        $url = $usingCursor
            ? $this->normalizeFhirUrl($cursorUrl)
            : $this->initialSearchUrl($resourceType, $pageSize);

        try {
            $bundle = $this->request($url);
        } catch (RuntimeException $e) {
            $isGone = str_contains($e->getMessage(), 'HTTP 410');
            $isHapiCursor = $usingCursor && str_contains($cursorUrl, '_getpages=');

            if (!$isGone || !$isHapiCursor) {
                throw $e;
            }

            Log::warning('FHIR cursor HAPI kedaluwarsa; mengulang pencarian dari halaman pertama.', [
                'resource_type' => $resourceType,
                'cursor_url' => $cursorUrl,
            ]);

            // Hapus cursor lama hanya setelah memastikan bahwa cursor tersebut
            // memang cursor HAPI yang kedaluwarsa.
            $this->saveCursor($resourceType, null);

            $url = $this->initialSearchUrl($resourceType, $pageSize);
            $bundle = $this->request($url);
        }

        if (($bundle['resourceType'] ?? null) !== 'Bundle') {
            throw new RuntimeException("Respons {$resourceType} bukan FHIR Bundle.");
        }

        $resources = [];

        foreach ($bundle['entry'] ?? [] as $entry) {
            $resource = $entry['resource'] ?? null;

            if (
                !is_array($resource) ||
                ($resource['resourceType'] ?? null) !== $resourceType ||
                empty($resource['id'])
            ) {
                continue;
            }

            $resources[] = $resource;
        }

        $nextUrl = null;

        foreach ($bundle['link'] ?? [] as $link) {
            if (($link['relation'] ?? null) !== 'next') {
                continue;
            }

            $next = trim((string) ($link['url'] ?? ''));

            if ($next !== '') {
                $nextUrl = $this->normalizeFhirUrl($next);
            }

            break;
        }

        return [
            'resources' => $resources,
            'next_url' => $nextUrl,
        ];
    }

    protected function initialSearchUrl(string $resourceType, int $pageSize): string
    {
        return $this->server . '/' . $resourceType . '?' . http_build_query([
            '_count' => max(1, $pageSize),
            '_pretty' => 'true',
            '_bundletype' => 'searchset',
        ]);
    }

    /**
     * Simpan cursor setelah batch berhasil.
     */
    protected function saveCursor(string $resourceType, ?string $nextUrl): void
    {
        $now = now();

        $existing = DB::table('fhir_sync_states')
            ->where('resource_type', $resourceType)
            ->exists();

        $values = [
            'next_url' => $nextUrl,
            'last_completed_at' => $nextUrl === null ? $now : null,
            'updated_at' => $now,
        ];

        if ($existing) {
            DB::table('fhir_sync_states')
                ->where('resource_type', $resourceType)
                ->update($values);
        } else {
            DB::table('fhir_sync_states')->insert([
                'resource_type' => $resourceType,
                ...$values,
                'created_at' => $now,
            ]);
        }
    }

    /**
     * Mengambil ID dari referensi seperti Patient/123 atau URL absolut.
     */
    protected function referenceId(?string $reference, string $resourceType): ?string
    {
        if (!$reference) {
            return null;
        }

        $reference = trim($reference);

        if (preg_match(
            '~(?:^|/)' . preg_quote($resourceType, '~') . '/([^/?#]+)(?:[?#].*)?$~',
            $reference,
            $matches
        )) {
            return rawurldecode($matches[1]);
        }

        return null;
    }

    protected function practitionerName(array $resource): ?string
    {
        $name = $resource['name'][0] ?? [];

        if (!empty($name['text'])) {
            return $name['text'];
        }

        $parts = [];

        foreach (['prefix', 'given', 'family', 'suffix'] as $field) {
            $value = $name[$field] ?? [];

            if (is_array($value)) {
                $parts = array_merge($parts, $value);
            } elseif (is_string($value) && $value !== '') {
                $parts[] = $value;
            }
        }

        $parts = array_values(array_filter(
            $parts,
            fn ($value) => is_string($value) && trim($value) !== ''
        ));

        return $parts ? implode(' ', $parts) : null;
    }

    /**
     * Sinkronkan Organization, Practitioner, dan Location yang dirujuk
     * oleh Encounter yang sudah tersimpan.
     */
    public function importEncounterReferences(): array
    {
        $references = [
            'Organization' => [],
            'Practitioner' => [],
            'Location' => [],
        ];

        FhirEncounter::query()
            ->select(['id', 'raw_resource'])
            ->chunkById(200, function ($encounters) use (&$references) {
                foreach ($encounters as $encounter) {
                    $resource = $encounter->raw_resource;

                    if (!is_array($resource)) {
                        continue;
                    }

                    $organizationId = $this->referenceId(
                        data_get($resource, 'serviceProvider.reference'),
                        'Organization'
                    );

                    if ($organizationId !== null) {
                        $references['Organization'][$organizationId] = true;
                    }

                    foreach ($resource['participant'] ?? [] as $participant) {
                        $id = $this->referenceId(
                            data_get($participant, 'individual.reference'),
                            'Practitioner'
                        );

                        if ($id !== null) {
                            $references['Practitioner'][$id] = true;
                        }
                    }

                    foreach ($resource['location'] ?? [] as $entry) {
                        $id = $this->referenceId(
                            data_get($entry, 'location.reference'),
                            'Location'
                        );

                        if ($id !== null) {
                            $references['Location'][$id] = true;
                        }
                    }
                }
            });

        $stats = [
            'organization' => ['found' => count($references['Organization']), 'saved' => 0, 'failed' => 0],
            'practitioner' => ['found' => count($references['Practitioner']), 'saved' => 0, 'failed' => 0],
            'location' => ['found' => count($references['Location']), 'saved' => 0, 'failed' => 0],
        ];

        $resourceConfig = [
            'Organization' => ['key' => 'organization', 'model' => FhirOrganization::class],
            'Practitioner' => ['key' => 'practitioner', 'model' => FhirPractitioner::class],
            'Location' => ['key' => 'location', 'model' => FhirLocation::class],
        ];

        foreach ($resourceConfig as $resourceType => $config) {
            foreach (array_keys($references[$resourceType]) as $fhirId) {
                try {
                    $url = $this->server . '/' . $resourceType . '/' . rawurlencode($fhirId);
                    $resource = $this->request($url);

                    if (
                        ($resource['resourceType'] ?? null) !== $resourceType ||
                        (string) ($resource['id'] ?? '') !== (string) $fhirId
                    ) {
                        throw new RuntimeException("Resource {$resourceType}/{$fhirId} tidak sesuai.");
                    }

                    $meta = $resource['meta'] ?? [];

                    $data = [
                        'fhir_id' => $fhirId,
                        'active' => array_key_exists('active', $resource) ? $resource['active'] : null,
                        'raw_resource' => $resource,
                        'fhir_last_updated' => !empty($meta['lastUpdated'])
                            ? Carbon::parse($meta['lastUpdated'])
                            : null,
                        'synced_at' => now(),
                    ];

                    if ($resourceType === 'Organization') {
                        $data += [
                            'name' => $resource['name'] ?? null,
                            'identifier' => $resource['identifier'] ?? [],
                            'type' => $resource['type'] ?? [],
                            'telecom' => $resource['telecom'] ?? [],
                            'address' => $resource['address'] ?? [],
                        ];
                    } elseif ($resourceType === 'Practitioner') {
                        $data += [
                            'name' => $this->practitionerName($resource),
                            'gender' => $resource['gender'] ?? null,
                            'birth_date' => $resource['birthDate'] ?? null,
                            'identifier' => $resource['identifier'] ?? [],
                            'name_data' => $resource['name'] ?? [],
                            'telecom' => $resource['telecom'] ?? [],
                            'address' => $resource['address'] ?? [],
                            'qualification' => $resource['qualification'] ?? [],
                        ];
                    } else {
                        $data += [
                            'name' => $resource['name'] ?? null,
                            'status' => $resource['status'] ?? null,
                            'mode' => $resource['mode'] ?? null,
                            'type' => $resource['type'] ?? [],
                            'physical_type' => $resource['physicalType'] ?? [],
                            'managing_organization_id' => $this->referenceId(
                                data_get($resource, 'managingOrganization.reference'),
                                'Organization'
                            ),
                            'part_of_id' => $this->referenceId(
                                data_get($resource, 'partOf.reference'),
                                'Location'
                            ),
                            'identifier' => $resource['identifier'] ?? [],
                            'telecom' => $resource['telecom'] ?? [],
                            'address' => $resource['address'] ?? [],
                            'position' => $resource['position'] ?? [],
                        ];
                    }

                    $modelClass = $config['model'];
                    $modelClass::updateOrCreate(['fhir_id' => $fhirId], $data);

                    $stats[$config['key']]['saved']++;
                } catch (Throwable $e) {
                    $stats[$config['key']]['failed']++;

                    Log::warning('FHIR reference import gagal', [
                        'resource_type' => $resourceType,
                        'fhir_id' => $fhirId,
                        'message' => $e->getMessage(),
                    ]);
                }
            }
        }

        Log::info('FHIR Encounter references selesai', $stats);

        return $stats;
    }

    /**
     * Import satu halaman Patient.
     */
    public function importPatient(): array
    {
        $pageSize = max(1, (int) config('fhir.patient_page_size', 100));
        $page = $this->fetchPage('Patient', $pageSize);
        $saved = 0;

        DB::transaction(function () use ($page, &$saved) {
            foreach ($page['resources'] as $resource) {
                $data = $this->patientMapper->map($resource);

                if (!is_array($data) || empty($data['fhir_id'])) {
                    throw new RuntimeException('FhirPatientMapper tidak menghasilkan fhir_id.');
                }

                $data['fhir_id'] = $resource['id'];
                unset($data['id'], $data['created_at'], $data['fhir_id']);
                $data['updated_at'] = now();

                FhirPatient::updateOrCreate(
                    ['fhir_id' => $resource['id']],
                    $data
                );

                $saved++;
            }

            $this->saveCursor('Patient', $page['next_url']);
        });

        Log::info('FHIR Patient batch selesai', [
            'saved' => $saved,
            'has_next_page' => $page['next_url'] !== null,
        ]);

        return [
            'saved' => $saved,
            'has_next_page' => $page['next_url'] !== null,
        ];
    }

    /**
     * Import satu halaman Encounter.
     */
    public function importEncounter(): array
    {
        $page = $this->fetchPage('Encounter', config('fhir.encounter_page_size', 100));
        $saved = 0;

        DB::transaction(function () use ($page, &$saved) {
            foreach ($page['resources'] as $resource) {
                $patientFhirId = $this->referenceId(
                    data_get($resource, 'subject.reference'),
                    'Patient'
                );

                $patient = $patientFhirId
                    ? FhirPatient::where('fhir_id', $patientFhirId)->first()
                    : null;

                $identifier = $resource['identifier'][0] ?? [];
                $encounterClass = $resource['class'] ?? [];
                $meta = $resource['meta'] ?? [];
                $period = $resource['period'] ?? [];

                $providerRef = data_get($resource, 'serviceProvider.reference');
                $providerId = $this->referenceId($providerRef, 'Organization');
                $providerName = data_get($resource, 'serviceProvider.display');

                // Hindari request referensi terpisah di sini. Referensi lengkap
                // disinkronkan oleh importEncounterReferences().
                $locationEntries = $resource['location'] ?? [];
                $firstLocation = $locationEntries[0]['location'] ?? [];
                $locationId = $this->referenceId(
                    $firstLocation['reference'] ?? null,
                    'Location'
                );

                $participantEntries = $resource['participant'] ?? [];
                $firstParticipant = $participantEntries[0] ?? [];
                $practitionerReference = data_get($firstParticipant, 'individual.reference');
                $practitionerId = $this->referenceId(
                    $practitionerReference,
                    'Practitioner'
                );
                $practitionerDisplay = data_get($firstParticipant, 'individual.display');

                $episodeOfCareId = $this->referenceId(
                    data_get($resource, 'episodeOfCare.0.reference'),
                    'EpisodeOfCare'
                );

                FhirEncounter::updateOrCreate(
                    ['fhir_id' => $resource['id']],
                    [
                        'version_id' => $meta['versionId'] ?? null,
                        'fhir_last_updated' => !empty($meta['lastUpdated'])
                            ? Carbon::parse($meta['lastUpdated'])
                            : null,
                        'patient_id' => $patient?->id,
                        'fhir_patient_id' => $patientFhirId,
                        'patient_display' => data_get($resource, 'subject.display'),
                        'identifier_system' => $identifier['system'] ?? null,
                        'identifier_value' => $identifier['value'] ?? null,
                        'class_code' => $encounterClass['code'] ?? null,
                        'class_display' => $encounterClass['display'] ?? null,
                        'service_provider_id' => $providerId,
                        'service_provider' => $providerName,
                        'location_id' => $locationId,
                        'location' => $firstLocation['display'] ?? null,
                        'practitioner_id' => $practitionerId,
                        'practitioner_display' => $practitionerDisplay,
                        'episode_of_care_id' => $episodeOfCareId,
                        'status' => $resource['status'] ?? null,
                        'status_history' => $resource['statusHistory'] ?? [],
                        'participant_data' => $participantEntries,
                        'location_data' => $locationEntries,
                        'encounter_date' => !empty($period['start'])
                            ? Carbon::parse($period['start'])
                            : null,
                        'encounter_end' => !empty($period['end'])
                            ? Carbon::parse($period['end'])
                            : null,
                        'raw_resource' => $resource,
                        'synced_at' => now(),
                    ]
                );

                $saved++;
            }

            $this->saveCursor('Encounter', $page['next_url']);
        });

        Log::info('FHIR Encounter batch selesai', [
            'saved' => $saved,
            'has_next_page' => $page['next_url'] !== null,
        ]);

        return [
            'saved' => $saved,
            'has_next_page' => $page['next_url'] !== null,
        ];
    }

    /**
     * Import satu halaman Observation.
     * Nilai value[x] yang umum dipetakan ke kolom khusus; value_json dan
     * raw_resource tetap menyimpan payload lengkap agar tidak kehilangan data.
     */
    public function importObservation(): array
    {
        $pageSize = max(1, (int) config('fhir.observation_page_size', 100));
        $page = $this->fetchPage('Observation', $pageSize);
        $saved = 0;

        DB::transaction(function () use ($page, &$saved) {
            foreach ($page['resources'] as $resource) {
                $subjectReference = data_get($resource, 'subject.reference');
                $patientFhirId = $this->referenceId($subjectReference, 'Patient');
                $patient = $patientFhirId !== null
                    ? FhirPatient::where('fhir_id', $patientFhirId)->first()
                    : null;

                $encounterReference = data_get($resource, 'encounter.reference');
                $encounterFhirId = $this->referenceId($encounterReference, 'Encounter');
                $encounter = $encounterFhirId !== null
                    ? FhirEncounter::where('fhir_id', $encounterFhirId)->first()
                    : null;

                $identifier = $resource['identifier'][0] ?? [];
                $coding = data_get($resource, 'code.coding.0', []);
                $meta = $resource['meta'] ?? [];

                $effectiveDateTime = null;
                $effectiveStart = null;
                $effectiveEnd = null;
                if (!empty($resource['effectiveDateTime'])) {
                    $effectiveDateTime = Carbon::parse($resource['effectiveDateTime']);
                } elseif (is_array($resource['effectivePeriod'] ?? null)) {
                    $effectiveStart = !empty($resource['effectivePeriod']['start'])
                        ? Carbon::parse($resource['effectivePeriod']['start'])
                        : null;
                    $effectiveEnd = !empty($resource['effectivePeriod']['end'])
                        ? Carbon::parse($resource['effectivePeriod']['end'])
                        : null;
                    $effectiveDateTime = $effectiveStart;
                } elseif (!empty($resource['effectiveInstant'])) {
                    $effectiveDateTime = Carbon::parse($resource['effectiveInstant']);
                }

                $valueMap = [
                    'valueQuantity' => 'Quantity',
                    'valueCodeableConcept' => 'CodeableConcept',
                    'valueString' => 'string',
                    'valueBoolean' => 'boolean',
                    'valueInteger' => 'integer',
                    'valueRange' => 'Range',
                    'valueRatio' => 'Ratio',
                    'valueSampledData' => 'SampledData',
                    'valueTime' => 'time',
                    'valueDateTime' => 'dateTime',
                    'valuePeriod' => 'Period',
                    'valueAttachment' => 'Attachment',
                    'valueReference' => 'Reference',
                    'valueCount' => 'Count',
                    'valueDistance' => 'Distance',
                    'valueDuration' => 'Duration',
                    'valueMoney' => 'Money',
                ];

                $valueType = null;
                $value = null;
                foreach ($valueMap as $field => $type) {
                    if (array_key_exists($field, $resource)) {
                        $valueType = $type;
                        $value = $resource[$field];
                        break;
                    }
                }

                $valueQuantity = null;
                $valueUnit = null;
                $valueCode = null;
                $valueDisplay = null;
                $valueString = null;
                $valueBoolean = null;
                $valueInteger = null;
                $valueDateTime = null;

                if ($valueType === 'Quantity' && is_array($value)) {
                    $numeric = $value['value'] ?? null;
                    $valueQuantity = is_numeric($numeric) ? $numeric : null;
                    $valueUnit = $value['unit'] ?? $value['code'] ?? null;
                    $valueCode = $value['code'] ?? null;
                    $valueDisplay = $value['unit'] ?? null;
                } elseif ($valueType === 'CodeableConcept' && is_array($value)) {
                    $valueCoding = $value['coding'][0] ?? [];
                    $valueCode = $valueCoding['code'] ?? null;
                    $valueDisplay = $value['text'] ?? $valueCoding['display'] ?? null;
                } elseif ($valueType === 'string') {
                    $valueString = is_scalar($value) ? (string) $value : null;
                } elseif ($valueType === 'boolean') {
                    $valueBoolean = is_bool($value) ? $value : null;
                } elseif ($valueType === 'integer' || $valueType === 'Count') {
                    $valueInteger = is_numeric($value) ? (int) $value : null;
                    if ($valueType === 'Count' && is_array($value)) {
                        $valueInteger = isset($value['value']) && is_numeric($value['value'])
                            ? (int) $value['value'] : null;
                        $valueUnit = $value['unit'] ?? null;
                    }
                } elseif ($valueType === 'dateTime' && is_string($value)) {
                    $valueDateTime = Carbon::parse($value);
                    $valueString = $value;
                } elseif (in_array($valueType, ['Range', 'Ratio', 'Period', 'SampledData', 'Attachment', 'Reference', 'Distance', 'Duration', 'Money'], true) && is_array($value)) {
                    $valueDisplay = $value['text'] ?? $value['unit'] ?? null;
                }

                FhirObservation::updateOrCreate(
                    ['fhir_id' => (string) $resource['id']],
                    [
                        'version_id' => $meta['versionId'] ?? null,
                        'fhir_last_updated' => !empty($meta['lastUpdated'])
                            ? Carbon::parse($meta['lastUpdated']) : null,
                        'patient_id' => $patient?->id,
                        'fhir_patient_id' => $patientFhirId,
                        'encounter_id' => $encounter?->id,
                        'fhir_encounter_id' => $encounterFhirId,
                        'identifier_system' => $identifier['system'] ?? null,
                        'identifier_value' => $identifier['value'] ?? null,
                        'status' => $resource['status'] ?? null,
                        'category' => $resource['category'] ?? [],
                        'code_system' => $coding['system'] ?? null,
                        'code' => $coding['code'] ?? null,
                        'code_display' => data_get($resource, 'code.text') ?? ($coding['display'] ?? null),
                        'effective_datetime' => $effectiveDateTime,
                        'effective_start' => $effectiveStart,
                        'effective_end' => $effectiveEnd,
                        'issued_at' => !empty($resource['issued'])
                            ? Carbon::parse($resource['issued']) : null,
                        'value_type' => $valueType,
                        'value_quantity' => $valueQuantity,
                        'value_unit' => $valueUnit,
                        'value_code' => $valueCode,
                        'value_display' => $valueDisplay,
                        'value_string' => $valueString,
                        'value_boolean' => $valueBoolean,
                        'value_integer' => $valueInteger,
                        'value_datetime' => $valueDateTime,
                        'value_json' => $value,
                        'interpretation' => $resource['interpretation'] ?? [],
                        'reference_range' => $resource['referenceRange'] ?? [],
                        'component' => $resource['component'] ?? [],
                        'performer_data' => $resource['performer'] ?? [],
                        'note' => $resource['note'] ?? [],
                        'raw_resource' => $resource,
                        'synced_at' => now(),
                    ]
                );

                $saved++;
            }

            $this->saveCursor('Observation', $page['next_url']);
        });

        Log::info('FHIR Observation batch selesai', [
            'saved' => $saved,
            'has_next_page' => $page['next_url'] !== null,
        ]);

        return [
            'saved' => $saved,
            'has_next_page' => $page['next_url'] !== null,
        ];
    }

    /**
     * Isi ulang foreign key internal yang sebelumnya belum dapat dipetakan
     * karena Patient/Encounter belum terimpor saat Observation pertama kali masuk.
     */
    protected function backfillObservationReferences(): int
    {
        $updated = 0;

        FhirObservation::query()
            ->where(function ($query) {
                $query->whereNull('patient_id')->orWhereNull('encounter_id');
            })
            ->where(function ($query) {
                $query->whereNotNull('fhir_patient_id')->orWhereNotNull('fhir_encounter_id');
            })
            ->select(['id', 'patient_id', 'fhir_patient_id', 'encounter_id', 'fhir_encounter_id'])
            ->chunkById(200, function ($observations) use (&$updated) {
                foreach ($observations as $observation) {
                    $changes = [];

                    if (!$observation->patient_id && $observation->fhir_patient_id) {
                        $patientId = FhirPatient::where('fhir_id', $observation->fhir_patient_id)->value('id');
                        if ($patientId) {
                            $changes['patient_id'] = $patientId;
                        }
                    }

                    if (!$observation->encounter_id && $observation->fhir_encounter_id) {
                        $encounterId = FhirEncounter::where('fhir_id', $observation->fhir_encounter_id)->value('id');
                        if ($encounterId) {
                            $changes['encounter_id'] = $encounterId;
                        }
                    }

                    if ($changes) {
                        FhirObservation::whereKey($observation->id)->update($changes);
                        $updated++;
                    }
                }
            });

        return $updated;
    }

    /**
     * Jalankan satu batch Patient, Encounter, Observation,
     * lalu sinkronkan referensi dari Encounter yang tersimpan.
     */
   public function run(): array
{
    $patient = $this->importPatient();
    $encounter = $this->importEncounter();
    $observation = $this->importObservation();

    $condition = $this->importCondition();
    $procedure = $this->importProcedure();
    $immunization = $this->importImmunization();

    return [
        'patient' => $patient,
        'encounter' => $encounter,
        'observation' => $observation,
        'condition' => $condition,
        'procedure' => $procedure,
        'immunization' => $immunization,
    ];
}
}
