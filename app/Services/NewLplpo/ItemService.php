<?php

namespace App\Services\NewLplpo;

use App\Models\NewLplpo\Item;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\NewLplpo\Report;


class ItemService
{



public function defaultValue($reportId, $kodeObat, $programId)
{
    $report = Report::findOrFail($reportId);

    // Periode acuan = bulan sebelum laporan yang sedang dibuat
    $periode = Carbon::create(
        $report->tahun,
        $report->bulan,
        1
    )->subMonth();

    $item = Item::select('new_lplpo_itemlist.*')
        ->join(
            'new_lplpo_reports',
            'new_lplpo_reports.id',
            '=',
            'new_lplpo_itemlist.report_id'
        )
        ->where('new_lplpo_reports.kode_faskes', $report->kode_faskes)
        ->where('new_lplpo_reports.report_status', 'FINAL')
        ->where('new_lplpo_itemlist.kode_obat', $kodeObat)
        ->where('new_lplpo_itemlist.program_id', $programId)
        ->where('new_lplpo_reports.id', '<>', $reportId)

        ->where(function ($q) use ($periode) {

            $q->where('new_lplpo_reports.tahun', '<', $periode->year)

              ->orWhere(function ($q) use ($periode) {

                    $q->where('new_lplpo_reports.tahun', $periode->year)
                      ->where('new_lplpo_reports.bulan', '<=', $periode->month);

              });

        })

        ->orderByDesc('new_lplpo_reports.tahun')
        ->orderByDesc('new_lplpo_reports.bulan')
        ->orderByDesc('new_lplpo_reports.id')
        ->first();

    return [

        'stok_awal_program_pkd' => $item->stok_akhir_program_pkd ?? 0,

        'stok_awal_jkn' => $item->stok_akhir_jkn ?? 0,

        'penerimaan_program_pkd' => $item->pemberian_pkd ?? 0,

        'penerimaan_jkn' => $item->pemberian_jkn ?? 0,

    ];
}



/**
     * seluruh item suatu report
     */
   public function listByReport($reportId)
{
    return Item::with('program')
        ->where('report_id', $reportId)
        ->orderBy('program_id')
        ->orderBy('nama_obat')
        ->get();
}

    /**
     * tambah item
     */
    public function create(array $data)
    {

        return DB::transaction(function() use ($data){

            return Item::create([

                'report_id'=>$data['report_id'],
                'program_id'=>$data['program_id'],

                'kode_obat'=>$data['kode_obat'],
                'nama_obat'=>$data['nama_obat'],
                'satuan'=>$data['satuan'],

                'stok_awal_progam_pkd'=>$data['stok_awal_progam_pkd'],
                'stok_awal_jkn'=>$data['stok_awal_jkn'],

                'penerimaan_program_pkd'=>$data['penerimaan_program_pkd'],
                'penerimaan_jkn'=>$data['penerimaan_jkn'],

                'persediaan_program_pkd'=>$data['persediaan_program_pkd'],
                'persediaan_jkn'=>$data['persediaan_jkn'],

                'pemakaian_program_pkd'=>$data['pemakaian_program_pkd'],
                'pemakaian_jkn'=>$data['pemakaian_jkn'],

                'item_expired'=>$data['item_expired'],

                'stok_akhir_program_pkd'=>$data['stok_akhir_program_pkd'],
                'stok_akhir_jkn'=>$data['stok_akhir_jkn'],

                'stok_minimum'=>$data['stok_minimum'],
                'stok_optimum'=>$data['stok_optimum'],

                'permintaan'=>$data['permintaan'],
                  'pemberian_program_pkd'  => $data['pemberian_program_pkd'],
                  'pemberian_jkn'          => $data['pemberian_jkn']

            ]);

        });

    }

    /**
     * update item
     */
    public function update(Item $item,array $data)
    {

        $item->update($data);

        return $item;

    }

    /**
     * hapus item
     */
    public function delete(Item $item)
    {
        return $item->delete();
    }


    public function datatable($reportId)
{

    return Item::

        with('program')

        ->where('report_id',$reportId)

        ->get();

}



}
