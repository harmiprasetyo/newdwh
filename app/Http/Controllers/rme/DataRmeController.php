<?php

namespace App\Http\Controllers\rme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Models\PregnancyRecord;
use App\Models\VitalSign;
use App\Models\Measurement;
use App\Models\Observation\AncDeliveryRecord as AncDeliveryRecord;
use App\Models\Observation\PncRecord;
use App\Models\Observation\NeonatalRecord;
use App\Services\Fhir\PatientService;
use App\Services\Fhir\EncounterService;
use App\Services\Fhir\ImmunizationService;



class DataRmeController extends Controller
{
    //


     protected $patientService;
    protected $encounterService;
    protected $immunizationService;

    public function __construct(
        PatientService $patientService,
        EncounterService $encounterService,
        ImmunizationService $immunizationService
    ){
        $this->patientService = $patientService;
        $this->encounterService = $encounterService;
        $this->immunizationService = $immunizationService;
    }


    public function index(){
        return view('rme.searchpasien');
    }

    public function landingpage(Request $request){
    $nik = $request->nik;
    return view('rme.datapasien',["nik"=>$nik]);
    }






    public function checkdata(Request $request){
        $token = env('FHIR_API_TOKEN');
        $server = env('FHIR_API_URL');
        $nik = $request->nik;

        $data = $this->patientService->searchByNik($nik);

       if(!$data){
        return response()->json([
            'status' => 'not_found',
            'total' => 0,
            'data' => null
        ]);
       }

        return response()->json([
        'status' => 'success',
        'total' => 1,
        'data' => $data,
         'nik' => $data->nik,
        'name' => $data->name,
        'phone' => $data->phone
    ]);

       }




    public function dataPasien(Request $request){
        $token = env('FHIR_API_TOKEN');
        $server = env('FHIR_API_URL');
        $nik = $request->nik;
        $encounterId = $request->idencounter;

  $patient = $this->patientService->searchByNik($nik);
   $dt['PATIENTID'] = $patient->toArray();
    $encounters = $this->encounterService->getByID($encounterId);
    $dt['ENCOUNTER'] = $encounters->toArray();

    $dt['IMMUNO'] = $this->immunizationService
    ->getByPatient($patient->patient_id)
    ->toArray();








        $observ = Http::withToken($token)->get($server."Observation?patient=".$dt['PATIENTID']['patient_id']."&encounter=".$encounterId);
        $obserResult = $observ->json();
      //dd($obserResult);




if(isset($obserResult['total'])){
    $total = $obserResult['total'];
}



        if(isset($obserResult['entry'])){

        $dt['sistole'] = null;
$dt['diastole'] = null;
$dt['anc_body_heigh'] = null;

$dt['VS'] = [
    'nadi' => null,
    'suhuBadan' => null,
    'pernafasan' => null
];

$dt['ANC'] = [
    'gravida' => 0,
    'parity' => 0,
    'abortions' => 0,
    'anc_hpl' => null,
    'anc_jarak_hamil' => null,
];




foreach($obserResult['entry'] as $k=>$obs){
     $codes = getCodes($obs);
    $value = getVal($obs);
    $valueANC = getValANC($obs);
    $unit  = getUnit($obs);
//dd($obs);

 if($obs['resource']['category'][0]['coding'][0]['code']=='vital-signs'){




    // simpan list observasi
    $dt['OBS'][$k] = [
        'param_code' => $codes[0] ?? null,
        'param_name' => data_get($obs, 'resource.code.coding.0.display'),
        'valueQty'   => $value,
        'valueUnit'  => $unit
    ];

    // 🔴 SISTOLE
    if (in_array('8480-6', $codes)) {
        $dt['sistole'] = $value;
    }

    // 🔵 DIASTOLE
    if (in_array('8462-4', $codes)) {
        $dt['diastole'] = $value . ' ' . $unit;
    }

    // 🟢 TINGGI BADAN
    if (in_array('8302-2', $codes)) {
        $dt['anc_body_heigh'] = $value . ' ' . $unit;
         $dt['ANC']['anc_body_heigh'] = $value . ' ' . $unit;
    }

    // 💓 NADI
    if (in_array('8867-4', $codes)) {
        $dt['VS']['nadi'] = $value . ' ' . $unit;
    }

    // 🌡️ SUHU
    if (in_array('8310-5', $codes)) {
        $dt['VS']['suhuBadan'] = $value . ' ' . $unit;
    }

    // 🫁 PERNAFASAN
    if (in_array('9279-1', $codes)) {
        $dt['VS']['pernafasan'] = $value . ' ' . $unit;
    }



}elseif($obs['resource']['category'][0]['coding'][0]['code']=='survey'){


             if (in_array('11996-6', $codes)) {
        $dt['ANC']['gravida'] = $valueANC ;
    }

    if (in_array('11977-6', $codes)) {
        $dt['ANC']['parity'] = $valueANC ;
    }elseif (in_array('64708-1', $codes)) {
        $dt['ANC']['parity'] = $valueANC ;
    }

   if (in_array('69043-8', $codes)) {
        $dt['ANC']['abortions'] = $valueANC ;
    }





//$dt['anc_hpl']=$obs['resource']['valueDateTime'];

            if(isset($obs['resource']['code']['coding'][0]['code']) && $obs['resource']['code']['coding'][0]['code']=='11778-8'){
                 $dt['codeHPL'] = $obs['resource']['code']['coding'][0]['code'];
                 $dt['ANC']['anc_hpl']=Carbon::parse($obs['resource']['valueDateTime'])->format("d M Y");
                 //$dt['ANC']['test'] = "exit";

            }else{
                $dt['anc_hpl']=" - ";
                }


            if(isset($obs['resource']['code']['coding'][0]['code']) && $obs['resource']['code']['coding'][0]['code']=='OC000001'){
                $dt['ANC']['anc_jarak_hamil']=$obs['resource']['valueQuantity']['value']." bln";

            }else{
                $dt['anc_jarak_hamil']=" - ";
            }



            //$dt['anc_hpl']=$obs['resource']['valueDateTime'];




        }elseif(isset($obs['resource']['category'][0]['coding'][0]['code']) && $obs['resource']['category'][0]['coding'][0]['code']=='laboratory'){


        if(isset($obs['resource']['code']['coding'][0]['code'])){

        if($obs['resource']['code']['coding'][0]['code']=='718-7'){
                $dt['lab']['lab_hb']['val']=$obs['resource']['valueQuantity']['value']." ".$obs['resource']['valueQuantity']['unit'];
                 $dt['lab']['lab_hb']['label']="Hemoglobin";
            }else{
                $dt['lab']['lab_hb']['val']="-";
                 $dt['lab']['lab_hb']['label']="Hemoglobin";

            }

            if($obs['resource']['code']['coding'][0]['code']=='5804-0'){
                $dt['lab']['lab_urin_protein']['val']=$obs['resource']['valueQuantity']['value']." ".$obs['resource']['valueQuantity']['unit'];
                 $dt['lab']['lab_urin_protein']['label'] = "Urin Protein";
            }else{

            $dt['lab']['lab_urin_protein']['val']="-";
                 $dt['lab']['lab_urin_protein']['label'] = "Urin Protein";

            }

            if($obs['resource']['code']['coding'][0]['code']=='74774-1'){
                $dt['lab']['lab_gula_darah']['val']=$obs['resource']['valueQuantity']['value']." ".$obs['resource']['valueQuantity']['unit'];
                $dt['lab']['lab_gula_darah']['label'] = "Gula Darah";
            }else{
                $dt['lab']['lab_gula_darah']['val']="-";
                $dt['lab']['lab_gula_darah']['label'] = "Gula Darah";

            }



             if($obs['resource']['code']['coding'][0]['code']=='75410-1'){
                $dt['lab']['lab_hepatitis_b']['val']=$obs['resource']['valueCodeableConcept']['coding'][0]['display'];
                $dt['lab']['lab_hepatitis_b']['label'] = "Hepatitis B";
            }else{
                $dt['lab']['lab_hepatitis_b']['val']="-";
                $dt['lab']['lab_hepatitis_b']['label'] = "Hepatitis B";
            }

             if($obs['resource']['code']['coding'][0]['code']=='68961-2'){
                $dt['lab']['lab_hiv']['val']=$obs['resource']['valueCodeableConcept']['coding'][0]['display'];
                $dt['lab']['lab_hiv']['label'] = "Tes HIV";
            }else{
                $dt['lab']['lab_hiv']['val']="-";
                $dt['lab']['lab_hiv']['label'] = "Tes HIV";
            }

        }




        }elseif(isset($obs['resource']['category'][0]['coding'][0]['code']) && $obs['resource']['category'][0]['coding'][0]['code']=='exam'){



             if(isset($obs['resource']['code']['coding'][0]['code']) && $obs['resource']['code']['coding'][0]['code']=='284473002'){
                $dt['ANC']['anc_lila']=$obs['resource']['valueQuantity']['value']." ".$obs['resource']['valueQuantity']['unit'];
            }



        }


        }



        }else{

   $dt['INFO']['total']=0;

            $dt['ANC']['sistole'] = "-";
            $dt['ANC']['diastole'] ="-";
            $dt['ANC']['anc_lila']="-";
            $dt['ANC']['gravida']="-";
            $dt['ANC']['parity']="-";
            $dt['ANC']['abortions']="-";
            $dt['ANC']['anc_jarak_hamil']="-";
            $dt['ANC']['anc_hpl']="-";
            $dt['ANC']['anc_body_heigh']="-";
            $dt['ANC']['label']['bln'] = array("01"=>"Jan","02"=>"Feb","03"=>"Mar","04"=>"Apr","05"=>"Mei","06"=>"Jun","07"=>"Jul","08"=>"Agt","09"=>"Sep","10"=>"Okt","11"=>"Nop","12"=>"Des");




        }


          $condition = Http::withToken($token)->get($server."Condition?patient=".$dt['PATIENTID']['patient_id']."&encounter=".$encounterId);
            $kondisi = $condition->json();
if($kondisi['total']>0){
            foreach($kondisi['entry'] as $kx=>$nres){



            }
}


//KOHORT


    $eCare = Http::withToken($token)->get($server.'EpisodeOfCare?patient='.$dt['PATIENTID']['patient_id']);
       $eRes = $eCare->json();
       $year = date("Y");



       if($eRes['total']>0){


       $dt['label']['bln'] = array("01"=>"Jan","02"=>"Feb","03"=>"Mar","04"=>"Apr","05"=>"Mei","06"=>"Jun","07"=>"Jul","08"=>"Agt","09"=>"Sep","10"=>"Okt","11"=>"Nop","12"=>"Des");

       foreach($eRes['entry'] as $key=>$nilai){
        if(is_array($nilai)){
            foreach($nilai as $k1=>$val1){

            if(isset($val1['id'])){
                $visits = Http::withToken($token)->get($server.'/Encounter?patient='.$dt['PATIENTID']['patient_id'].'&episode-of-care='.$val1['id']);
                $vis = $visits->json();
                if($vis['total']>0){
                foreach($vis['entry'] as $kvis=>$nvis){
                    $dt['KOHORT'][$key]['anc_jenis_kunjungan'] = $nvis['resource']['identifier'][0]['value'];

                 $ids = $nvis['resource']['id'];

        $KHobserv= Http::withToken($token)->get($server."Observation?patient=".$dt['PATIENTID']['patient_id']."&encounter=".$encounterId);
        $KOB = $KHobserv->json();
       if(isset($KOB['total']) && $KOB['total']>0){
            foreach($KOB['entry'] as $kb=>$nnb){

            if(isset($nnb['resource']['code']['coding']['0']['code'])){

            if($nnb['resource']['code']['coding']['0']['code']=='29463-7'){
                $dt['KOHORT'][$key]['anc_body_weight'] = $nnb['resource']['valueQuantity']['value']." ".$nnb['resource']['valueQuantity']['unit'];
            }

             if($nnb['resource']['code']['coding']['0']['code']=='11881-0'){
                $dt['KOHORT'][$key]['anc_tinggi_fundus'] = $nnb['resource']['valueQuantity']['value']." ".$nnb['resource']['valueQuantity']['unit'];
            }

             if($nnb['resource']['code']['coding']['0']['code']=='55283-6'){
                $dt['KOHORT'][$key]['anc_djj'] = $nnb['resource']['valueQuantity']['value']." ".$nnb['resource']['valueQuantity']['unit'];
            }

            if($nnb['resource']['code']['coding']['0']['code']=='89087-1'){
                $dt['KOHORT'][$key]['anc_tbj'] = $nnb['resource']['valueQuantity']['value']." ".$nnb['resource']['valueQuantity']['unit'];
            }

              if($nnb['resource']['code']['coding']['0']['code']=='72155-5'){
                $dt['KOHORT'][$key]['anc_presentasi'] = $nnb['resource']['valueCodeableConcept']['coding'][0]['display'];
            }

            if($nnb['resource']['code']['coding']['0']['code']=='249111004'){
                $dt['KOHORT'][$key]['anc_posisi_kepala'] = $nnb['resource']['valueCodeableConcept']['coding'][0]['display'];
            }



            }


            }
        }

                }



                }

            }






                if(isset($val1['type'][0]['coding'][0]['code']) && $val1['type'][0]['coding'][0]['code']=='ANC'){

                if(isset($val1['period'])){

                $dt['KOHORT'][$key]['anc_bulan'] = Carbon::parse($val1['period']['start'])->format('m');
                $dt['KOHORT'][$key]['anc_kunjungan']=$val1['period']['start'];

                }else{
                    $dt['KOHORT'][$key]['anc_bulan'] ="-";
                    $dt['KOHORT'][$key]['anc_kunjungan']="-";

                }
                }else{
                     $dt['KOHORT'][$key]['anc_bulan'] ="-";
                    $dt['KOHORT'][$key]['anc_kunjungan']="-";
                }

               // $dt['KOHORT'][$key]['anc_kunjungan']=$val1['period']['start'];
            }
        }


        }


       }else{
          $dt['label']['bln'] = array("01"=>"Jan","02"=>"Feb","03"=>"Mar","04"=>"Apr","05"=>"Mei","06"=>"Jun","07"=>"Jul","08"=>"Agt","09"=>"Sep","10"=>"Okt","11"=>"Nop","12"=>"Des");
       }




               //======== Mapping to DB =======



        $ObservSTR= Http::withToken($token)->get($server."Observation?patient=".$dt['PATIENTID']['patient_id']."&encounter=".$encounterId);
        $data = $ObservSTR->json();




        $pregnancy = [];
        $vital = [];
        $measure = [];
if (isset($data['entry'])) {
        foreach ($data['entry'] as $item) {



            $r = $item['resource'];
            $code = $r['code']['coding'][0]['code'] ?? null;

            //$patient = $r['subject']['reference'] ?? null;
            //$encounter = $r['encounter']['reference'] ?? null;

            $patient = $dt['PATIENTID']['patient_id'];
            $encounter = $encounterId;

            // base
            $pregnancy['patient_id'] = $patient;
            $pregnancy['encounter_id'] = $encounter;

            $vital['patient_id'] = $patient;
            $vital['encounter_id'] = $encounter;

            $measure['patient_id'] = $patient;
            $measure['encounter_id'] = $encounter;

             $delivery['patient_id'] = $patient;
            $delivery['encounter_id'] = $encounter;

            $pnc['patient_id'] = $patient;
            $pnc['encounter_id'] = $encounter;


             $neonatal['patient_id'] = $patient;
            $neonatal['encounter_id'] = $encounter;

            //NN
              // mapping LOINC → field & variable
    $map = [

        // ===== NN pemeriksaan fisik =====
        '10206-1' => ['path' => 'interpretation.0.coding.0.display', 'field' => 'kulit'],
        '10199-8' => ['path' => 'interpretation.0.coding.0.display', 'field' => 'kepala'],
        '10197-2' => ['path' => 'interpretation.0.coding.0.display', 'field' => 'mata'],
        '32453-3' => ['path' => 'interpretation.0.coding.0.display', 'field' => 'mulut'],
        '10191-5' => ['path' => 'interpretation.0.coding.0.display', 'field' => 'abdomen'],
        '10192-3' => ['path' => 'interpretation.0.coding.0.display', 'field' => 'punggung'],
        '11388-6' => ['path' => 'interpretation.0.coding.0.display', 'field' => 'bokong'],
        '11400-9' => ['path' => 'interpretation.0.coding.0.display', 'field' => 'genitalia'],

        // ===== APGAR 1 menit =====
        '32406-1' => ['field' => 'color', 'var' => 'color_score'],
        '32407-9' => ['field' => 'heartrate', 'var' => 'hr_score'],
        '32409-5' => ['field' => 'reflex', 'var' => 'reflex_score'],
        '32408-7' => ['field' => 'muscle', 'var' => 'muscle_score'],
        '32410-3' => ['field' => 'respiration', 'var' => 'respiration_score'],

        // ===== APGAR 5 menit =====
        generateLoinc('32411') => ['var' => 'color5m_score'],
        generateLoinc('32412') => ['var' => 'hr5m_score'],
        generateLoinc('32413') => ['var' => 'muscle5m_score'],
        generateLoinc('32414') => ['var' => 'reflex5m_score'],
        generateLoinc('32415') => ['var' => 'resp5m_score'],

        // ===== APGAR 10 menit =====
        generateLoinc('32401') => ['var' => 'color10m_score'],
        generateLoinc('32402') => ['var' => 'hr10m_score'],
        generateLoinc('32403') => ['var' => 'muscle10m_score'],
        generateLoinc('32404') => ['var' => 'reflex10m_score'],
        generateLoinc('32405') => ['var' => 'resp10m_score'],
    ];

    foreach ($data['entry'] as $item) {

        $r = $item['resource'] ?? [];

        // ambil loinc code
        $loincCode = data_get($r, 'code.coding.0.code');

        if (!$loincCode || !isset($map[$loincCode])) {
            continue;
        }

        $config = $map[$loincCode];

        // ===== CASE 1: interpretation =====
        if (isset($config['path'])) {
            $value = data_get($r, $config['path']);

            if (isset($config['field'])) {
                $dt['NN'][$config['field']] = $value;
            }

            continue;
        }

        // ===== CASE 2: valueCodeableConcept =====
        $coding  = data_get($r, 'valueCodeableConcept.coding.0', []);
        $code    = $coding['code'] ?? null;
        $display = $coding['display'] ?? $code; // fallback

        if (isset($config['field'])) {
            $dt['NN'][$config['field']] = $code;
        }

        if (isset($config['var'])) {
            ${$config['var']} = $display;
        }
    }


        $map = [

    // ===== ANC =====
    '11996-6' => [
        ['target' => 'pregnancy', 'field' => 'gravida', 'path' => 'valueInteger'],
        ['target' => 'delivery',  'field' => 'gravida', 'path' => 'valueInteger'],
        ['target' => 'pnc',       'field' => 'gravida', 'path' => 'valueInteger'],
    ],

    '64708-1' => [
        ['target' => 'pregnancy', 'field' => 'parity', 'path' => 'valueInteger'],
        ['target' => 'delivery',  'field' => 'parity', 'path' => 'valueInteger'],
        ['target' => 'pnc',       'field' => 'parity', 'path' => 'valueInteger'],
    ],

    '69043-8' => [
        ['target' => 'pregnancy', 'field' => 'abortus', 'path' => 'valueInteger'],
        ['target' => 'delivery',  'field' => 'abortus', 'path' => 'valueInteger'],
        ['target' => 'pnc',       'field' => 'abortus', 'path' => 'valueInteger'],
    ],

    '8665-2' => [
        ['target' => 'pregnancy', 'field' => 'lmp', 'path' => 'valueDateTime', 'format' => 'date'],
    ],

    '11778-8' => [
        ['target' => 'pregnancy', 'field' => 'edd', 'path' => 'valueDateTime', 'format' => 'date'],
    ],

    // ===== VITAL =====
    '8480-6' => [
        ['target' => 'vital', 'field' => 'systolic', 'path' => 'valueQuantity.value'],
    ],

    '8867-4' => [
        ['target' => 'vital',     'field' => 'heart_rate', 'path' => 'valueQuantity.value'],
        ['target' => 'neonatal',  'field' => 'nadi',       'path' => 'valueQuantity.value'],
    ],

    '9279-1' => [
        ['target' => 'vital',     'field' => 'respiratory_rate', 'path' => 'valueQuantity.value'],
        ['target' => 'neonatal',  'field' => 'pernafasan',       'path' => 'valueQuantity.value'],
    ],

    '8310-5' => [
        ['target' => 'vital',     'field' => 'temperature', 'path' => 'valueQuantity.value'],
        ['target' => 'neonatal',  'field' => 'suhu',        'path' => 'valueQuantity.value'],
    ],

    '9843-4'=>[
         ['target' => 'neonatal',  'field' => 'lingkar_kepala',        'path' => 'valueQuantity.value'],
    ],

    // ===== MEASURE =====
    '29463-7' => [
        ['target' => 'measure', 'field' => 'weight', 'path' => 'valueQuantity.value'],
    ],
    '89269-5'=>[
         ['target' => 'neonatal',  'field' => 'panjang_badan','path' => 'valueQuantity.value'],

    ],

    // ===== DELIVERY =====
    '93857-1' => [
        ['target' => 'delivery', 'field' => 'delivery_time', 'path' => 'valueDateTime', 'format' => 'datetime'],
        ['target' => 'pnc',      'field' => 'delivery_time', 'path' => 'valueDateTime', 'format' => 'datetime'],
    ],

    // ===== NEONATAL =====
    '8339-4' => [
        ['target' => 'neonatal', 'field' => 'berat_lahir', 'path' => 'valueQuantity.value'],
    ],

    '9198-5' => [
        ['target' => 'neonatal', 'field' => 'apgar_1_menit', 'path' => 'valueInteger'],
    ],
];

$containers = [
    'pregnancy' => &$pregnancy,
    'delivery'  => &$delivery,
    'pnc'       => &$pnc,
    'vital'     => &$vital,
    'measure'   => &$measure,
    'neonatal'  => &$neonatal,
];

foreach ($data['entry'] as $item) {

    $r = $item['resource'] ?? [];
    $code = data_get($r, 'code.coding.0.code');

    if (!$code || !isset($map[$code])) {
        continue;
    }

    foreach ($map[$code] as $cfg) {

        $value = getFhirValue($r, $cfg['path']);

        // format tanggal
        if (!empty($cfg['format']) && $value) {
            if ($cfg['format'] === 'date') {
                $value = Carbon::parse($value)->format('Y-m-d');
            } elseif ($cfg['format'] === 'datetime') {
                $value = Carbon::parse($value)->format('Y-m-d H:i:s');
            }
        }

        $containers[$cfg['target']][$cfg['field']] = $value;
    }
}
        }
//dd($neonatal);
        // SAVE
        if(!isset($pnc['delivery_time'])){
        PregnancyRecord::updateOrCreate(
            [
                'patient_id' => $pregnancy['patient_id'],
                'encounter_id' => $pregnancy['encounter_id']
            ],
            $pregnancy
        );
        }
$loincMap = [
    '32406-1' => ['field' => 'color', 'var' => 'color_score'],
    '32407-9' => ['field' => 'heartrate', 'var' => 'hr_score'],
    '32409-5' => ['field' => 'reflex', 'var' => 'reflex_score'],
    '32408-7' => ['field' => 'muscle', 'var' => 'muscle_score'],
    '32410-3' => ['field' => 'respiration', 'var' => 'respiration_score'],

    generateLoinc('32411') => ['var' => 'color5m_score'],
    generateLoinc('32412') => ['var' => 'hr5m_score'],
    generateLoinc('32413') => ['var' => 'muscle5m_score'],
    generateLoinc('32414') => ['var' => 'reflex5m_score'],
    generateLoinc('32415') => ['var' => 'resp5m_score'],

    generateLoinc('32401') => ['var' => 'color10m_score'],
    generateLoinc('32402') => ['var' => 'hr10m_score'],
    generateLoinc('32403') => ['var' => 'muscle10m_score'],
    generateLoinc('32404') => ['var' => 'reflex10m_score'],
    generateLoinc('32405') => ['var' => 'resp10m_score'],
];

if (isset($loincMap[$loincCode])) {

    $config = $loincMap[$loincCode];

    // kalau ada field → masuk ke dt
    if (isset($config['field'])) {
        $dt['NN'][$config['field']] = $code;
    }

    // set variable score (dynamic)
    if (isset($config['var'])) {
        ${$config['var']} = $display;
    }
}

$dt['APGAR1'] =
    (int)($color_score ?? 0) +
    (int)($hr_score ?? 0) +
   (int) ($reflex_score ?? 0) +
    (int)($muscle_score ?? 0) +
    (int)($respiration_score ?? 0);

    $dt['APGAR5'] =
     (int)($color5m_score ?? 0) +
     (int)($hr5m_score ?? 0) +
     (int)($reflex5m_score ?? 0) +
     (int)($muscle5m_score ?? 0) +
     (int)($resp5m_score ?? 0);

    $dt['APGAR10'] =
     (int)($color10m_score ?? 0) +
     (int)($hr10m_score ?? 0) +
     (int)($reflex10m_score ?? 0) +
     (int)($muscle10m_score ?? 0) +
     (int)($resp10m_score ?? 0);

        VitalSign::updateOrCreate(
            [
                'patient_id' => $vital['patient_id'],
                'encounter_id' => $vital['encounter_id']
            ],
            $vital
        );


        NeonatalRecord::updateOrCreate(
            [
                'patient_id' => $neonatal['patient_id'],
                'encounter_id' => $neonatal['encounter_id']
            ],
            $neonatal
        );


        Measurement::updateOrCreate(
            [
                'patient_id' => $measure['patient_id'],
                'encounter_id' => $measure['encounter_id']
            ],
            $measure
        );
if(isset($pnc['delivery_time'])){
         PncRecord::updateOrCreate(
            [
                'patient_id' => $pnc['patient_id'],
                'encounter_id' => $pnc['encounter_id']
            ],
            $pnc
        );
}


if(isset($delivery['delivery_time'])){
        AncDeliveryRecord::updateOrCreate(
            [
                'patient_id' => $delivery['patient_id'],
                'encounter_id' => $delivery['encounter_id']
            ],
            $delivery
        );

}




$dt['trimester1'] = PregnancyRecord::where('patient_id', $patient)->where('trimester', 1)->count();
$dt['trimester2'] = PregnancyRecord::where('patient_id', $patient)->where('trimester', 2)->count();
$dt['trimester3'] = PregnancyRecord::where('patient_id', $patient)->where('trimester', 3)->count();
$dt['INC'] = AncDeliveryRecord::where('patient_id', $patient)->where('encounter_id', $encounterId)->get()->toArray();
$dt['PNC'] = PncRecord::where('patient_id', $patient)->where('encounter_id', $encounterId)->get()->toArray();
$dt['NEONATAL'] = NeonatalRecord::where('patient_id', $patient)->where('encounter_id', $encounterId)->get()->toArray();


 //       return response()->json([
 //           'status' => 'success'
//        ]);

}

//-------- End Mapping to DB -------
/*echo "<pre>";
print_r($KOB);
echo "</pre>";
*/
$ImnSTR = Http::withToken($token)
    ->get($server."Immunization?patient=".$dt['PATIENTID']['patient_id']);

$dataImn = $ImnSTR->json();

$dt['IMUNISASI'] = [];
$dt['IMN_NN'] = [];

if (!empty($dataImn['entry'])) {

    foreach ($dataImn['entry'] as $imn) {

        $r = $imn['resource'] ?? [];

        // ===== ambil coding aman =====
        $coding = data_get($r, 'vaccineCode.coding.0', []);
        $code   = $coding['code'] ?? null;
        $display= $coding['display'] ?? null;

        // ===== tanggal =====
        $tgl = data_get($r, 'occurrenceDateTime');
        $tglFormatted = $tgl ? Carbon::parse($tgl)->format('d M Y') : null;

        // ===== lokasi =====
        $pos = data_get($r, 'location.display');

        // ===== encounter =====
        $encRef = data_get($r, 'encounter.reference');
        $encId  = $encRef ? explode('/', $encRef)[1] ?? null : null;

        // ===== push ke array =====
        $dt['IMUNISASI'][] = [
            'code' => $code,
            'display' => $display,
            'tglImunisasi' => $tglFormatted,
            'pos' => $pos,
        ];

        // ===== khusus imunisasi neonatal =====
        if ($code === '93008995' && $encId===$encounterId) {

            $dt['IMN_NN'][] = [
                'tglImunisasi' => $tglFormatted,
                'encounter' => $encId,
            ];
        }
    }
}

 $anamnese = Http::withToken($token)
    ->get($server."Condition?encounter=".$encounterId);

$resAnamnese = $anamnese->json();

$dt['ANAMNESE'] = [];

if (!empty($resAnamnese['entry'])) {

    foreach ($resAnamnese['entry'] as $i => $item) {

        $r = $item['resource'] ?? [];

        $dt['ANAMNESE'][] = [
            'diagnosa_kode' => data_get($r, 'code.coding.0.code'),
            'diagnosa_display' => data_get($r, 'code.coding.0.display'),
            // 'note' => data_get($r, 'note.0.text'),
        ];
    }
}


         $plan = Http::withToken($token)->get($server."CarePlan?patient=".$dt['PATIENTID']['patient_id']."&encounter=".$encounterId);
         $rowPlan = $plan->json();
         if(isset($rowPlan['entry'])){
            foreach($rowPlan['entry'] as $k=>$pln){
                $dt['PLAN'][$k]['RTL'] = $pln['resource']['description'];

            }

         }
       $pncProcedure= Http::withToken($token)->get($server."Procedure?patient=".$dt['PATIENTID']['patient_id']."&encounter=".$encounterId);
       $proceData = $pncProcedure->json();
       $dt['PNCPROC'] = [];

       if(isset($proceData['entry'])){
        $dt['proc'] = $proceData;
        foreach($proceData['entry'] as $k=>$pr){
            $dt['PNCPROC'][$k]['code'] = $pr['resource']['code']['coding'][0]['code'];
            $dt['PNCPROC'][$k]['display'] = $pr['resource']['code']['coding'][0]['display'];
            $dt['PNCPROC'][$k]['procedure'] = $pr['resource']['code'];
            $dt['PNCPROC'][$k]['tglvitamin'] = $pr['resource']['performedPeriod']['start'];
        }
       }


//dd($dt['IMUNISASI']);
  return view('rme.detailpasien',["dt"=>$dt]);





    }


    public function pasiensearch(){

    }


    public function searchpasien(Request $request){

        $token = env('FHIR_API_TOKEN');
        $server = env('FHIR_API_URL');
        $nik = $request->nik;

   $patient = $this->patientService->searchByNik($nik);
   $dt['PATIENTID'] = $patient->toArray();
    $encounters = $this->encounterService->getByPatient($dt['PATIENTID']['patient_id']);

    $dt['ENCOUNTER'] = $encounters->toArray();
   // dd($dt['PATIENTID']['patient_id']);

   foreach ($dt['ENCOUNTER'] as $k => $v) {

    $identifiers = is_string($v['identifiers'])
        ? json_decode($v['identifiers'], true)
        : $v['identifiers'];

    $anc = collect($identifiers ?? [])
        ->first(function ($item) {
            return str_contains($item['system'] ?? '', 'ANC');
        });

    if ($anc) {
        $dt['SUBENC'][$k]['jeniskunjungan_name'] = 'ANC';
        $dt['SUBENC'][$k]['kunjunganANC'] = $anc['value'] ?? '-';
    } else {
        $dt['SUBENC'][$k]['jeniskunjungan_name'] = "Lainnya";
        $dt['SUBENC'][$k]['kunjunganANC'] = "-";
    }
}

 return view('rme.datapasien',["dt"=>$dt]);

}


}









