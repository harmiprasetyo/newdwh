<?php

namespace App\Http\Controllers\rme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class DataRmeController extends Controller
{
    //
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
        $response = Http::withToken($token)->get($server.'Patient?identifier=https://fhir.kemkes.go.id/id/nik|'.$nik);
        $data = $response->json();

    $jdid['total'] = $data['total'];
    $jdid['status']="success";
    $jdid['nik'] = $nik;
    echo json_encode($jdid);


    }

    public function dataPasien(Request $request){
        $token = env('FHIR_API_TOKEN');
        $server = env('FHIR_API_URL');
        $nik = $request->nik;
        $id = $request->idencounter;
            $response = Http::withToken($token)->get($server.'Patient?identifier=https://fhir.kemkes.go.id/id/nik|'.$nik);
            $data = $response->json();
        $dt['INFO']['total'] = $data['total'];
        $dt['INFO']['id'] = $data['id'];
        foreach($data['entry'] as $key=>$hasil){
      // echo $key."<br>";
        $dt['PID']['fullUrl'] = $hasil['fullUrl'];
        $dt['PID']['id'] = $hasil['resource']['id'];
        $dt['PID']['versionId'] = $hasil['resource']['meta']['versionId'];
        $dt['PID']['lastUpdate'] = $hasil['resource']['meta']['lastUpdated'];
        $dt['PID']['nik'] = $hasil['resource']['identifier'][0]['value'];
        $dt['PID']['norm'] = $hasil['resource']['identifier'][1]['value'];
        $dt['PID']['nama'] = $hasil['resource']['name'][0]['text'];
        $dt['PID']['family'] = $hasil['resource']['name'][0]['family'];
        if($hasil['resource']['telecom'][0]['system']=='phone'){
        $dt['PID']['phone'] = $hasil['resource']['telecom'][0]['value'];
        }else{
            $dt['PID']['phone']="";
        }

        $dt['PID']['gender'] = $hasil['resource']['gender'];
        $dt['PID']['birthdate'] = $hasil['resource']['birthDate'];
        $dt['PID']['province_code'] = $hasil['resource']['address'][0]['extension'][0]['extension'][0]['valueCoding']['code'];
        $dt['PID']['province_name'] = $hasil['resource']['address'][0]['extension'][0]['extension'][0]['valueCoding']['display'];
        $dt['PID']['city_code'] = $hasil['resource']['address'][0]['extension'][0]['extension'][1]['valueCoding']['code'];
        $dt['PID']['city_name'] = $hasil['resource']['address'][0]['extension'][0]['extension'][1]['valueCoding']['display'];

         $dt['PID']['subdistrict_code'] = $hasil['resource']['address'][0]['extension'][0]['extension'][2]['valueCoding']['code'];
         $dt['PID']['subdistrict_name'] = $hasil['resource']['address'][0]['extension'][0]['extension'][2]['valueCoding']['display'];
       }








    $ecounter = Http::withToken($token)->get($server.'Encounter/'.$id);
    $encounterResult = $ecounter->json();


     $date = Carbon::parse($encounterResult['period']['start']);
      $dt['ENC']['tglKunjungan'] = $date->format("d M Y");

       $faskes = Http::withToken($token)->get($server.$encounterResult['serviceProvider']['reference']);
         $resFaskes = $faskes->json();
         $dt['ENC']['serviceProvider_code'] = $resFaskes['identifier'][0]['value'];
         $dt['ENC']['serviceProvider_name'] = $resFaskes['name'];

    foreach($encounterResult as $ky=>$encR){



      }


        $observ = Http::withToken($token)->get($server."Observation?patient=".$dt['PID']['id']."&encounter=".$id);
        $obserResult = $observ->json();



        $dt['INFO']['total']=$obserResult['total'];

        foreach($obserResult['entry'] as $k=>$obs){
        //echo $obs['resource']['category'][0]['coding'][0]['code']."<br>";

        if($obs['resource']['category'][0]['coding'][0]['code']=='vital-signs'){
            $dt['OBS'][$k]['param_code'] = $obs['resource']['code']['coding'][0]['code'];
            $dt['OBS'][$k]['param_name'] = $obs['resource']['code']['coding'][0]['display'];
             $dt['OBS'][$k]['valueQty'] = $obs['resource']['valueQuantity']['value'];
             $dt['OBS'][$k]['valueUnit'] = $obs['resource']['valueQuantity']['unit'];
              if($obs['resource']['code']['coding'][0]['code']=='8480-6'){
                $dt['sistole'] = $obs['resource']['valueQuantity']['value'];
              }

               if($obs['resource']['code']['coding'][0]['code']=='8462-4'){
                $dt['diastole'] = $obs['resource']['valueQuantity']['value']." ".$obs['resource']['valueQuantity']['unit'];;
              }

                if($obs['resource']['code']['coding'][0]['code']=='8302-2'){
                $dt['anc_body_heigh']=$obs['resource']['valueQuantity']['value']." ".$obs['resource']['valueQuantity']['unit'];
            }


        }elseif($obs['resource']['category'][0]['coding'][0]['code']=='survey'){
            if($obs['resource']['code']['coding'][0]['code']=='11996-6'){
                $dt['gravida']=$obs['resource']['valueInteger'];

            }
            if($obs['resource']['code']['coding'][0]['code']=='11977-6'){
                $dt['parity']=$obs['resource']['valueInteger'];

            }

             if($obs['resource']['code']['coding'][0]['code']=='69043-8'){
                $dt['abortions']=$obs['resource']['valueInteger'];
            }

            if(isset($obs['resource']['code']['coding'][0]['code']) && $obs['resource']['code']['coding'][0]['code']=='11778-8'){
                $dt['anc_hpl']=$obs['resource']['valueDateTime'];
            }else{
                $dt['anc_hpl']=" - ";
                }


            if(isset($obs['resource']['code']['coding'][0]['code']) && $obs['resource']['code']['coding'][0]['code']=='OC000001'){
                $dt['anc_jarak_hamil']=$obs['resource']['valueQuantity']['value'];

            }else{
                $dt['anc_jarak_hamil']=" - ";
            }




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

        if(isset($obs['resource']['code']['coding'][0]['code']))
{

             if($obs['resource']['code']['coding'][0]['code']=='284473002'){
                $dt['anc_lila']=$obs['resource']['valueQuantity']['value']." ".$obs['resource']['valueQuantity']['unit'];
            }

        }

        }


        }


          $condition = Http::withToken($token)->get($server."Condition?patient=".$dt['PID']['id']."&encounter=".$id);
            $kondisi = $condition->json();

            foreach($kondisi['entry'] as $kx=>$nres){



            }


//KOHORT


    $eCare = Http::withToken($token)->get($server.'EpisodeOfCare?patient='.$dt['PID']['id']);
       $eRes = $eCare->json();
       $year = date("Y");



       if($eRes['total']>0){


       $dt['label']['bln'] = array("01"=>"Jan","02"=>"Feb","03"=>"Mar","04"=>"Apr","05"=>"Mei","06"=>"Jun","07"=>"Jul","08"=>"Agt","09"=>"Sep","10"=>"Okt","11"=>"Nop","12"=>"Des");

       foreach($eRes['entry'] as $key=>$nilai){
        if(is_array($nilai)){
            foreach($nilai as $k1=>$val1){

            if(isset($val1['id'])){
                $visits = Http::withToken($token)->get($server.'/Encounter?patient='.$dt['PID']['id'].'&episode-of-care='.$val1['id']);
                $vis = $visits->json();
                if($vis['total']>0){
                foreach($vis['entry'] as $kvis=>$nvis){
                    $dt['KOHORT'][$key]['anc_jenis_kunjungan'] = $nvis['resource']['identifier'][0]['value'];

                 $ids = $nvis['resource']['id'];

        $KHobserv= Http::withToken($token)->get($server."Observation?patient=".$dt['PID']['id']."&encounter=".$ids);
        $KOB = $KHobserv->json();
       if($KOB['total']>0){
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

                }
                }

               // $dt['KOHORT'][$key]['anc_kunjungan']=$val1['period']['start'];
            }
        }


        }


       }




        return view('rme.detailpasien',["dt"=>$dt]);





    }


    public function pasiensearch(){

    }


    public function searchpasien(){

        $token = env('FHIR_API_TOKEN');
        $server = env('FHIR_API_URL');
        $nik = $_GET['nik'];

    $response = Http::withToken($token)->get($server.'Patient?identifier=https://fhir.kemkes.go.id/id/nik|'.$nik);

          $data = $response->json();
          $dt['INFO']['total'] = $data['total'];
          $dt['INFO']['id'] = $data['id'];

        if($data['total']>0){
          $i=0;
       foreach($data['entry'] as $key=>$hasil){


      // echo $key."<br>";
        $dt['PID']['fullUrl'] = $hasil['fullUrl'];
        $dt['PID']['id'] = $hasil['resource']['id'];
        $dt['PID']['versionId'] = $hasil['resource']['meta']['versionId'];
        $dt['PID']['lastUpdate'] = $hasil['resource']['meta']['lastUpdated'];
        $dt['PID']['nik'] = $hasil['resource']['identifier'][0]['value'];
        $dt['PID']['norm'] = $hasil['resource']['identifier'][1]['value'];
        $dt['PID']['nama'] = $hasil['resource']['name'][0]['text'];
        $dt['PID']['family'] = $hasil['resource']['name'][0]['family'];
        if($hasil['resource']['telecom'][0]['system']=='phone'){
        $dt['PID']['phone'] = $hasil['resource']['telecom'][0]['value'];
        }else{
            $dt['PID']['phone']="";
        }

        $dt['PID']['gender'] = $hasil['resource']['gender'];
        $dt['PID']['birthdate'] = $hasil['resource']['birthDate'];
        $dt['PID']['province_code'] = $hasil['resource']['address'][0]['extension'][0]['extension'][0]['valueCoding']['code'];
        $dt['PID']['province_name'] = $hasil['resource']['address'][0]['extension'][0]['extension'][0]['valueCoding']['display'];
        $dt['PID']['city_code'] = $hasil['resource']['address'][0]['extension'][0]['extension'][1]['valueCoding']['code'];
        $dt['PID']['city_name'] = $hasil['resource']['address'][0]['extension'][0]['extension'][1]['valueCoding']['display'];

         $dt['PID']['subdistrict_code'] = $hasil['resource']['address'][0]['extension'][0]['extension'][2]['valueCoding']['code'];
         $dt['PID']['subdistrict_name'] = $hasil['resource']['address'][0]['extension'][0]['extension'][2]['valueCoding']['display'];
       }






       $ecounter = Http::withToken($token)->get($server.'Encounter?patient='.$dt['PID']['id']);
       $encounterResult = $ecounter->json();
       if($encounterResult['total']>0){


         $n=0;
         foreach($encounterResult['entry'] as $ky=>$encR){

         $dt['ENC'][$n]['fullUrl'] = $encR['fullUrl'];
         $dt['ENC'][$n]['id'] = $encR['resource']['id'];
         $dt['ENC'][$n]['versionId'] = $encR['resource']['meta']['versionId'];


         $dt['ENC'][$n]['lastUpdated'] = $encR['resource']['meta']['lastUpdated'];


         $dt['ENC'][$n]['codeRef'] = $encR['resource']['identifier'][0]['system'];
         $dt['ENC'][$n]['visitCategori'] = $encR['resource']['identifier'][0]['value'];
         $dt['ENC'][$n]['status_kedatangan'] = $encR['resource']['status'];
        $dt['ENC'][$n]['tipe_kunjungan'] = $encR['resource']['class']['code'];
        $dt['ENC'][$n]['tipe_kunjungan_display'] = $encR['resource']['class']['display'];
         $date = Carbon::parse($encR['resource']['period']['start']);
        $dt['ENC'][$n]['tglKunjungan'] = $date->format("d M Y");


          $dt['ENC'][$n]['start_periode'] = $encR['resource']['period']['start'];
          $dt['ENC'][$n]['jenis_perawatan'] = $encR['resource']['class']['display'];
          $dt['ENC'][$n]['patient_id'] = $encR['resource']['subject']['reference'];
          $dt['ENC'][$n]['patient_name'] = $encR['resource']['subject']['display'];

          if(isset($encR['resource']['episodeOfCare'][0]['reference'])){
       $dt['ENC'][$n]['episodeOfCare'] = $encR['resource']['episodeOfCare'][0]['reference'];
       $eoc = Http::withToken($token)->get($server.$dt['ENC'][$n]['episodeOfCare']);
       $eocResult = $eoc->json();

        $dt['ENC'][$n]['jeniskunjungan'] = $eocResult['type'][0]['coding'][0]['code'];
     $dt['ENC'][$n]['jeniskunjungan_name'] = $eocResult['type'][0]['coding'][0]['display'];


          }else{
             $dt['ENC'][$n]['jeniskunjungan'] ="-";
             $dt['ENC'][$n]['jeniskunjungan_name'] ="-";
          }


       $dt['ENC'][$n]['practitioner'] = $encR['resource']['participant'][0]['individual']['reference'];
       $practitioner =  Http::withToken($token)->get($server.$dt['ENC'][$n]['practitioner']);
       $practResult = $practitioner->json();
       $dt['ENC'][$n]['practitioner_number']=$practResult['identifier'][0]['value'];
        $dt['ENC'][$n]['practitioner_nik']=$practResult['identifier'][1]['value'];
        $dt['ENC'][$n]['practitioner_name']=$practResult['name'][0]['text'];
         $dt['ENC'][$n]['practitioner_strkki']=$practResult['qualification'][0]['identifier'][0]['value'];

         $dt['ENC'][$n]['unit_poli'] = $encR['resource']['location'][0]['location']['display'];
         $dt['ENC'][$n]['unit_poli_id'] = $encR['resource']['location'][0]['location']['reference'];

         $dt['ENC'][$n]['serviceProvider_id'] = $encR['resource']['serviceProvider']['reference'];

         $faskes = Http::withToken($token)->get($server.$dt['ENC'][$n]['serviceProvider_id']);
         $resFaskes = $faskes->json();
         $dt['ENC'][$n]['serviceProvider_code'] = $resFaskes['identifier'][0]['value'];
         $dt['ENC'][$n]['serviceProvider_name'] = $resFaskes['name'];

        $anamnese = Http::withToken($token)->get($server."Condition?encounter=".$dt['ENC'][$n]['id']);
         $resAnamnese = $anamnese->json();
         if($resAnamnese['total']>0){
            for($i=0;$i<$resAnamnese['total'];$i++){
                $dt['ANAMNESE'][$i]['keluhan_kode'] = $resAnamnese['entry'][$i]['resource']['code']['coding'][0]['code'];
                 $dt['ANAMNESE'][$i]['keluhan_display'] = $resAnamnese['entry'][$i]['resource']['code']['coding'][0]['display'];
                 // $dt['ANAMNESE'][$i]['note'] = $resAnamnese['entry'][$i]['resource']['note'][0]['text'];
            }

         }

         $observ = Http::withToken($token)->get($server."Observation?patient=".$dt['PID']['id']."&encounter=".$dt['ENC'][$n]['id']);
         $obserResult = $observ->json();




         $n++;
         }

       }




        }else{
            $dt['message']="Data Tidak ditemukan";
        }


  //  echo "<pre>";
  // print_r($dt);
   // echo "</pre>";
 return view('rme.datapasien',["dt"=>$dt]);

}


}


