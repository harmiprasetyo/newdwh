<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetSasaran extends Model
{
    protected $table='target_sasaran';

    protected $fillable = [

    'province_code',
    'city_code',
    'district_code',
    'village_code',

    'kodePosyandu',
    'namaPosyandu',

    'posyandu_id',

    'bulan',
    'tahun',
    'rw',
    'rt',

    'sasaran_ibu_hamil',
    'sasaran_ibu_melahirkan',
    'sasaran_bayi_baru_lahir',

    'created_by',
    'updated_by',

];
    public function posyandu()
    {
        return $this->belongsTo(
            MasterPosyandu::class,
            'posyandu_id'
        );
    }

    public function getNamaBulanAttribute()
    {
        return [
            1=>"Januari",
            2=>"Februari",
            3=>"Maret",
            4=>"April",
            5=>"Mei",
            6=>"Juni",
            7=>"Juli",
            8=>"Agustus",
            9=>"September",
            10=>"Oktober",
            11=>"November",
            12=>"Desember"
        ][$this->bulan];
    }
}
