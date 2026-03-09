<?php
namespace App\Http\Controllers;
use App\Models\tb_fhir_resource;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
//use Psy\Util\Str;

class GetdataController extends Controller
{

public function __invoke()
{
    return true;
}
public function index()
    {

         // Mengambil data dari API eksternal
        $token = env('FHIR_API_TOKEN');
        $server = env('FHIR_API_URL');

    $response = Http::withToken($token)->get($server.'Patient/');

        // Mengubah respons menjadi array
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

        //print_r($dt);

        //echo $jcode;

        // Mengirim data ke view
     //  return view('fhir-data', compact('dt'));
       // return view('fhir-data')->with('data',json_decode(json_encode($response->json()),true));
    }


public function encounter(){

     // Mengambil data dari API eksternal
        $token = env('FHIR_API_TOKEN');
        $server = env('FHIR_API_URL');

         $response = Http::withToken($token)->get($server.'Encounter/');

        // Mengubah respons menjadi array
        $data = $response->json();
        //$js = json_encode($data);
       // $jcode = json_decode($js, true);

        print_r($data);




}

    /**
     * store
     *
     * @param Request $request
     * @return void
     */

}
