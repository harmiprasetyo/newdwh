<?php

namespace App\Http\Controllers\loginpage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginUserController extends Controller
{
    //

    public function index(){
        return view('login/loginform');

    }

    public function usercheck(Request $request){

    $request->validate(
        [
            "username"=>'required',
            "password"=>'required'
        ]

    );
    if(Auth::attempt(['username' => $request->username, 'password' => $request->password])){

    echo json_encode(array("success"=>true,"message"=>"User ditemukan"));


    };



    }
}
