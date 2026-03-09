<?php

namespace App\Http\Controllers\fhir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\tb_fhir_resource;
use App\Models\tb_fhir_encounter;
use App\Models\tb_fhir_organization;
use DateTime;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GetFhirController extends Controller
{
    //

    public function index(){

        echo "home";
    }

    public function patient(){
        $token = env('FHIR_API_TOKEN');
        $server = env('FHIR_API_URL');

    $response = Http::withToken($token)->get($server.'Patient/');
         //print_r($response->json());
          $data = $response->json();
        $js = json_encode($data);
        $jcode = json_decode($js, true);

$ent=0;
        foreach($jcode['entry'] as $key => $val){
            $i=0;
            foreach($val as $k=>$n){





                if(is_array($n)){



//print_r($n);
                    $inx =0;
                    foreach($n as $kn=>$nv){






                        if($kn=='id') {
                            $dt[$ent]['id'] = $nv;

                        }elseif($kn=='gender'){
                            $dt[$ent]['gender'] = $nv;
                        }elseif($kn=='birthDate'){
                            $dt[$ent]['birthDate'] = $nv;
                        }elseif($kn=='meta'){
                            $dt[$ent]['lastupdated']= $nv['lastUpdated'];



                        }elseif($kn=="identifier"){
                            foreach($nv as $ikey=>$vid){
                                if($ikey==0){
                                    //if($vid=="value"){
                                    $dt[$ent]['nik'] = $vid['value'];
                                    $dt[$ent]['nik_ref'] = $vid['system'];

                                }else{
                                    $dt[$ent]['rmnumber'] = $vid['value'];
                                    $dt[$ent]['rm_ref'] = $vid['system'];
                                }



                            }
                          //  $dt[$ent] =$nv;
                        }elseif($kn=='name'){
                            foreach($nv as $idname=>$nval){
                                if(is_array($nval)){
                                    foreach($nval as $iname=>$valname){
                                      if($iname=="text"){
                                        $dt[$ent]["patient_name"]=$valname;
                                      }elseif($iname=='family'){
                                        $dt[$ent]['family_name']=$valname;
                                      }
                                    }
                                }


                                  //  print_r($nval);

                               // print_r($nval);

                            }
                        }elseif($kn=="telecom"){
                            foreach($nv as $telid=>$telval){
                                if($telid==0){
                                    $dt[$ent]['contact_phone_number']=$telval['value'];
                                    $dt[$ent]['contact_phone_type']=$telval['use'];
                                }else{
                                    $dt[$ent]['contact_email'] = $telval['value'];
                                }
                            }
                        }elseif($kn=='address'){
                            foreach($nv AS $addid=>$addval){
                            if(is_array($addval)){
                            foreach($addval as $addide=>$addvlu){
                            if(is_array($addvlu)){
                            foreach($addvlu as $addidex=>$addvlue){


                                if(is_array($addvlue)){
                                    foreach($addvlue as $addkey=>$addvalue){
                                        if(is_array($addvalue)){
                                            foreach($addvalue as $inkey=>$valind){
                                               foreach($valind as $extkey=>$extval){

                                              if(is_array($extval)){
                                                if($inkey==0){
                                                    $dt[$ent]['prov_code']=$extval['code'];
                                                    $dt[$ent]['prov_name']=$extval['display'];
                                                }elseif($inkey=='1'){
                                                    $dt[$ent]['city_code'] = $extval['code'];
                                                     $dt[$ent]['city_name'] = $extval['display'];
                                                }elseif($inkey=='2'){
                                                    $dt[$ent]['district_code'] = $extval['code'];
                                                     $dt[$ent]['district_name'] = $extval['display'];
                                                }
                                                foreach($extval as $kval=>$valk){
                                             //   echo $kval." ".$valk."<br> ";
                                               }
                                              }else{
                                              //  echo $inkey."<br>";
                                              }

                                               }
                                            }
                                        }

                                    if($addkey=='url'){
                                        $dt[$ent]['ref_administrative_code']=$addvalue;
                                    }


                                    }

                                }
                               }
                            }
                            }
                            }
                        }
                       // echo $kn."_2 : ".$nv."<br>";
                        }

                        }

$inx++;

                }else{

                    $dt[$ent]['fullUrl'] = $n;
                    //echo "<b>FullURL $ent : </b>".$n."<br>";


$i++;
                }

            }



$ent++;

        }

foreach($dt as $data){
    if(isset($data['rmnumber'], $data['rm_ref'])){
        $rm = $data['rmnumber'];
        $rm_ref = $data['rm_ref'];
    }else{
        $rm="";
        $rm_ref="";
    }

    if(isset($data['patient_name'])){
        $patient_name = $data['patient_name'];
    }else{
        $patient_name = "";
    }

    if(isset($data['family_name'])){
        $family_name = $data['family_name'];
    }else{
        $family_name = "";
    }

    if(isset($data['contact_phone_number'],$data['contact_email'])){
        $contact_phone_number = $data['contact_phone_number'];
        $contact_email = $data['contact_email'];

    }else{
         $contact_phone_number = "";
        $contact_email = "";

    }

    if(isset($data['ref_administrative_code'])){
        $ref_administrative_code = $data['ref_administrative_code'];
        $province_code = $data['prov_code'];
        $city_code = $data['city_code'];
        $district_code = $data['district_code'];
        $prov_name = $data['prov_name'];
        $city_name = $data['city_name'];
        $district_name = $data['district_name'];
    }else{
        $ref_administrative_code = "";
         $province_code = "";
        $city_code = "";
        $district_code = "";
          $prov_name = "";
        $city_name = "";
        $district_name = "";
    }

    if(isset($data['lastupdated'])){
        $datetime = $data['lastupdated'];
    $lastUpdate = date('Y-m-d H:i:s',strtotime($datetime));

    }else{
        $lastUpdate="";
    }

   tb_fhir_resource::create([
    'fhirid'=>Str::uuid(),
            'fullUrl'     => $data['fullUrl'],
            'patient_id'     => $data['id'],
            'patient_nik' => $data['nik'],
            'patient_nik_ref'=>$data['nik_ref'],
           'patient_rmn'=>$rm,

            'patient_rmn_ref'=>$rm_ref,
         'patient_name'=>$patient_name,
           'patient_familyname'=>$family_name,
           'patient_gender'=>$data['gender'],
           'patient_bdate'=>$data['birthDate'],
           'contact_phone'=>$contact_phone_number,
           'contact_email'=>$contact_email,
           'administratif_code_ref'=>$ref_administrative_code,
           'province_code'=>$province_code,
           'province_name'=>$prov_name,
           'city_code'=>$city_code,
           'city_name'=>$city_name,
           'district_code'=>$district_code,
           'district_name'=>$district_name,
           'fhir_last_update'=>$lastUpdate
        ]);
}


    }

    public function encounter(){
               $token = env('FHIR_API_TOKEN');
        $server = env('FHIR_API_URL');

    $response = Http::withToken($token)->get($server.'Encounter/');




    //   print_r($response->json());
  $data = $response->json();
        $js = json_encode($data);
        $jcode = json_decode($js, true);

$ent=0;
        foreach($jcode['entry'] as $key => $val){
            foreach($val as $key2=>$val2){
                if(is_array($val2)){
                    foreach($val2 as $key3=>$val3){
                        if(is_array($val3)){

                            if($key3=="identifier"){

                                foreach($val3 as $keyId=>$valId){
                                    if($keyId=="0"){
                                        $dt[$ent]['episodeOfcare'] = $valId['system'];
                                        $dt[$ent]['ANCSeries'] = $valId['value'];
                                    }
                                }
                            }elseif($key3=="statusHistory"){
                                foreach($val3 as $keySh=>$valSh){
                                    if($keySh=='0'){
                                        $dt[$ent]['historyStatus'] = $valSh['status'];
                                        $dt[$ent]['startPeriodHS'] = $valSh['period']['start'];
                                    }
                                }
                            }elseif($key3=="class"){
                                foreach($val3 as $keyCl=>$valCl){
                                    if($keyCl=='system'){
                                        $dt[$ent]['classRef'] = $valCl;
                                    }elseif($keyCl=='code'){
                                        $dt[$ent]['classCode'] = $valCl;
                                    }elseif($keyCl=='display'){
                                        $dt[$ent]['classDisplay'] = $valCl;
                                    }
                                }
                            }elseif($key3=='subject'){
                                foreach($val3 as $keySub=>$valSub){
                                    if($keySub=="reference"){
                                        $dt[$ent]['PatientRef']=$valSub;
                                    }elseif($keySub=="display"){
                                        $dt[$ent]['PatientName']=$valSub;
                                    }
                                }
                            }elseif($key3=="episodeOfCare"){
                                foreach($val3 as $KeyEOC=>$valEOC){
                                    if($KeyEOC=="0"){
                                        $dt[$ent]['EOCref']=$valEOC['reference'];
                                    }

                                }
                            }elseif($key3=="participant"){
                                foreach($val3 as $keyPart1=>$valPart1){
                                    foreach($valPart1 as $keyPart2=>$valPart2){

                                        if($keyPart2=="individual"){
                                            $dt[$ent]['PractitionerRef'] = $valPart2['reference'];
                                            $dt[$ent]['PractitionerName'] = $valPart2['display'];
                                        }
                                        foreach($valPart2 as $keyPart3=>$valPart3){
                                            if(is_array($valPart3)){
                                                foreach($valPart3 as $keyPart4=>$valPart4){
                                                    if(is_array($valPart4)){
                                                        foreach($valPart4 as $keyPart5=>$valPart5){
                                                            $dt[$ent]['ParticipantRef']=$valPart5['system'];
                                                            $dt[$ent]['ParticipantCode']=$valPart5['code'];
                                                            $dt[$ent]['participantDisplay']=$valPart5['display'];
                                                        }
                                                    }
                                                }

                                            }




                                        }
                                    }
                                }

                            }elseif($key3=="location"){
                                foreach($val3 as $keyLOC=>$valLOC){
                                  foreach($valLOC as $keyLOCa=>$valLOCa){
                                   foreach($valLOCa as $keyLOCb=>$valLOCb){
                                    if($keyLOCb=="reference"){
                                        $dt[$ent]['locationRef']=$valLOCb;

                                    }elseif($keyLOCb=="display"){
                                        $dt[$ent]['locationDisplay']=$valLOCb;
                                    }
                                   }
                                  }

                                }
                            }elseif($key3=="serviceProvider"){
                                foreach($val3 as $keySRV=>$valSRV){
                                    $dt[$ent]['serviceProviderRef'] = $valSRV;

                                }
                            }




                        }else{


                            if($key3=='id'){
                                $dt[$ent]['id']=$val3;
                            }elseif($key3=='status'){
                                $dt[$ent]['status']=$val3;
                            }
                        }
                    }

                }else{
                    $dt[$ent]['fullUrl']=$val2;
                }

            }
            $dt[$ent]['encounterID']=Str::uuid();

        $ent++;
        }

        foreach($dt as $data){

            //$entData=array("encounterID"=>Str::uuid());
            // array_push($entData,$valdt);
           foreach($data as $k=>$v){

           $ins[]=array($k=>$v);

           }

        tb_fhir_encounter::insert($dt);

        }




    }

    public function organization(){
         $token = env('FHIR_API_TOKEN');
        $server = env('FHIR_API_URL');

    $response = Http::withToken($token)->get($server.'Organization/');

     $data = $response->json();
        $js = json_encode($data);
        $jcode = json_decode($js, true);
//print_r($data);
$ent=0;
        foreach($jcode['entry'] as $key => $val){

            foreach($val as $key1=>$val1){
                 if(is_array($val1)){
                   foreach($val1 as $key2=>$val2){
                    if(is_array($val2)){

                       echo $key2." : ";
                        print_r($val2);
                        echo "<br>";

                        if($key2=="meta"){
                            foreach($val2 as $kMeta=>$vMeta){
                                $dt[$ent][$kMeta]=$vMeta;
                            }
                        }elseif($key2=="identifier"){
                            foreach($val2 as $kIdent=>$vIdent){
                                foreach($vIdent as $kIdent1=>$vIdent1){

                                    if($kIdent1=="use"){
                                        $dt[$ent]['IdentifierSystem'] = $vIdent1;
                                    }elseif($kIdent1=="value"){
                                    $dt[$ent]['IdentifierValue'] = $vIdent1;
                                    }elseif($kIdent1=="system"){
                                      //  $dt[$ent]['IdentifierRef'] = $vIdent1;
                                    }


                                }
                            }

                        }elseif($key2=="type"){
                            foreach($val2 as $kAct=>$vAct){
                               if(is_array($vAct)){
                               foreach($vAct as $kAct1=>$vAct1){
                                if(is_array($vAct1)){
                                   foreach($vAct1 as $kAct2=>$vAct2){
                                    if($kAct2=="0"){
                                        $dt[$ent]['typeCode'] = $vAct2['code'];
                                        $dt[$ent]['typeDisplay'] = $vAct2['display'];

                                    }

                                   }
                                }
                               }

                               }
                            }

                        }elseif($key2=="telecom"){
                            foreach($val2 as $kTel=>$vTel){
                                if($kTel==0){
                                    $dt[$ent]['telecomPhone'] = $vTel['value'];
                                }elseif($kTel==1){
                                    $dt[$ent]['telecomEmail'] = $vTel['value'];
                                }
                            }
                        }elseif($key2=="address"){
                            foreach($val2 as $kAdd=>$vAdd){


                                if($kAdd==0){

                                    foreach($vAdd as $kAdd1=>$vAdd1){





                                    if(is_array($vAdd1)){


                                    foreach($vAdd1 as $kAdd2=>$vAdd2){
                                        $dt[$ent]['addressInfo']=$vAdd2;
                                        if($kAdd2=="0"){
                                            if(is_array($vAdd2)){
                                                foreach($vAdd2 as $kAdd3=>$vAdd3){
                                                    if(is_array($vAdd3)){
                                                        foreach($vAdd3 as $kAdd4=>$vAdd4){
                                                            if($kAdd4=="0"){
                                                                $dt[$ent]['addressProvinceCode'] = $vAdd4['valueCode'];
                                                            }elseif($kAdd4=="1"){
                                                                $dt[$ent]['addressCityCode'] = $vAdd4['valueCode'];
                                                            }elseif($kAdd4=="2"){
                                                                $dt[$ent]['addressDistrictCode'] = $vAdd4['valueCode'];
                                                            }elseif($kAdd4=="3"){
                                                                $dt[$ent]['addressVillageCode'] = $vAdd4['valueCode'];
                                                            }

                                                        }

                                                    }else{
                                                        $dt[$ent]['addressRef']=$vAdd3;

                                                    }


                                                }



                                            }else{
                                           $dt[$ent]['addressRef'] ="";
                                           $dt[$ent]['addressProvinceCode'] ="";
                                           $dt[$ent]['addressCityCode'] ="";
                                           $dt[$ent]['addressDistrictCode'] ="";
                                           $dt[$ent]['addressVillageCode'] ="";
                                            }

                                        }
                                        // $dt[$ent]['addressRef'] = $vAdd2['url'];

                                    }
                                    }else{

                                        if($kAdd1=="use"){
                                        $dt[$ent]['addressUse'] = $vAdd1;
                                        }elseif($kAdd1=="postalCode"){
                                            $dt[$ent]['addressPostalCode'] = $vAdd1;
                                        }elseif($kAdd1=="country"){
                                            $dt[$ent]['addressCountry'] = $vAdd1;
                                        }
                                    }
                                    }

                                }

                                if(is_array($kAdd)){


                                }
                            }
                        }

                    }else{
                        $dt[$ent][$key2] = $val2;
                    }
                   }

                 }else{
                    $dt[$ent]['fullUrl'] = $val1;
                  //  echo $key1." - ".$val1."<br>";
                 }
            }
            $dt[$ent]['organizationID']=Str::uuid();
        $ent++;
        }

//print_r($dt);
   tb_fhir_organization::insert($dt);

foreach($dt as $k=>$v){
    foreach($v as $k1=>$v1){
        echo $k1."<br>";
    }
}

    }

    public function observation(){
        $token = env('FHIR_API_TOKEN');
        $server = env('FHIR_API_URL');

    $response = Http::withToken($token)->get($server.'Observation/');
    $data = $response->json();
        $js = json_encode($data);
        $jcode = json_decode($js, true);
//print_r($data);
$ent=0;
        foreach($jcode['entry'] as $key => $val){
            echo "<h1>".$key."</h1><br>";

            foreach($val as $key1=>$val1){
                if(is_array($val1)){

                    foreach($val1 as $key2=>$val2){
                        if(is_array($val2)){

                        echo "<b>";print_r($val2);echo "</b><br>";

                        foreach($val2 as $key3=>$val3){
                            if(is_array($val3)){
                                foreach($val3 as $key4=>$val4){
                                    if(is_array($val4)){

                                    foreach($val4 as $key5=>$val5){
                                        if(is_array($val5)){
                                            foreach($val5 as $key6=>$val6){
                                                if(is_array($val6)){

                                                }else{
 echo $key6." : $val6<br>";
 $dt[$ent][$key6]=$val6;
                                                }
                                            }

                                        }else{

                                            $dt[$ent][$key5]=$val5;
                                        }
                                    }


                                    }else{
                                       // echo $key4." : $val4<br>";
                                        $dt[$ent][$key4]=$val4;
                                    }
                                }

                            }else{
                                //echo $key3.": $val3<br>";
                                 $dt[$ent][$key3]=$val3;
                            }
                        }




                        }else{

                            $dt[$ent][$key2]=$val2;
                           // echo $key2." $val2 <br>";
                        }

                        if(!is_array($val2)){
                   // echo "<h2>$val2</h2><br>";
                }
                    }


                }else{

                    $dt[$ent][$key1] = $val1;
                }

                if(!is_array($val1)){
                  //  echo "<h2>$val1</h2><br>";
                }
                //echo "<h2>$key1</h2><br>";
            }
if(!is_array($val)){
                  //  echo "<h2>$val</h2><br>";
                }

        $ent++;
        }


echo "<p><b>";
        print_r($dt);
        echo "</b>";

    }
}
