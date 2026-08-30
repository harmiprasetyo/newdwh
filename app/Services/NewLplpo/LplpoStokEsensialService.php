<?php

namespace App\Services\NewLplpo;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LplpoStokEsensialService
{
    /**
     * ==========================================================
     * FASKES USER
     * ==========================================================
     */
    public function getFaskesForUser($user): Collection
    {
        $groupId = (int) $user->groupid;

        $query = DB::table('master_faskes')
            ->select(
                'kodeFaskes',
                'namaFaskes'
            );

        /*
        |--------------------------------------------------------------------------
        | GROUP 1
        |--------------------------------------------------------------------------
        | Semua faskes
        */

        if ($groupId === 1) {

            return $query
                ->orderBy('namaFaskes')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | GROUP 2
        |--------------------------------------------------------------------------
        | Hanya faskes dalam kabupaten/kota user
        */

        if ($groupId === 2) {

            return $query
                ->where(
                    'kodeKabupaten',
                    $user->kodeKota
                )
                ->orderBy('namaFaskes')
                ->get();
        }

        return collect();
    }

    /**
     * ==========================================================
     * HEATMAP GROUP 3, 4, 5
     * ==========================================================
     *
     * Baris:
     *     Obat esensial
     *
     * Kolom:
     *     Bulan
     *
     * Faskes:
     *     Faskes milik user
     */
    public function getHeatmapPeriode(
        $user,
        int $bulanMulai,
        int $tahunMulai,
        int $bulanSampai,
        int $tahunSampai
    ): array {

        $kodeFaskes =
            $user->kodeFaskes;

        /*
        |--------------------------------------------------------------------------
        | DAFTAR PERIODE
        |--------------------------------------------------------------------------
        */

        $periods =
            $this->generatePeriods(
                $bulanMulai,
                $tahunMulai,
                $bulanSampai,
                $tahunSampai
            );

        /*
        |--------------------------------------------------------------------------
        | FASKES
        |--------------------------------------------------------------------------
        */

        $faskes =
            DB::table('master_faskes')
                ->where(
                    'kodeFaskes',
                    $kodeFaskes
                )
                ->first([
                    'kodeFaskes',
                    'namaFaskes'
                ]);

        /*
        |--------------------------------------------------------------------------
        | MASTER OBAT ESENSIAL
        |--------------------------------------------------------------------------
        |
        | Ambil berdasarkan seluruh tahun yang dibutuhkan.
        |
        */

        $tahunList =
            collect($periods)
                ->pluck('tahun')
                ->unique()
                ->values();

        $masterQuery =
            DB::table(
                'master_stokminimal_obat as s'
            )

            ->join(
                'master_obat as o',
                'o.kode_obat',
                '=',
                's.kode_obat'
            )

            ->where(
                's.kodeFaskes',
                $kodeFaskes
            )

            ->where(
                's.obat_esensial',
                'oe'
            )

            ->whereIn(
                's.tahun',
                $tahunList
            )

            ->select(
                's.kode_obat',
                'o.nama_obat',
                'o.satuan',
                'o.obat_napza',
                's.obat_esensial',
                's.obat_formularium_puskesmas',
                's.stok_minimal',
                's.stok_optimum',
                's.tahun'
            );

        $master =
            $masterQuery
                ->orderBy('o.nama_obat')
                ->get();

        /*
        |--------------------------------------------------------------------------
        | DATA STOK
        |--------------------------------------------------------------------------
        */

        $stock =
            $this->getStockForPeriods(
                $kodeFaskes,
                $periods
            );

        /*
        |--------------------------------------------------------------------------
        | FORMAT ROW
        |--------------------------------------------------------------------------
        */

        $rows = [];

        foreach ($master as $item) {

            $row = [
                'kode_obat' => $item->kode_obat,
                'nama_obat' => $item->nama_obat,
                'satuan' => $item->satuan,
                'obat_napza' => $item->obat_napza,
                'obat_esensial' => $item->obat_esensial,
                'cells' => []
            ];

            foreach ($periods as $period) {

                $key =
                    $period['tahun'] .
                    '-' .
                    str_pad(
                        $period['bulan'],
                        2,
                        '0',
                        STR_PAD_LEFT
                    );

                /*
                |--------------------------------------------------------------------------
                | MASTER STOCK
                |--------------------------------------------------------------------------
                */

                $minimum =
                    $master
                        ->where(
                            'kode_obat',
                            $item->kode_obat
                        )
                        ->where(
                            'tahun',
                            $period['tahun']
                        )
                        ->first();

                /*
                |--------------------------------------------------------------------------
                | STOCK
                |--------------------------------------------------------------------------
                */

                $stockItem =
                    $stock[$key][$item->kode_obat]
                    ?? null;

                $row['cells'][$key] =
                    $this->buildCell(
                        $stockItem,
                        $minimum
                    );
            }

            $rows[] = $row;
        }

        return [
            'faskes' => $faskes,
            'periods' => $periods,
            'rows' => $rows
        ];
    }

    /**
     * ==========================================================
     * HEATMAP GROUP 1, 2
     * ==========================================================
     *
     * Baris:
     *     Obat esensial
     *
     * Kolom:
     *     Faskes
     *
     * Filter:
     *     Satu bulan + satu tahun
     */
    public function getHeatmapPerFaskes(
        $user,
        int $bulan,
        int $tahun,
        ?string $kodeFaskes = null
    ): array {

        $groupId =
            (int) $user->groupid;

        /*
        |--------------------------------------------------------------------------
        | FASKES
        |--------------------------------------------------------------------------
        */

        $faskesQuery =
            DB::table('master_faskes')
                ->select(
                    'kodeFaskes',
                    'namaFaskes'
                );

        /*
        |--------------------------------------------------------------------------
        | GROUP 2
        |--------------------------------------------------------------------------
        */

        if ($groupId === 2) {

            $faskesQuery->where(
                'kodeKabupaten',
                $user->kodeKota
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER FASKES
        |--------------------------------------------------------------------------
        */

        if (
            $kodeFaskes !== null &&
            $kodeFaskes !== ''
        ) {

            $allowed =
                $faskesQuery
                    ->where(
                        'kodeFaskes',
                        $kodeFaskes
                    )
                    ->exists();

            if (!$allowed) {

                abort(
                    403,
                    'Faskes tidak diperbolehkan.'
                );
            }

            $faskesQuery =
                DB::table('master_faskes')
                    ->where(
                        'kodeFaskes',
                        $kodeFaskes
                    )
                    ->select(
                        'kodeFaskes',
                        'namaFaskes'
                    );
        }

        $faskes =
            $faskesQuery
                ->orderBy('namaFaskes')
                ->get();

        if ($faskes->isEmpty()) {

            return [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'faskes' => [],
                'rows' => []
            ];
        }

        $kodeFaskesList =
            $faskes
                ->pluck('kodeFaskes')
                ->values();

        /*
        |--------------------------------------------------------------------------
        | MASTER OBAT ESENSIAL
        |--------------------------------------------------------------------------
        |
        | UNION seluruh obat esensial yang tersedia
        | pada faskes yang ditampilkan.
        |
        */

        $master =
            DB::table(
                'master_stokminimal_obat as s'
            )

            ->join(
                'master_obat as o',
                'o.kode_obat',
                '=',
                's.kode_obat'
            )

            ->whereIn(
                's.kodeFaskes',
                $kodeFaskesList
            )

            ->where(
                's.tahun',
                $tahun
            )

            ->where(
                's.obat_esensial',
                'oe'
            )

            ->select(
                's.kode_obat',
                'o.nama_obat',
                'o.satuan',
                'o.obat_napza'
            )

            ->distinct()

            ->orderBy(
                'o.nama_obat'
            )

            ->get();

        /*
        |--------------------------------------------------------------------------
        | MASTER PER FASKES
        |--------------------------------------------------------------------------
        */

        $masterFaskes =
            DB::table(
                'master_stokminimal_obat as s'
            )

            ->join(
                'master_obat as o',
                'o.kode_obat',
                '=',
                's.kode_obat'
            )

            ->whereIn(
                's.kodeFaskes',
                $kodeFaskesList
            )

            ->where(
                's.tahun',
                $tahun
            )

            ->where(
                's.obat_esensial',
                'oe'
            )

            ->select(
                's.kode_obat',
                's.kodeFaskes',
                's.stok_minimal',
                's.stok_optimum',
                's.obat_esensial',
                's.obat_formularium_puskesmas'
            )

            ->get();

        /*
        |--------------------------------------------------------------------------
        | STOCK
        |--------------------------------------------------------------------------
        */

        $stock =
            DB::table(
                'new_lplpo_itemlist as i'
            )

            ->join(
                'new_lplpo_reports as r',
                'r.id',
                '=',
                'i.report_id'
            )

            ->whereIn(
                'r.kode_faskes',
                $kodeFaskesList
            )

            ->where(
                'r.bulan',
                $bulan
            )

            ->where(
                'r.tahun',
                $tahun
            )

            ->where(
                'r.report_status',
                'FINAL'
            )

            ->select(
                'r.kode_faskes',
                'i.kode_obat',

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.stok_akhir_program_pkd,
                            0
                        ) +
                        COALESCE(
                            i.stok_akhir_jkn,
                            0
                        )
                    ) as stok_akhir'
                )
            )

            ->groupBy(
                'r.kode_faskes',
                'i.kode_obat'
            )

            ->get();

        /*
        |--------------------------------------------------------------------------
        | INDEX STOCK
        |--------------------------------------------------------------------------
        */

        $stockIndex = [];

        foreach ($stock as $item) {

            $stockIndex[
                $item->kode_faskes
            ][
                $item->kode_obat
            ] =
                (int) $item->stok_akhir;
        }

        /*
        |--------------------------------------------------------------------------
        | INDEX MASTER
        |--------------------------------------------------------------------------
        */

        $masterIndex = [];

        foreach ($masterFaskes as $item) {

            $masterIndex[
                $item->kodeFaskes
            ][
                $item->kode_obat
            ] = $item;
        }

        /*
        |--------------------------------------------------------------------------
        | ROWS
        |--------------------------------------------------------------------------
        */

        $rows = [];

        foreach ($master as $item) {

            $row = [
                'kode_obat' => $item->kode_obat,
                'nama_obat' => $item->nama_obat,
                'satuan' => $item->satuan,
                'obat_napza' => $item->obat_napza,
                'obat_esensial' => 'oe',
                'cells' => []
            ];

            foreach ($faskes as $f) {

                $masterItem =
                    $masterIndex[
                        $f->kodeFaskes
                    ][
                        $item->kode_obat
                    ] ?? null;

                $stockValue =
                    $stockIndex[
                        $f->kodeFaskes
                    ][
                        $item->kode_obat
                    ] ?? null;

                $row['cells'][
                    $f->kodeFaskes
                ] =
                    $this->buildCell(
                        $stockValue !== null
                            ? (object) [
                                'stok_akhir' => $stockValue
                            ]
                            : null,
                        $masterItem
                    );
            }

            $rows[] = $row;
        }

        return [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'faskes' => $faskes,
            'rows' => $rows
        ];
    }

    /**
     * ==========================================================
     * STOCK PER PERIOD
     * ==========================================================
     */
    protected function getStockForPeriods(
        string $kodeFaskes,
        array $periods
    ): array {

        if (empty($periods)) {
            return [];
        }

        $conditions = [];

        foreach ($periods as $period) {

            $conditions[] = [
                $period['tahun'],
                $period['bulan']
            ];
        }

        $query =
            DB::table(
                'new_lplpo_itemlist as i'
            )

            ->join(
                'new_lplpo_reports as r',
                'r.id',
                '=',
                'i.report_id'
            )

            ->where(
                'r.kode_faskes',
                $kodeFaskes
            )

            ->where(
                'r.report_status',
                'FINAL'
            )

            ->where(function ($q) use ($conditions) {

                foreach ($conditions as $index => $condition) {

                    [$tahun, $bulan] =
                        $condition;

                    if ($index === 0) {

                        $q->where(function ($sub) use (
                            $tahun,
                            $bulan
                        ) {

                            $sub
                                ->where(
                                    'r.tahun',
                                    $tahun
                                )
                                ->where(
                                    'r.bulan',
                                    $bulan
                                );
                        });

                    } else {

                        $q->orWhere(function ($sub) use (
                            $tahun,
                            $bulan
                        ) {

                            $sub
                                ->where(
                                    'r.tahun',
                                    $tahun
                                )
                                ->where(
                                    'r.bulan',
                                    $bulan
                                );
                        });
                    }
                }
            })

            ->select(
                'r.tahun',
                'r.bulan',
                'i.kode_obat',

                DB::raw(
                    'SUM(
                        COALESCE(
                            i.stok_akhir_program_pkd,
                            0
                        ) +
                        COALESCE(
                            i.stok_akhir_jkn,
                            0
                        )
                    ) as stok_akhir'
                )
            )

            ->groupBy(
                'r.tahun',
                'r.bulan',
                'i.kode_obat'
            )

            ->get();

        /*
        |--------------------------------------------------------------------------
        | INDEX
        |--------------------------------------------------------------------------
        */

        $result = [];

        foreach ($query as $item) {

            $key =
                $item->tahun .
                '-' .
                str_pad(
                    $item->bulan,
                    2,
                    '0',
                    STR_PAD_LEFT
                );

            $result[$key][
                $item->kode_obat
            ] = $item;
        }

        return $result;
    }

    /**
     * ==========================================================
     * BUILD CELL
     * ==========================================================
     */
    protected function buildCell(
        $stock,
        $minimum
    ): array {

        /*
        |--------------------------------------------------------------------------
        | TIDAK ADA MASTER MINIMUM
        |--------------------------------------------------------------------------
        */

        if (!$minimum) {

            return [
                'available' => false,
                'stok_akhir' => $stock
                    ? (int) $stock->stok_akhir
                    : null,
                'stok_minimal' => null,
                'stok_optimum' => null,
                'formularium' => null,
                'percentage' => null,
                'level' => 'unknown'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | TIDAK ADA DATA STOCK
        |--------------------------------------------------------------------------
        */

        if (!$stock) {

            return [
                'available' => false,
                'stok_akhir' => null,
                'stok_minimal' =>
                    (int) $minimum->stok_minimal,
                'stok_optimum' =>
                    (int) $minimum->stok_optimum,
                'formularium' =>
                    $minimum->obat_formularium_puskesmas,
                'percentage' => null,
                'level' => 'nodata'
            ];
        }

        $stokAkhir =
            (int) $stock->stok_akhir;

        $stokMinimal =
            (int) $minimum->stok_minimal;

        /*
        |--------------------------------------------------------------------------
        | STOK MINIMAL = 0
        |--------------------------------------------------------------------------
        */

        if ($stokMinimal <= 0) {

            return [
                'available' => true,
                'stok_akhir' => $stokAkhir,
                'stok_minimal' => $stokMinimal,
                'stok_optimum' =>
                    (int) $minimum->stok_optimum,
                'formularium' =>
                    $minimum->obat_formularium_puskesmas,
                'percentage' => null,
                'level' => 'normal'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | PERSENTASE
        |--------------------------------------------------------------------------
        |
        | (stok akhir - stok minimal)
        | --------------------------------
        | stok minimal
        |
        */

        $percentage =
            (
                (
                    $stokAkhir -
                    $stokMinimal
                ) /
                $stokMinimal
            ) * 100;

        /*
        |--------------------------------------------------------------------------
        | LEVEL
        |--------------------------------------------------------------------------
        */

        if ($percentage < 25) {

            $level = 'danger';

        } elseif ($percentage < 35) {

            $level = 'warning';

        } elseif ($percentage <= 50) {

            $level = 'yellow';

        } else {

            $level = 'success';
        }

        return [
            'available' => true,
            'stok_akhir' => $stokAkhir,
            'stok_minimal' => $stokMinimal,
            'stok_optimum' =>
                (int) $minimum->stok_optimum,
            'formularium' =>
                $minimum->obat_formularium_puskesmas,
            'percentage' =>
                round(
                    $percentage,
                    2
                ),
            'level' => $level
        ];
    }

    /**
     * ==========================================================
     * GENERATE PERIOD
     * ==========================================================
     */
    protected function generatePeriods(
        int $bulanMulai,
        int $tahunMulai,
        int $bulanSampai,
        int $tahunSampai
    ): array {

        $result = [];

        $current =
            \Carbon\Carbon::create(
                $tahunMulai,
                $bulanMulai,
                1
            );

        $end =
            \Carbon\Carbon::create(
                $tahunSampai,
                $bulanSampai,
                1
            );

        while ($current->lte($end)) {

            $result[] = [
                'bulan' => $current->month,
                'tahun' => $current->year,
                'label' =>
                    $current->translatedFormat('M Y')
            ];

            $current->addMonth();
        }

        return $result;
    }
}
