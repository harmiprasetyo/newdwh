<?php

namespace App\Services;

use App\Models\TargetSasaran;
use App\Models\MasterPosyandu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TargetSasaranService
{

public function getPosyandu()
{
    return MasterPosyandu::where('isActive', 1)
        ->orderBy('namaPosyandu')
        ->get();
}

    public function store(array $request)
    {

        DB::beginTransaction();

        try{

          $posyandu = MasterPosyandu::findOrFail($request['posyandu_id']);

TargetSasaran::create([

    'province_code'  => $posyandu->province_code,
    'city_code'      => $posyandu->city_code,
    'district_code'  => $posyandu->district_code,
    'village_code'   => $posyandu->village_code,

    'kodePosyandu'   => $posyandu->kodePosyandu,
    'namaPosyandu'   => $posyandu->namaPosyandu,

    'posyandu_id'    => $posyandu->id,

    'bulan'          => $request['bulan'],
    'tahun'          => $request['tahun'],
    'rw'             => $request['rw'],
    'rt'             => $request['rt'],

    'sasaran_ibu_hamil'         => $request['sasaran_ibu_hamil'],
    'sasaran_ibu_melahirkan'    => $request['sasaran_ibu_melahirkan'],
    'sasaran_bayi_baru_lahir'   => $request['sasaran_bayi_baru_lahir'],

    'created_by' => auth()->user()->id,

]);

            DB::commit();

            return [
                'success'=>true,
                'message'=>'Data berhasil disimpan.'
            ];

        }catch(\Exception $e){

            DB::rollBack();

            return [
                'success'=>false,
                'message'=>$e->getMessage()
            ];

        }

    }

    public function update(TargetSasaran $model,array $request)
    {

        DB::beginTransaction();

        try{

            $model->update([

                'posyandu_id'=>$request['posyandu_id'],

                'bulan'=>$request['bulan'],

                'tahun'=>$request['tahun'],

                'rw'=>$request['rw'],

                'rt'=>$request['rt'],

                'sasaran_ibu_hamil'=>$request['sasaran_ibu_hamil'],

                'sasaran_ibu_melahirkan'=>$request['sasaran_ibu_melahirkan'],

                'sasaran_bayi_baru_lahir'=>$request['sasaran_bayi_baru_lahir'],

                'updated_by'=>Auth::id()

            ]);

            DB::commit();

            return [
                'success'=>true,
                'message'=>'Data berhasil diperbarui.'
            ];

        }catch(\Exception $e){

            DB::rollBack();

            return [
                'success'=>false,
                'message'=>$e->getMessage()
            ];

        }

    }

    public function delete(TargetSasaran $model)
    {

        DB::beginTransaction();

        try{

            $model->delete();

            DB::commit();

            return [
                'success'=>true,
                'message'=>'Data berhasil dihapus.'
            ];

        }catch(\Exception $e){

            DB::rollBack();

            return [
                'success'=>false,
                'message'=>$e->getMessage()
            ];

        }

    }

}
