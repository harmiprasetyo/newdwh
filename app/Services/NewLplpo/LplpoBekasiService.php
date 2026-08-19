<?php

namespace App\Services\NewLplpo;

use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class LplpoBekasiService
{
    protected string $endpoint;

    protected int $timeout;

    protected ?string $apiKey;

    protected ?string $cookie;


    public function __construct()
    {
        $this->endpoint = (string) config(
            'services.lplpo.url'
        );

        $this->timeout = (int) config(
            'services.lplpo.timeout',
            60
        );

        $this->apiKey = config(
            'services.lplpo.api_key'
        );

        $this->cookie = config(
            'services.lplpo.cookie'
        );
    }


    /**
     * Get LPLPO data from API.
     */
    public function getData(
        int $page = 1,
        int $limit = 50,
        ?string $startDate = null,
        ?string $endDate = null
    ): array {

        $startDate = $startDate
            ? Carbon::parse($startDate)->format('Y-m-d')
            : now()->startOfMonth()->format('Y-m-d');

        $endDate = $endDate
            ? Carbon::parse($endDate)->format('Y-m-d')
            : now()->endOfMonth()->format('Y-m-d');


        $params = [
            'limit'      => $limit,
            'page'       => $page,
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ];


        try {

            $response = $this->httpClient()
                ->get(
                    $this->endpoint,
                    $params
                );


            if (!$response->successful()) {

                Log::error(
                    'LPLPO API request failed',
                    [
                        'endpoint' => $this->endpoint,
                        'status'   => $response->status(),
                        'params'   => $params,
                        'body'     => $response->body(),
                    ]
                );


                return [
                    'success' => false,
                    'data'    => [],
                    'hasNext' => false,
                    'message' =>
                        'API LPLPO mengembalikan HTTP ' .
                        $response->status(),
                ];
            }


            $json = $response->json();

            $data = $this->normalizeData(
                $json
            );


            /*
             * API tidak mengirim total data.
             *
             * Jika jumlah data masih 50,
             * kita anggap masih ada kemungkinan
             * halaman berikutnya.
             */
            $hasNext = count($data) >= $limit;


            return [
                'success' => true,
                'data'    => $data,
                'hasNext' => $hasNext,
                'message' => null,
            ];

        } catch (Throwable $e) {

            Log::error(
                'LPLPO API exception',
                [
                    'endpoint' => $this->endpoint,
                    'params'   => $params,
                    'message'  => $e->getMessage(),
                ]
            );


            return [
                'success' => false,
                'data'    => [],
                'hasNext' => false,
                'message' =>
                    'Tidak dapat terhubung ke API LPLPO.',
            ];
        }
    }


    /**
     * Build HTTP client.
     */
    protected function httpClient(): PendingRequest
    {
        $client = Http::timeout(
            $this->timeout
        )
        ->acceptJson();


        /*
         * API KEY
         */
        if (!empty($this->apiKey)) {

            $client->withHeaders([
                'X-API-KEY' => $this->apiKey,
            ]);

        }


        /*
         * COOKIE
         */
        if (!empty($this->cookie)) {

            $client->withHeaders([
                'Cookie' => $this->cookie,
            ]);

        }


        return $client;
    }


    /**
     * Normalize API response.
     */
    protected function normalizeData($json): array
    {
        if (!$json) {
            return [];
        }


        /*
         * Jika API nantinya berubah menjadi:
         *
         * {
         *     "data": [...]
         * }
         */
        if (
            isset($json['data']) &&
            is_array($json['data'])
        ) {

            return array_values(
                $json['data']
            );
        }


        /*
         * Response API sekarang:
         *
         * {
         *     "0": {...},
         *     "1": {...},
         *     "2": {...}
         * }
         */
        if (is_array($json)) {

            return array_values(
                $json
            );
        }


        return [];
    }
}
