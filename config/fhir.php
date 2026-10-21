<?php

return [
    'base_url' => env('FHIR_API_URL', 'http://fhir.sisfomedika.co.id/fhir'),
    'token' => env('FHIR_API_TOKEN'),
    'timeout' => (int) env('FHIR_API_TIMEOUT', 60),
    'patient_page_size' => (int) env('FHIR_PATIENT_PAGE_SIZE', 100),
    'encounter_page_size' => (int) env('FHIR_ENCOUNTER_PAGE_SIZE', 100),
    'observation_page_size' => (int) env(
    'FHIR_OBSERVATION_PAGE_SIZE',
    100
),'condition_page_size' => (int) env('FHIR_CONDITION_PAGE_SIZE', 100),
'procedure_page_size' => (int) env('FHIR_PROCEDURE_PAGE_SIZE', 100),
'immunization_page_size' => (int) env('FHIR_IMMUNIZATION_PAGE_SIZE', 100),

];
