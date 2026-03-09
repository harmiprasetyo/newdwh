<?php

namespace App\Models\fhir;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FHIR_resource_patient extends Model
{
    use HasFactory;
    protected $table = "FHIR_resource_patient";
    protected $fillable = [
        "pid",
            "fullurl",
            "resourceType",
            "id",
            "meta_versionId",
            "meta_lasteUpdated",
            "identifier_use_0",
            "identifier_system_0",
            "identifier_value_0",
            "identifier_use_1",
            "identifier_system_1",
            "identifier_value_1",
            "name_use",
            "name_text",
            "name_family",
            "telecom_system_0",
            "telecom_value_0",
            "telecom_use_0",
            "telecom_system_1",
            "telecom_value_1",
            "gender",
            "birthDate",
            "address_extension_0_url_0",
           "address_extension_0_extension_0_url_0",
           "address_extension_0_extension_0_valueCoding_system_0",
           "address_extension_0_extension_0_valueCoding_code_0",
           "address_extension_0_extension_0_valueCoding_display_0",
           "address_extension_0_extension_1_url_0",
           "address_extension_0_extension_1_valueCoding_system_0",
           "address_extension_0_extension_1_valueCoding_code_0",
           "address_extension_0_extension_1_valueCoding_display_0",
           "address_extension_0_extension_2_url_0",
           "address_extension_0_extension_2_valueCoding_system_0",
           "address_extension_0_extension_2_valueCoding_code_0",
           "address_extension_0_extension_2_valueCoding_display_0",
           "address_use_0"


    ];


}
