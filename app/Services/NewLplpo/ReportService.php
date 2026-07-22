<?php

namespace App\Services\NewLplpo;

use App\Models\NewLplpo\Report;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReportService
{

    public function dashboard()
    {
        return [

            'draft'=>Report::where('report_status','DRAFT')->count(),

            'terkirim'=>Report::where('report_status','SUBMITED')->count(),

            'terverifikasi'=>Report::where('report_status','VERIFIED')->count(),
            'ditolak' =>Report::where('report_status','REJECT')->count(),

            'selesai'=>Report::where('report_status','FINAL')->count()

        ];
    }

    public function list($request)
    {

        return Report::query()

            ->when($request->bulan,function($q) use($request){

                $q->where('bulan',$request->bulan);

            })

            ->when($request->tahun,function($q) use($request){

                $q->where('tahun',$request->tahun);

            })

            ->orderByDesc('id')

            ->paginate(20);

    }


    public function laporan($request)
{
    return Report::withCount('items')

        ->when($request->bulan, function ($q) use ($request) {
            $q->where('bulan', $request->bulan);
        })

        ->when($request->tahun, function ($q) use ($request) {
            $q->where('tahun', $request->tahun);
        })

        ->orderByDesc('created_at')
        ->get();
}


    public function create(array $data)
    {

        DB::beginTransaction();

        try{

            $report=Report::create([

                'kode_faskes'=>$data['kode_faskes'],

                'nama_faskes'=>$data['nama_faskes'],

                'bulan'=>$data['bulan'],

                'tahun'=>$data['tahun'],

                'nomor_lplpo'=>$data['nomor_lplpo'],

                'report_status'=>'Draft'

            ]);

            DB::commit();

            return $report;

        }catch(\Exception $e){

            DB::rollBack();

            throw $e;

        }

    }

    public function update(Report $report,array $data)
    {


     if(
        $report->report_status=='SUBMITED' &&
        ($data['report_status'] ?? null)!='DRAFT'
    ){
        throw ValidationException::withMessages([
            'report'=>'Laporan sudah disubmit.'
        ]);
    }

    $report->update($data);

        return $report;

    }

    public function delete(Report $report)
    {

        return $report->delete();

    }

}
