<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\fhir\FHIR_resource_patient;
use DateTime;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FhirGetDataController extends Controller
{
    //
    public function index(){
        echo "fhir controller";
    }

    public function patient(){
        $token = env('FHIR_API_TOKEN');
        $server = env('FHIR_API_URL');
          $response = Http::withToken($token)->get($server.'Patient/');
         //print_r($response->json());
          $data = $response->json();
        $js = json_encode($data);
        $jcode = json_decode($js, true);

       // print_r($data);


        $i=0;
        foreach($data['entry'] as $key=>$result){
         if(!is_array($result)){
           $dt[$i][$key] = $result;
          // echo $key." ".$result;
         }else{
            foreach($result as $key1=>$result1){
                if(!is_array($result1)){
                    $dt[$i][$key1]=$result1;
                }else{


                  foreach($result1 as $key2=>$result2){
                    if(!is_array($result2)){
                        $dt[$i][$key2]=$result2;

                    }else{

                    if($key2=="meta"){
                        foreach($result2 as $metakey=>$metaresult){
                            $dt[$i][$key2."_".$metakey]=$metaresult;
                        }
                    }elseif($key2=="identifier"){

                        foreach($result2 as $idenKey=>$idenResult){
                             if($idenKey==0){
                                foreach($idenResult as $ik=>$ir){
                            $dt[$i][$key2."_".$ik."_".$idenKey]=$ir;
                                }

                        }elseif($idenKey==1){
                             foreach($idenResult as $ik=>$ir){
                            $dt[$i][$key2."_".$ik."_".$idenKey]=$ir;
                                }


                        }
                        }
                    }elseif($key2=="name"){

                        foreach($result2 as $nameKey=>$nameResult){

                            if($nameKey=="0"){
                                foreach($nameResult as $nameKey1=>$nameResult1){
                                    if($nameKey1!="given"){
                            $dt[$i][$key2."_".$nameKey1] = $nameResult1;
                                    }
                                }
                        }
                        }
                    }elseif($key2=="telecom"){
                        foreach($result2 as $telecomKey=>$telecomResult){
                            if($telecomKey==0){
                                foreach($telecomResult as $telecomKey1=>$telecomResult1){
                                    $dt[$i][$key2."_".$telecomKey1."_".$telecomKey]=$telecomResult1;
                                }
                            }else{
                                 foreach($telecomResult as $telecomKey1=>$telecomResult1){
                                    $dt[$i][$key2."_".$telecomKey1."_".$telecomKey]=$telecomResult1;
                                }
                            }
                        }
                    }elseif($key2=="address"){
                        foreach($result2 as $addressKey=>$addressResult){
                            foreach($addressResult as $addressKey1=>$addressResult1){
                                if(!is_array($addressResult1)){
                                $dt[$i][$key2."_".$addressKey1."_".$addressKey]=$addressResult1;
                                }else{
                                    foreach($addressResult1 as $addressKey2=>$addressResult2){
                                        if(!is_array($addressResult2)){
                                        $dt[$i][$key2."_".$addressKey1."_".$addressKey2."_".$addressKey]=$addressResult2;
                                        }else{
                                            foreach($addressResult2 as $addressKey3=>$addressResult3){
                                                if(!is_array($addressResult3)){
                                                $dt[$i][$key2."_".$addressKey1."_".$addressKey2."_".$addressKey3."_".$addressKey]=$addressResult3;
                                                }else{
                                                    foreach($addressResult3 as $addressKey4=>$addressResult4){
                                                        if(!is_array($addressResult4)){
                                                         $dt[$i][$key2."_".$addressKey1."_".$addressKey2."_".$addressKey3."_".$addressKey4."_".$addressKey]=$addressResult4;
                                                        }else{
                                                            foreach($addressResult4 as $addressKey5=>$addressResult5){
                                                                if(!is_array($addressKey5)){
                                                                    if($addressKey5=="url"){
                                                                 $dt[$i][$key2."_".$addressKey1."_".$addressKey2."_".$addressKey3."_".$addressKey4."_".$addressKey5."_".$addressKey]=$addressResult5;
                                                                    }elseif($addressKey5=="valueCoding"){
                                                                        foreach($addressResult5 as $valKey=>$valRes){
                                                                             $dt[$i][$key2."_".$addressKey1."_".$addressKey2."_".$addressKey3."_".$addressKey4."_".$addressKey5."_".$valKey."_".$addressKey]=$valRes;

                                                                        }

                                                                    }elseif($addressKey5=="valueCode"){
                                                                        $dt[$i][$key2."_".$addressKey1."_".$addressKey2."_".$addressKey3."_".$addressKey4."_".$addressKey5."_".$addressKey]=$addressResult5;

                                                                    }
                                                                }else{
                                                                    foreach($addressKey5 as $addressKey6=>$addressResult6){
                                                                        if(!is_array($addressResult6)){
                                                                        $dt[$i][$key2."_".$addressKey1."_".$addressKey2."_".$addressKey3."_".$addressKey4."_".$addressKey5."_".$addressKey6."_".$addressKey]=$addressResult6;
                                                                        }else{
                                                                            foreach($addressResult6 as $addressKey7=>$addressResult7){
                                                                                 $dt[$i][$key2."_".$addressKey1."_".$addressKey2."_".$addressKey3."_".$addressKey4."_".$addressKey5."_".$addressKey6."_".$addressKey7."_".$addressKey]=$addressResult7;
                                                                            }
                                                                        }

                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }
                        }
                    }




                    }
                  }
                }

            }
         }


       $i++; }
echo '<h1><b>hasil :</b></h1><pre>';
       print_r($dt);

       echo "</pre>";
//FHIR_resource_patient::insert($dt);
       foreach($dt as $key=>$val){
        $check = FHIR_resource_patient::where(array('id'=>$val['id'],'meta_versionId'=>$val['meta_versionId']))->get();

        //echo $key." ".$val['meta_versionId']."<br><pre>";

        echo "<pre>";
        print_r($check);
        foreach($check as $key=>$hasil){
            echo $key." ".$hasil;
        }
        echo "</pre>";

        foreach($val as $kn=>$nilai){
            if(!is_array($nilai)){
                $rd[$key][$kn]=$nilai;
            }

        }
       }

       echo "<pre>";
    //   print_r($rd);
       echo "</pre>";
//FHIR_resource_patient::insert($rd);

    }
}
