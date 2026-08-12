<?php

namespace App\Services\NewLplpo;

use App\Models\NewLplpo\Item;
use App\Models\NewLplpo\Report;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\NewLplpo\StokMinimalObat;
use App\Models\NewLplpo\MasterDataObat;

class ItemService
{


public function find($id)
{
    return Item::with('program')
        ->findOrFail($id);
}
/*
    |--------------------------------------------------------------------------
    | DEFAULT VALUE
    |--------------------------------------------------------------------------
    |
    | Digunakan ketika user memilih obat untuk dimasukkan
    | ke LPLPO baru.
    |
    */

    public function defaultValue($reportId, $kodeObat, $programId)
{
    $report = Report::findOrFail($reportId);

    /*
    |--------------------------------------------------------------------------
    | PERIODE SEBELUMNYA
    |--------------------------------------------------------------------------
    */

    $periode = Carbon::create(
        $report->tahun,
        $report->bulan,
        1
    )->subMonth();


    /*
    |--------------------------------------------------------------------------
    | CARI ITEM BULAN SEBELUMNYA
    |--------------------------------------------------------------------------
    */

    $item = Item::query()

        ->select('new_lplpo_itemlist.*')

        ->join(
            'new_lplpo_reports',
            'new_lplpo_reports.id',
            '=',
            'new_lplpo_itemlist.report_id'
        )

        ->where(
            'new_lplpo_reports.kode_faskes',
            $report->kode_faskes
        )

        ->where(
            'new_lplpo_reports.report_status',
            'FINAL'
        )

        ->where(
            'new_lplpo_itemlist.kode_obat',
            $kodeObat
        )

        ->where(
            'new_lplpo_itemlist.program_id',
            $programId
        )

        ->where(
            'new_lplpo_reports.id',
            '<>',
            $reportId
        )

        ->where(function ($q) use ($periode) {

            $q->where(
                'new_lplpo_reports.tahun',
                '<',
                $periode->year
            )

            ->orWhere(function ($q) use ($periode) {

                $q->where(
                    'new_lplpo_reports.tahun',
                    $periode->year
                )

                ->where(
                    'new_lplpo_reports.bulan',
                    '<=',
                    $periode->month
                );

            });

        })

        ->orderByDesc('new_lplpo_reports.tahun')
        ->orderByDesc('new_lplpo_reports.bulan')
        ->orderByDesc('new_lplpo_reports.id')

        ->first();


    /*
    |--------------------------------------------------------------------------
    | MASTER STOK MINIMAL
    |--------------------------------------------------------------------------
    */

    $stokMinimal = StokMinimalObat::query()

        ->where(
            'kode_obat',
            $kodeObat
        )

        ->where(
            'kodeFaskes',
            $report->kode_faskes
        )

        ->where(
            'tahun',
            $report->tahun
        )

        ->first();


    return [

        /*
        |--------------------------------------------------------------------------
        | BULAN SEBELUMNYA
        |--------------------------------------------------------------------------
        */

        'stok_awal_program_pkd' =>
            $item->stok_akhir_program_pkd ?? 0,

        'stok_awal_jkn' =>
            $item->stok_akhir_jkn ?? 0,

        'penerimaan_program_pkd' =>
            $item->pemberian_program_pkd ?? 0,

        'penerimaan_jkn' =>
            $item->pemberian_jkn ?? 0,


        /*
        |--------------------------------------------------------------------------
        | STOK MINIMAL
        |--------------------------------------------------------------------------
        */

        'stok_minimum' =>
            $stokMinimal->stok_minimal ?? 0,

        'stok_optimum' =>
            $stokMinimal->stok_optimum ?? 0,


        /*
        |--------------------------------------------------------------------------
        | OBAT ESENSIAL
        |--------------------------------------------------------------------------
        */

        'obat_esensial' =>
            $stokMinimal->obat_esensial ?? 'noe',

        'obat_formularium_puskesmas' =>
            $stokMinimal->obat_formularium_puskesmas ?? 'false',


        /*
        |--------------------------------------------------------------------------
        | NAPZA
        |--------------------------------------------------------------------------
        */

        'obat_napza' =>
            optional(
                MasterDataObat::where(
                    'kode_obat',
                    $kodeObat
                )->first()
            )->obat_napza ?? 'tidak',

    ];
}

    /*
    |--------------------------------------------------------------------------
    | LIST ITEM
    |--------------------------------------------------------------------------
    */

    public function listByReport($reportId)
{
    $report = Report::findOrFail($reportId);

    return Item::query()

        ->with([
            'program',
            'obat',
        ])

        ->where('report_id', $reportId)

        ->orderBy('program_id')
        ->orderBy('nama_obat')

        ->get()

        ->map(function ($item) use ($report) {

            /*
            |--------------------------------------------------------------------------
            | MASTER OBAT
            |--------------------------------------------------------------------------
            */

            $item->obat_napza =
                optional($item->obat)->obat_napza ?? 'tidak';


            /*
            |--------------------------------------------------------------------------
            | MASTER STOK MINIMAL
            |--------------------------------------------------------------------------
            */

            $stokMinimal = StokMinimalObat::query()

                ->where('kode_obat', $item->kode_obat)

                ->where('kodeFaskes', $report->kode_faskes)

                ->where('tahun', $report->tahun)

                ->first();


            $item->obat_esensial =
                $stokMinimal->obat_esensial ?? 'noe';


            $item->obat_formularium_puskesmas =
                $stokMinimal->obat_formularium_puskesmas ?? 'false';


            return $item;

        });
}


    /*
    |--------------------------------------------------------------------------
    | CREATE ITEM
    |--------------------------------------------------------------------------
    */

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            return Item::create([

                'report_id' =>
                    $data['report_id'],

                'program_id' =>
                    $data['program_id'],

                'program_name' =>
                    $data['program_name'] ?? null,

                'kode_obat' =>
                    $data['kode_obat'],

                'nama_obat' =>
                    $data['nama_obat'],

                'satuan' =>
                    $data['satuan'],

                'stok_awal_progam_pkd' =>
                    $data['stok_awal_progam_pkd'] ?? 0,

                'stok_awal_jkn' =>
                    $data['stok_awal_jkn'] ?? 0,

                'penerimaan_program_pkd' =>
                    $data['penerimaan_program_pkd'] ?? 0,

                'penerimaan_jkn' =>
                    $data['penerimaan_jkn'] ?? 0,

                'persediaan_program_pkd' =>
                    $data['persediaan_program_pkd'] ?? 0,

                'persediaan_jkn' =>
                    $data['persediaan_jkn'] ?? 0,

                'pemakaian_program_pkd' =>
                    $data['pemakaian_program_pkd'] ?? 0,

                'pemakaian_jkn' =>
                    $data['pemakaian_jkn'] ?? 0,

                'item_expired_pkd' =>
                    $data['item_expired_pkd'] ?? 0,

                'item_expired_jkn' =>
                    $data['item_expired_jkn'] ?? 0,

                'stok_akhir_program_pkd' =>
                    $data['stok_akhir_program_pkd'] ?? 0,

                'stok_akhir_jkn' =>
                    $data['stok_akhir_jkn'] ?? 0,

                'stok_minimum' =>
                    $data['stok_minimum'] ?? 0,

                'stok_optimum' =>
                    $data['stok_optimum'] ?? 0,

                'permintaan' =>
                    $data['permintaan'] ?? 0,

                'pemberian_program_pkd' =>
                    $data['pemberian_program_pkd'] ?? 0,

                'pemberian_jkn' =>
                    $data['pemberian_jkn'] ?? 0,

            ]);

        });
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE ITEM
    |--------------------------------------------------------------------------
    */

   public function update($id, array $data)
{
    $item = Item::findOrFail($id);

    $item->update([

        'program_id' =>
            $data['program_id'] ?? $item->program_id,

        'kode_obat' =>
            $data['kode_obat'] ?? $item->kode_obat,

        'nama_obat' =>
            $data['nama_obat'] ?? $item->nama_obat,

        'satuan' =>
            $data['satuan'] ?? $item->satuan,

        'stok_awal_progam_pkd' =>
            $data['stok_awal_progam_pkd'] ?? $item->stok_awal_progam_pkd,

        'stok_awal_jkn' =>
            $data['stok_awal_jkn'] ?? $item->stok_awal_jkn,

        'penerimaan_program_pkd' =>
            $data['penerimaan_program_pkd'] ?? $item->penerimaan_program_pkd,

        'penerimaan_jkn' =>
            $data['penerimaan_jkn'] ?? $item->penerimaan_jkn,

        'persediaan_program_pkd' =>
            $data['persediaan_program_pkd'] ?? $item->persediaan_program_pkd,

        'persediaan_jkn' =>
            $data['persediaan_jkn'] ?? $item->persediaan_jkn,

        'pemakaian_program_pkd' =>
            $data['pemakaian_program_pkd'] ?? $item->pemakaian_program_pkd,

        'pemakaian_jkn' =>
            $data['pemakaian_jkn'] ?? $item->pemakaian_jkn,

        'item_expired_pkd' =>
            $data['item_expired_pkd'] ?? $item->item_expired_pkd,

        'item_expired_jkn' =>
            $data['item_expired_jkn'] ?? $item->item_expired_jkn,

        'stok_akhir_program_pkd' =>
            $data['stok_akhir_program_pkd'] ?? $item->stok_akhir_program_pkd,

        'stok_akhir_jkn' =>
            $data['stok_akhir_jkn'] ?? $item->stok_akhir_jkn,

        'stok_minimum' =>
            $data['stok_minimum'] ?? $item->stok_minimum,

        'stok_optimum' =>
            $data['stok_optimum'] ?? $item->stok_optimum,

        'permintaan' =>
            $data['permintaan'] ?? $item->permintaan,

        'pemberian_program_pkd' =>
            $data['pemberian_program_pkd'] ?? $item->pemberian_program_pkd,

        'pemberian_jkn' =>
            $data['pemberian_jkn'] ?? $item->pemberian_jkn,

    ]);

    return $item->fresh();
}

    /*
    |--------------------------------------------------------------------------
    | DELETE ITEM
    |--------------------------------------------------------------------------
    */

    public function delete(Item $item)
    {
        return $item->delete();
    }


    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    public function datatable($reportId)
    {
        return Item::with('program')

            ->where(
                'report_id',
                $reportId
            )

            ->orderBy(
                'program_id'
            )

            ->orderBy(
                'nama_obat'
            )

            ->get();
    }



    public function copyPreviousMonthItems($reportId)
{
    return DB::transaction(function () use ($reportId) {

        $report = Report::findOrFail($reportId);

        /*
        |--------------------------------------------------------------------------
        | PERIODE SEBELUMNYA
        |--------------------------------------------------------------------------
        */

        $periode = Carbon::create(
            $report->tahun,
            $report->bulan,
            1
        )->subMonth();


        /*
        |--------------------------------------------------------------------------
        | CARI REPORT FINAL BULAN SEBELUMNYA
        |--------------------------------------------------------------------------
        */

        $previousReports = Report::query()

            ->where(
                'kode_faskes',
                $report->kode_faskes
            )

            ->where(
                'bulan',
                $periode->month
            )

            ->where(
                'tahun',
                $periode->year
            )

            ->where(
                'report_status',
                'FINAL'
            )

            ->pluck('id');


        if ($previousReports->isEmpty()) {
            return collect();
        }


        /*
        |--------------------------------------------------------------------------
        | JANGAN COPY ULANG JIKA SUDAH ADA ITEM
        |--------------------------------------------------------------------------
        */

        $existingItems = Item::query()

            ->where(
                'report_id',
                $report->id
            )

            ->get([
                'program_id',
                'kode_obat'
            ])

            ->map(function ($item) {

                return $item->program_id . '|' . $item->kode_obat;

            })

            ->flip();


        /*
        |--------------------------------------------------------------------------
        | AMBIL ITEM BULAN SEBELUMNYA
        |--------------------------------------------------------------------------
        */

        $previousItems = Item::query()

            ->whereIn(
                'report_id',
                $previousReports
            )

            ->with([
                'program',
                'obat'
            ])

            ->get();


        $created = collect();


        foreach ($previousItems as $oldItem) {

            $key =
                $oldItem->program_id .
                '|' .
                $oldItem->kode_obat;


            /*
            |--------------------------------------------------------------------------
            | SKIP JIKA SUDAH ADA
            |--------------------------------------------------------------------------
            */

            if ($existingItems->has($key)) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | MASTER STOK MINIMAL
            |--------------------------------------------------------------------------
            */

            $stokMinimal = StokMinimalObat::query()

                ->where(
                    'kode_obat',
                    $oldItem->kode_obat
                )

                ->where(
                    'kodeFaskes',
                    $report->kode_faskes
                )

                ->where(
                    'tahun',
                    $report->tahun
                )

                ->first();


            /*
            |--------------------------------------------------------------------------
            | STOK AWAL
            |--------------------------------------------------------------------------
            */

            $stokAwalPkd =
                (int) $oldItem->stok_akhir_program_pkd;

            $stokAwalJkn =
                (int) $oldItem->stok_akhir_jkn;


            /*
            |--------------------------------------------------------------------------
            | PENERIMAAN
            |--------------------------------------------------------------------------
            */

            $penerimaanPkd =
                (int) $oldItem->pemberian_program_pkd;

            $penerimaanJkn =
                (int) $oldItem->pemberian_jkn;


            /*
            |--------------------------------------------------------------------------
            | PERSEDIAAN
            |--------------------------------------------------------------------------
            */

            $persediaanPkd =
                $stokAwalPkd +
                $penerimaanPkd;

            $persediaanJkn =
                $stokAwalJkn +
                $penerimaanJkn;


            /*
            |--------------------------------------------------------------------------
            | ITEM BARU
            |--------------------------------------------------------------------------
            */

            $newItem = Item::create([

                'report_id' =>
                    $report->id,

                'program_id' =>
                    $oldItem->program_id,

                'program_name' =>
                    $oldItem->program_name,

                'kode_obat' =>
                    $oldItem->kode_obat,

                'nama_obat' =>
                    $oldItem->nama_obat,

                'satuan' =>
                    $oldItem->satuan,


                'stok_awal_progam_pkd' =>
                    $stokAwalPkd,

                'stok_awal_jkn' =>
                    $stokAwalJkn,


                'penerimaan_program_pkd' =>
                    $penerimaanPkd,

                'penerimaan_jkn' =>
                    $penerimaanJkn,


                'persediaan_program_pkd' =>
                    $persediaanPkd,

                'persediaan_jkn' =>
                    $persediaanJkn,


                /*
                |--------------------------------------------------------------------------
                | PEMAKAIAN BARU
                |--------------------------------------------------------------------------
                */

                'pemakaian_program_pkd' => 0,

                'pemakaian_jkn' => 0,


                /*
                |--------------------------------------------------------------------------
                | EXPIRED
                |--------------------------------------------------------------------------
                */

                'item_expired_pkd' => 0,

                'item_expired_jkn' => 0,


                /*
                |--------------------------------------------------------------------------
                | STOK AKHIR
                |--------------------------------------------------------------------------
                */

                'stok_akhir_program_pkd' =>
                    $persediaanPkd,

                'stok_akhir_jkn' =>
                    $persediaanJkn,


                /*
                |--------------------------------------------------------------------------
                | STOK MINIMUM / OPTIMUM
                |--------------------------------------------------------------------------
                */

                'stok_minimum' =>
                    $stokMinimal->stok_minimal ?? 0,

                'stok_optimum' =>
                    $stokMinimal->stok_optimum ?? 0,


                /*
                |--------------------------------------------------------------------------
                | PERMINTAAN
                |--------------------------------------------------------------------------
                */

                'permintaan' => 0,


                /*
                |--------------------------------------------------------------------------
                | PEMBERIAN BULAN SEKARANG
                |--------------------------------------------------------------------------
                */

                'pemberian_program_pkd' => 0,

                'pemberian_jkn' => 0,

            ]);


            $created->push($newItem);

        }


        return $created;

    });
}
}
