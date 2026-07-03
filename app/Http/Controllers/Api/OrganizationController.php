<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrganizationController extends Controller
{

    //
    public function organization(Request $request)
{

 $token = env('FHIR_API_TOKEN');
        $server = env('FHIR_API_URL');
$search = $request->search;

    $response = Http::withToken($token)
        ->get($server.'/Organization',[
            'name'=>$search,
            '_count'=>30
        ]);

    $bundle = $response->json();

    $data = [];

    foreach($bundle['entry'] ?? [] as $entry){

        $org = $entry['resource'];

        $data[] = [

            'id'=>$org['id'],
            'name'=>$org['name']

        ];

    }

    return response()->json([
        'data'=>$data
    ]);
}
}
