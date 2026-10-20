<?php
namespace App\Services\Fhir;

use Carbon\Carbon;

class FhirPatientMapper
{
    protected function identifierValue(
        array $identifiers,
        array $codes,
        array $systems = []
    ): ?string {
        foreach ($identifiers as $identifier) {
            $value = trim((string) ($identifier['value'] ?? ''));

            if ($value === '') {
                continue;
            }

            $type = $identifier['type'] ?? [];
            $coding = $type['coding'] ?? [];

            $typeCodes = [];
            foreach ($coding as $item) {
                if (!empty($item['code'])) {
                    $typeCodes[] = strtoupper($item['code']);
                }
            }

            if (!empty($type['text'])) {
                $typeCodes[] = strtoupper($type['text']);
            }

            $system = strtolower(
                (string) ($identifier['system'] ?? '')
            );

            // Utamakan tipe identifier, misalnya BPJS-Kes.
            if (array_intersect($codes, $typeCodes)) {
                return $value;
            }

            // Gunakan system sebagai fallback.
            if (
                $systems &&
                in_array($system, $systems, true) &&
                !array_intersect(
                    ['BPJS-KES', 'BPJS', 'NIK-IBU'],
                    $typeCodes
                )
            ) {
                return $value;
            }
        }

        return null;
    }

    protected function administrativeCodes(array $address): array
    {
        $result = [
            'province_code' => null,
            'province_name' => null,
            'city_code' => null,
            'city_name' => null,
            'district_code' => null,
            'district_name' => null,
            'village_code' => null,
            'village_name' => null,
            'rw' => null,
            'rt' => null,
        ];

        foreach ($address['extension'] ?? [] as $extension) {
            foreach ($extension['extension'] ?? [] as $item) {
                $key = strtolower(
                    (string) ($item['url'] ?? '')
                );

                $coding = $item['valueCoding'] ?? [];
                $code = $coding['code'] ?? null;
                $display = trim(
                    (string) ($coding['display'] ?? '')
                );

                $name = $display !== ''
                    && $display !== (string) $code
                    ? $display
                    : null;

                switch ($key) {
                    case 'province':
                        $result['province_code'] = $code;
                        $result['province_name'] = $name;
                        break;

                    case 'city':
                        $result['city_code'] = $code;
                        $result['city_name'] = $name;
                        break;

                    case 'district':
                        $result['district_code'] = $code;
                        $result['district_name'] = $name;
                        break;

                    case 'village':
                        $result['village_code'] = $code;

                        // Hindari menyimpan label generik
                        // seperti "Kalurahan" sebagai nama desa.
                        $result['village_name'] =
                            $name && !in_array(
                                strtolower($name),
                                ['kalurahan', 'kelurahan', 'desa'],
                                true
                            )
                                ? $name
                                : null;
                        break;

                    case 'rw':
                        $result['rw'] = $display !== ''
                            ? $display
                            : $code;
                        break;

                    case 'rt':
                        $result['rt'] = $display !== ''
                            ? $display
                            : $code;
                        break;
                }
            }
        }

        return $result;
    }

    public function map(array $resource): array
    {
        $identifiers = $resource['identifier'] ?? [];

        $name = collect($resource['name'] ?? [])
            ->firstWhere('use', 'official')
            ?? ($resource['name'][0] ?? []);

        $telecom = $resource['telecom'] ?? [];

        $phone = null;
        $email = null;

        foreach ($telecom as $item) {
            $value = trim((string) ($item['value'] ?? ''));

            if ($value === '') {
                continue;
            }

            if (($item['system'] ?? null) === 'phone' && !$phone) {
                $phone = $value;
            }

            if (($item['system'] ?? null) === 'email' && !$email) {
                $email = $value;
            }
        }

        // Pilih alamat pertama yang memiliki line atau
        // administrativeCode. Pada contoh, alamat postal
        // muncul sebelum alamat physical.
        $address = collect($resource['address'] ?? [])
            ->first(function ($item) {
                return !empty($item['line'])
                    || !empty($item['extension']);
            }) ?? [];

        $addressLine = implode(
            ', ',
            array_filter($address['line'] ?? [], fn ($v) =>
                trim((string) $v) !== ''
            )
        );

        $administrative = $this->administrativeCodes($address);

        $birthDate = null;

        if (!empty($resource['birthDate'])) {
            try {
                $birthDate = Carbon::parse(
                    $resource['birthDate']
                )->toDateString();
            } catch (\Throwable $e) {
                $birthDate = null;
            }
        }

        $meta = $resource['meta'] ?? [];

        return array_merge([
            'fhir_id' => $resource['id'],
            'version_id' => $meta['versionId'] ?? null,
            'fhir_last_updated' => !empty($meta['lastUpdated'])
                ? Carbon::parse($meta['lastUpdated'])
                : null,

            'ihs_number' => $this->identifierValue(
                $identifiers,
                ['IHS'],
                ['https://fhir.kemkes.go.id/id/ihs-number']
            ),

            'nik' => $this->identifierValue(
                $identifiers,
                ['NIK'],
                ['https://fhir.kemkes.go.id/id/nik']
            ),

            'nik_ibu' => $this->identifierValue(
                $identifiers,
                ['NIK-IBU'],
                ['https://fhir.kemkes.go.id/id/nik-ibu']
            ),

            'bpjs_number' => $this->identifierValue(
                $identifiers,
                ['BPJS-KES', 'BPJS'],
                []
            ),

            'active' => $resource['active'] ?? null,
            'name' => $name['text']
                ?? $name['family']
                ?? null,
            'family_name' => $name['family'] ?? null,
            'given_names' => $name['given'] ?? [],
            'gender' => $resource['gender'] ?? null,
            'birth_date' => $birthDate,
            'phone' => $phone,
            'email' => $email,
            'address_line' => $addressLine ?: null,

            'raw_resource' => $resource,
            'synced_at' => now(),
        ], $administrative);
    }
}
