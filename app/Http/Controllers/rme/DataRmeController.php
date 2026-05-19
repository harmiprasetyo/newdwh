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



            switch ($code) {
                //NN//
                case '10206-1':
                    $dt['NN']['kulit'] = $r['interpretation'][0]['coding'][0]['display'];
                    break;
                     case '10199-8':
                    $dt['NN']['kepala'] = $r['interpretation'][0]['coding'][0]['display'];
                    break;
                     case '10197-2':
                    $dt['NN']['mata'] = $r['interpretation'][0]['coding'][0]['display'];
                    break;

                     case '32453-3':
                    $dt['NN']['mulut'] = $r['interpretation'][0]['coding'][0]['display'];
                    break;

                     case '10191-5':
                    $dt['NN']['abdomen'] = $r['interpretation'][0]['coding'][0]['display'];
                    break;

                    case '10192-3':
                    $dt['NN']['punggung'] = $r['interpretation'][0]['coding'][0]['display'];
                    break;

                    case '11388-6':
                    $dt['NN']['bokong'] = $r['interpretation'][0]['coding'][0]['display'];
                    break;

                    case '11400-9':
                    $dt['NN']['genitalia'] = $r['interpretation'][0]['coding'][0]['display'];
                    break;

                     case '32406-1':
                    $dt['NN']['color'] = $r['valueCodeableConcept']['coding'][0]['code'];
                    break;
                     case '32407-9':
                    $dt['NN']['heartrate'] = $r['valueCodeableConcept']['coding'][0]['code'];
                    break;

                    case '32409-5':
                    $dt['NN']['reflex'] = $r['valueCodeableConcept']['coding'][0]['code'];
                    break;

                     case '32408-7':
                    $dt['NN']['muscle'] = $r['valueCodeableConcept']['coding'][0]['code'];
                    break;

                    case '32410-3':
                    $dt['NN']['respiration'] = $r['valueCodeableConcept']['coding'][0]['code'];
                    break;



                /** ANC */
                case '11996-6':
                    $pregnancy['gravida'] = $r['valueInteger'];
                    $delivery['gravida'] = $r['valueInteger'];
                    $pnc['gravida'] = $r['valueInteger'];
                    break;
                case '64708-1':
                    $pregnancy['parity'] = $r['valueInteger'];
                    $delivery['parity'] = $r['valueInteger'];
                    $pnc['parity'] = $r['valueInteger'];
                    break;

                case '11977-6':
                    $pregnancy['parity'] = $r['valueInteger'];
                    break;

                case '69043-8':
                    $pregnancy['abortus'] = $r['valueInteger'];
                    $delivery['abortus'] = $r['valueInteger'];
                    $pnc['abortus'] = $r['valueInteger'];
                    break;

                case '8665-2':
                    $pregnancy['lmp'] = date('Y-m-d', strtotime($r['valueDateTime']));
                    break;

                case '11778-8':
                    $pregnancy['edd'] = date('Y-m-d', strtotime($r['valueDateTime']));
                    break;

                case '18185-9':
                    $pregnancy['gestational_age'] = $r['valueQuantity']['value']?? null;
                    $delivery['gestational_age'] = $r['valueQuantity']['value'] ?? null;
                    break;

                case '32418-6':
                    $pregnancy['trimester'] = $r['valueInteger'];
                    break;

                /** VITAL */
                case '8480-6':
                    $vital['systolic'] = $r['valueQuantity']['value'];
                    break;

                case '8462-4':
                    $vital['diastolic'] = $r['valueQuantity']['value'];
                    break;

                case '8867-4':
                    $vital['heart_rate'] = $r['valueQuantity']['value'];
                    $neonatal['nadi'] = $r['valueQuantity']['value']?? null;
                    break;

                case '9279-1':
                    $vital['respiratory_rate'] = $r['valueQuantity']['value'];
                    $neonatal['pernafasan'] = $r['valueQuantity']['value']?? null;
                    break;

                case '8310-5':
                    $vital['temperature'] = $r['valueQuantity']['value'];
                    $neonatal['suhu'] = $r['valueQuantity']['value']?? null;
                    break;

                /** MEASURE */
                case '8302-2':
                    $measure['height'] = $r['valueQuantity']['value'];
                    break;

                case '29463-7':
                    $measure['weight'] = $r['valueQuantity']['value'];
                    break;

                case '56077-1':
                    $measure['pre_weight'] = $r['valueQuantity']['value'];
                    break;

                case 'OC000010':
                    $measure['bmi'] = $r['valueQuantity']['value'];
                    $measure['bmi_status'] = $r['interpretation'][0]['coding'][0]['display'] ?? null;
                    break;

                case '284473002':
                    $measure['lila'] = $r['valueQuantity']['value'];
                    break;

                case '11881-0':
                    $measure['sfh'] = $r['valueQuantity']['value'];
                    break;

                    //Delivery


                case '11996-6':
                    $delivery['gravida'] = $r['valueInteger'] ?? null;
                    break;

                case '64708-1':
                    $delivery['parity'] = $r['valueInteger'] ?? null;
                    break;

                case '69043-8':
                    $delivery['abortus'] = $r['valueInteger'] ?? null;
                    break;

                case '93857-1':
                    $delivery['delivery_time'] = Carbon::parse($r['valueDateTime'])->format('Y-m-d H:i:s') ?? null;
                     $pnc['delivery_time'] = Carbon::parse($r['valueDateTime'])->format('Y-m-d H:i:s') ?? null;
                    break;

                case '249197004':
                    $delivery['postpartum_condition'] =
                        $r['valueCodeableConcept']['coding'][0]['display'] ?? null;
                    break;

                case 'OC000013':
                    $delivery['delivery_helper'] =
                        $r['valueCodeableConcept']['coding'][0]['display'] ?? null;
                    break;

                case '57071-3':
                    $delivery['delivery_method'] =
                        $r['valueCodeableConcept']['coding'][0]['display'] ?? null;
                    break;

                case '249120008':
                    $delivery['stage1'] = Carbon::parse($r['valueDateTime'])->format('Y-m-d H:i:s') ?? null;
                    break;

                case '249160009':
                    $delivery['stage2'] = Carbon::parse($r['valueDateTime'])->format('Y-m-d H:i:s') ?? null;
                    break;

                case 'OC000018':
                    $delivery['stage3'] = Carbon::parse($r['valueDateTime'])->format('Y-m-d H:i:s') ?? null;
                    break;

                case 'OC000019':
                    $delivery['stage4'] = Carbon::parse($r['valueDateTime'])->format('Y-m-d H:i:s') ?? null;
                    break;

                    // PNC
                    case '81661-1':
                    $pnc['pendarahan'] = $r['valueQuantity']['value'] ?? null;
                    break;

                     case '32422-8':
                    $pnc['pemeriksaan_payudara'] = $r['valueCodeableConcept']['coding'][0]['display'] ?? null;
                    break;

                    case '364297003':
                        $pnc['kondisi_perineum'] = $r['valueString'] ?? null;
                        break;
                    case 'OC000020':
                        $pnc['tanda_infeksi_perineum'] = $r['valueBoolean']==true ? 'ada' : 'Tidak ada' ?? null;
                        break;
                    case 'OC000025':
                        $pnc['tanda_infeksi_luka_sc']= $r['valueBoolean']==true ? 'ada' : 'Tidak ada' ?? null;
                        break;
                    case 'OC000017':
                        $pnc['produksi_asi'] = $r['valueCodeableConcept']['coding'][0]['display'] ?? null;
                        break;


                        // Neonatal
                        case '57715-5':
                            $neonatal['jam_lahir'] = $r['valueTime'] ?? null;
                            break;

                            case '8339-4':
                                $neonatal['berat_lahir'] = $r['valueQuantity']['value'] ?? null;
                                break;
                            case '8330-2':
                                $neonatal['panjang_badan'] = $r['valueQuantity']['value'] ?? null;
                                break;
                            case '9843-4':
                                $neonatal['lingkar_kepala'] = $r['valueQuantity']['value'] ?? null;
                                break;

                                        case '9198-5':
                                            $neonatal['apgar_1_menit'] = $r['valueInteger'] ?? null;
                                            break;
                                        case '9199-3':
                                            $neonatal['apgar_5_menit'] = $r['valueInteger'] ?? null;
                                            break;
                                        case '9200-1':
                                            $neonatal['apgar_10_menit'] = $r['valueInteger'] ?? null;
                                            break;


            }
        }

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


        $score = [
    'color' => [
        'LA6724-4' => 0,
        'LA6725-1' => 1,
        'LA6726-9' => 2,
    ],
    'heartrate' => [
        'LA6718-6' => 0,
        'LA6719-4' => 1,
        'LA6720-2' => 2,
    ],
    'reflex' => [
        'LA6721-0' => 0,
        'LA6722-8' => 1,
        'LA6723-6' => 2,
    ],
    'muscle' => [
        'LA6714-5' => 0,
        'LA6715-2' => 1,
        'LA6716-0' => 2,
    ],
    'respiration'=>[
        'LA6727-7' => 0,
        'LA6728-5' => 1,
        'LA6729-3' => 2,
    ]
];
if(isset($dt['NN']['color'])){
$dt['NN']['color_score'] = $score['color'][$dt['NN']['color']] ?? null;
}

if(isset($dt['NN']['heartrate'])){
$dt['NN']['heartrate_score'] = $score['heartrate'][$dt['NN']['heartrate']] ?? null;
}

if(isset($dt['NN']['reflex'])){
$dt['NN']['reflex_score'] = $score['reflex'][$dt['NN']['reflex']] ?? null;
}
if(isset($dt['NN']['muscle'])){
$dt['NN']['muscle_score'] = $score['muscle'][$dt['NN']['muscle']] ?? null;
}
if(isset($dt['NN']['respiration'])){
$dt['NN']['respiration_score'] = $score['respiration'][$dt['NN']['respiration']] ?? null;
}


/*dd([
    'color' => $dt['NN']['color'] ?? null,
    'color_score' => $dt['NN']['color_score'] ?? null,

    'hr' => $dt['NN']['heartrate'] ?? null,
    'hr_score' => $dt['NN']['heartrate_score'] ?? null,

    'reflex' => $dt['NN']['reflex'] ?? null,
    'reflex_score' => $dt['NN']['reflex_score'] ?? null,

    'muscle' => $dt['NN']['muscle'] ?? null,
    'muscle_score' => $dt['NN']['muscle_score'] ?? null,

    'resp' => $dt['NN']['respiration'] ?? null,
    'resp_score' => $dt['NN']['respiration_score'] ?? null,
]);*/

$dt['APGAR1'] =
    ($dt['NN']['color_score'] ?? 0) +
    ($dt['NN']['heartrate_score'] ?? 0) +
    ($dt['NN']['reflex_score'] ?? 0) +
    ($dt['NN']['muscle_score'] ?? 0) +
    ($dt['NN']['respiration_score'] ?? 0);
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
$ImnSTR= Http::withToken($token)->get($server."Immunization?patient=".$dt['PATIENTID']['patient_id']);
        $dataImn = $ImnSTR->json();
$dt['IMUNISASI'] = [];
       if(isset($dataImn['entry'])) {
      //  $dt['imth'] = $dataImn['entry'];
           foreach($dataImn['entry'] as $k=>$imn){
                $dt['IMUNISASI'][$k]['code'] = $imn['resource']['vaccineCode']['coding'][0]['code'];
                $dt['IMUNISASI'][$k]['display'] = $imn['resource']['vaccineCode']['coding'][0]['display'];
                $dt['IMUNISASI'][$k]['tglImunisasi'] = Carbon::parse($imn['resource']['occurrenceDateTime'])->format('d M Y');
                $dt['IMUNISASI'][$k]['pos'] = $imn['resource']['location']['display'];
            }
       }

 $anamnese = Http::withToken($token)->get($server."Condition?encounter=".$encounterId);
         $resAnamnese = $anamnese->json();
         if($resAnamnese['total']>0){
            for($i=0;$i<$resAnamnese['total'];$i++){
                $dt['ANAMNESE'][$i]['diagnosa_kode'] = $resAnamnese['entry'][$i]['resource']['code']['coding'][0]['code'];
                 $dt['ANAMNESE'][$i]['diagnosa_display'] = $resAnamnese['entry'][$i]['resource']['code']['coding'][0]['display'];
                 // $dt['ANAMNESE'][$i]['note'] = $resAnamnese['entry'][$i]['resource']['note'][0]['text'];
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
        foreach($proceData['entry'] as $k=>$pr){
            $dt['PNCPROC'][$k]['code'] = $pr['resource']['code']['coding'][0]['code'];
            $dt['PNCPROC'][$k]['display'] = $pr['resource']['code']['coding'][0]['display'];
            $dt['PNCPROC'][$k]['procedure'] = $pr['resource']['code'];
        }
       }

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


