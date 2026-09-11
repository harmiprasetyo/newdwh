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
        |--------------------------------------------------------------------------
        */
        if ($groupId === 1) {
            return $query
                ->orderBy('namaFaskes')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | GROUP 2 / DINKES
        |--------------------------------------------------------------------------
        | Hanya faskes dalam kabupaten/kota user
        |--------------------------------------------------------------------------
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
     * GROUP 3, 4, 5
     * ==========================================================
     *
     * Monitoring:
     *
     * ROW    = OBAT
     * COLUMN = PERIODE
     */
    public function getHeatmapPeriode(
        $user,
        int $bulanMulai,
        int $tahunMulai,
        int $bulanSampai,
        int $tahunSampai
    ): array {

        $kodeFaskes = $user->kodeFaskes;

        $periods = $this->generatePeriods(
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
        $faskes = DB::table('master_faskes')
            ->where(
                'kodeFaskes',
                $kodeFaskes
            )
            ->first([
                'kodeFaskes',
                'namaFaskes'
            ]);

        if (!$faskes) {
            return [
                'faskes' => null,
                'periods' => $periods,
                'rows' => []
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | TAHUN
        |--------------------------------------------------------------------------
        */
        $tahunList = collect($periods)
            ->pluck('tahun')
            ->unique()
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | MASTER OBAT
        |--------------------------------------------------------------------------
        */
        $master = DB::table(
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
            )
            ->orderBy('o.nama_obat')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | INDEX MASTER
        |--------------------------------------------------------------------------
        */
        $masterIndex = [];

        foreach ($master as $item) {
            $masterIndex[
                $item->tahun
            ][
                $item->kode_obat
            ] = $item;
        }

        /*
        |--------------------------------------------------------------------------
        | STOCK
        |--------------------------------------------------------------------------
        */
        $stock = $this->getStockForPeriods(
            $kodeFaskes,
            $periods
        );

        /*
        |--------------------------------------------------------------------------
        | ROW
        |--------------------------------------------------------------------------
        */
        $rows = [];

        /*
        |--------------------------------------------------------------------------
        | UNIQUE OBAT
        |--------------------------------------------------------------------------
        */
        $obatList = $master
            ->groupBy('kode_obat')
            ->map(function ($items) {
                return $items->first();
            })
            ->values();

        foreach ($obatList as $item) {

            $row = [
                'kode_obat' => $item->kode_obat,
                'nama_obat' => $item->nama_obat,
                'satuan' => $item->satuan,
                'obat_napza' => $item->obat_napza,
                'obat_esensial' => 'oe',
                'cells' => []
            ];

            foreach ($periods as $period) {

                $key = $this->periodKey(
                    $period['tahun'],
                    $period['bulan']
                );

                $minimum =
                    $masterIndex[
                        $period['tahun']
                    ][
                        $item->kode_obat
                    ] ?? null;

                $stockItem =
                    $stock[
                        $key
                    ][
                        $item->kode_obat
                    ] ?? null;

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
     * GROUP 1
     * ==========================================================
     *
     * ROW    = OBAT
     * COLUMN = FASKES
     */
    public function getHeatmapPerObat(
        $user,
        int $bulan,
        int $tahun,
        ?string $kodeFaskes = null
    ): array {

        $faskes = $this->getFilteredFaskes(
            $user,
            $kodeFaskes
        );

        if ($faskes->isEmpty()) {
            return [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'mode' => 'obat',
                'faskes' => [],
                'rows' => []
            ];
        }

        $kodeFaskesList = $faskes
            ->pluck('kodeFaskes')
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | MASTER OBAT
        |--------------------------------------------------------------------------
        */
        $master = DB::table(
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
            ->orderBy('o.nama_obat')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | MASTER PER FASKES
        |--------------------------------------------------------------------------
        */
        $masterFaskes = DB::table(
                'master_stokminimal_obat as s'
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
        $stock = $this->getStockForFaskes(
            $kodeFaskesList,
            $bulan,
            $tahun
        );

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
        | INDEX STOCK
        |--------------------------------------------------------------------------
        */
        $stockIndex = [];

        foreach ($stock as $item) {
            $stockIndex[
                $item->kode_faskes
            ][
                $item->kode_obat
            ] = (int) $item->stok_akhir;
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

                $stockObject = null;

                if ($stockValue !== null) {
                    $stockObject = (object) [
                        'stok_akhir' => $stockValue
                    ];
                }

                $row['cells'][
                    $f->kodeFaskes
                ] = $this->buildCell(
                    $stockObject,
                    $masterItem
                );
            }

            $rows[] = $row;
        }

        return [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'mode' => 'obat',
            'faskes' => $faskes,
            'rows' => $rows
        ];
    }

    /**
     * ==========================================================
     * GROUP 2 / DINKES
     * ==========================================================
     *
     * ROW    = KATEGORI OBAT
     * COLUMN = FASKES
     *
     * Semua obat dalam kategori dijumlahkan.
     */
    public function getHeatmapPerKategori(
        $user,
        int $bulan,
        int $tahun,
        ?string $kodeFaskes = null
    ): array {

        $faskes = $this->getFilteredFaskes(
            $user,
            $kodeFaskes
        );

        if ($faskes->isEmpty()) {
            return [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'mode' => 'kategori',
                'faskes' => [],
                'rows' => []
            ];
        }

        $kodeFaskesList = $faskes
            ->pluck('kodeFaskes')
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | MASTER KATEGORI
        |--------------------------------------------------------------------------
        */
        $master = DB::table(
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
            ->whereNotNull(
                's.kategori'
            )
            ->where(
                's.kategori',
                '<>',
                ''
            )
            ->select(
                's.kategori'
            )
            ->distinct()
            ->orderBy(
                's.kategori'
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | MASTER PER KATEGORI + FASKES
        |--------------------------------------------------------------------------
        */
        $masterFaskes = DB::table(
                'master_stokminimal_obat as s'
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
            ->whereNotNull(
                's.kategori'
            )
            ->where(
                's.kategori',
                '<>',
                ''
            )
            ->select(
                's.kodeFaskes',
                's.kategori',
                DB::raw(
                    'SUM(COALESCE(s.stok_minimal, 0)) as stok_minimal'
                ),
                DB::raw(
                    'SUM(COALESCE(s.stok_optimum, 0)) as stok_optimum'
                ),
                DB::raw(
                    'MAX(s.obat_formularium_puskesmas) as obat_formularium_puskesmas'
                )
            )
            ->groupBy(
                's.kodeFaskes',
                's.kategori'
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | STOCK PER OBAT
        |--------------------------------------------------------------------------
        */
        $stock = $this->getStockForFaskes(
            $kodeFaskesList,
            $bulan,
            $tahun
        );

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
                $item->kategori
            ] = $item;
        }

        /*
        |--------------------------------------------------------------------------
        | STOCK MASTER -> KATEGORI
        |--------------------------------------------------------------------------
        |
        | Karena stock masih disimpan per kode obat,
        | kita mapping kode_obat -> kategori_obat.
        |
        */
        $obatKategori = DB::table(
                'master_stokminimal_obat'
            )
            ->whereIn(
                'kodeFaskes',
                $kodeFaskesList
            )
            ->where(
                'tahun',
                $tahun
            )
            ->where(
                'obat_esensial',
                'oe'
            )
            ->whereNotNull(
                'kategori'
            )
            ->where(
                'kategori',
                '<>',
                ''
            )
            ->select(
                'kodeFaskes',
                'kode_obat',
                'kategori'
            )
            ->get();

        $kategoriMap = [];

        foreach ($obatKategori as $item) {

            $kategoriMap[
                $item->kodeFaskes
            ][
                $item->kode_obat
            ] = $item->kategori;
        }

        /*
        |--------------------------------------------------------------------------
        | AGREGASI STOCK PER KATEGORI
        |--------------------------------------------------------------------------
        */
        $stockCategoryIndex = [];

        foreach ($stock as $item) {

            $kategori =
                $kategoriMap[
                    $item->kode_faskes
                ][
                    $item->kode_obat
                ] ?? null;

            if (!$kategori) {
                continue;
            }

            if (!isset(
                $stockCategoryIndex[
                    $item->kode_faskes
                ][
                    $kategori
                ]
            )) {

                $stockCategoryIndex[
                    $item->kode_faskes
                ][
                    $kategori
                ] = 0;
            }

            $stockCategoryIndex[
                $item->kode_faskes
            ][
                $kategori
            ] += (int) $item->stok_akhir;
        }

        /*
        |--------------------------------------------------------------------------
        | ROWS
        |--------------------------------------------------------------------------
        */
        $rows = [];

        foreach ($master as $category) {

            $kategori = $category->kategori;

            $row = [
                'kategori' => $kategori,
                'nama_obat' => $kategori,
                'cells' => []
            ];

            foreach ($faskes as $f) {

                $masterItem =
                    $masterIndex[
                        $f->kodeFaskes
                    ][
                        $kategori
                    ] ?? null;

                $stockValue =
                    $stockCategoryIndex[
                        $f->kodeFaskes
                    ][
                        $kategori
                    ] ?? null;

                $stockObject = null;

                if ($stockValue !== null) {

                    $stockObject = (object) [
                        'stok_akhir' => $stockValue
                    ];
                }

                $row['cells'][
                    $f->kodeFaskes
                ] = $this->buildCell(
                    $stockObject,
                    $masterItem
                );
            }

            $rows[] = $row;
        }

        return [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'mode' => 'kategori',
            'faskes' => $faskes,
            'rows' => $rows
        ];
    }

    /**
     * ==========================================================
     * FILTER FASKES
     * ==========================================================
     */
    protected function getFilteredFaskes(
        $user,
        ?string $kodeFaskes
    ): Collection {

        $groupId = (int) $user->groupid;

        $query = DB::table('master_faskes')
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

            $query->where(
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

            /*
             * Validasi bahwa faskes memang boleh dilihat user.
             */
            if (
                !(clone $query)
                    ->where(
                        'kodeFaskes',
                        $kodeFaskes
                    )
                    ->exists()
            ) {

                abort(
                    403,
                    'Faskes tidak diperbolehkan.'
                );
            }

            $query->where(
                'kodeFaskes',
                $kodeFaskes
            );
        }

        return $query
            ->orderBy('namaFaskes')
            ->get();
    }

    /**
     * ==========================================================
     * STOCK PER FASKES
     * ==========================================================
     */
    protected function getStockForFaskes(
        array $kodeFaskesList,
        int $bulan,
        int $tahun
    ): Collection {

        return DB::table(
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
                        COALESCE(i.stok_akhir_program_pkd, 0)
                        +
                        COALESCE(i.stok_akhir_jkn, 0)
                    ) AS stok_akhir'
                )
            )
            ->groupBy(
                'r.kode_faskes',
                'i.kode_obat'
            )
            ->get();
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

        $query = DB::table(
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
            ->where(function ($q) use ($periods) {

                foreach ($periods as $index => $period) {

                    $callback = function ($sub) use ($period) {

                        $sub->where(
                            'r.tahun',
                            $period['tahun']
                        )->where(
                            'r.bulan',
                            $period['bulan']
                        );
                    };

                    if ($index === 0) {
                        $q->where(
                            $callback
                        );
                    } else {
                        $q->orWhere(
                            $callback
                        );
                    }
                }
            })
            ->select(
                'r.tahun',
                'r.bulan',
                'i.kode_obat',
                DB::raw(
                    'SUM(
                        COALESCE(i.stok_akhir_program_pkd, 0)
                        +
                        COALESCE(i.stok_akhir_jkn, 0)
                    ) AS stok_akhir'
                )
            )
            ->groupBy(
                'r.tahun',
                'r.bulan',
                'i.kode_obat'
            )
            ->get();

        $result = [];

        foreach ($query as $item) {

            $key = $this->periodKey(
                $item->tahun,
                $item->bulan
            );

            $result[
                $key
            ][
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
        | MASTER TIDAK ADA
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
        | STOCK TIDAK ADA
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

        $stokOptimum =
            (int) $minimum->stok_optimum;

        /*
        |--------------------------------------------------------------------------
        | MINIMAL = 0
        |--------------------------------------------------------------------------
        */
        if ($stokMinimal <= 0) {

            return [
                'available' => true,
                'stok_akhir' => $stokAkhir,
                'stok_minimal' => $stokMinimal,
                'stok_optimum' => $stokOptimum,
                'formularium' =>
                    $minimum->obat_formularium_puskesmas,
                'percentage' => null,
                'level' => 'normal'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | PERCENTAGE
        |--------------------------------------------------------------------------
        */
        $percentage =
            ($stokAkhir / $stokMinimal) * 100;

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
            'stok_optimum' => $stokOptimum,
            'formularium' =>
                $minimum->obat_formularium_puskesmas,
            'percentage' =>
                round($percentage, 2),
            'level' => $level
        ];
    }

    /**
     * ==========================================================
     * PERIOD KEY
     * ==========================================================
     */
    protected function periodKey(
        int $tahun,
        int $bulan
    ): string {

        return $tahun .
            '-' .
            str_pad(
                $bulan,
                2,
                '0',
                STR_PAD_LEFT
            );
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

        $current = \Carbon\Carbon::create(
            $tahunMulai,
            $bulanMulai,
            1
        );

        $end = \Carbon\Carbon::create(
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