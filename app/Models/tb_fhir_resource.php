<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_fhir_resource extends Model
{
    use HasFactory;
    protected $fillable = [
        'fhirid',
        'fullUrl',
         'patient_id' ,
            'patient_nik',
            'patient_nik_ref',
           'patient_rmn',

            'patient_rmn_ref',
         'patient_name',
           'patient_familyname',
           'patient_gender',
           'patient_bdate',
           'contact_phone',
           'contact_email',
           'administratif_code_ref',
           'province_code',
           'province_name',
           'city_code',
           'city_name',
           'district_code',
           'district_name',
           'fhir_last_update'

    ];
}
