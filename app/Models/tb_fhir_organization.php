<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_fhir_organization extends Model
{
    use HasFactory;
    protected $table = "tb_fhir_organization";
    protected $fillable = [
        'fullUrl','resourceType','id','versionId','lastUpdated','IdentifierSystem','IdentifierRef','IdentifierValue','active','typeCode','typeDisplay','name','telecomPhone','telecomEmail','addressInfo','addressRef','addressProvinceCode','addressCityCode','addressDistrictCode','addressVillageCode','addressUse','addressPostalCode','addressCountry','mode'

    ];

}
