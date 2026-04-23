<?php

use App\Http\Controllers\charts\ChartDistributionTargetController;
use App\Http\Controllers\DashController;
use App\Http\Controllers\fhir\GetFhirController;
use App\Http\Controllers\FhirGetDataController;
use App\Http\Controllers\Homepage;
use App\Http\Controllers\loginpage\LoginUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\rme\DataRmeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [AuthController::class, 'index']); // default ke login
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/send-otp', [OtpController::class, 'sendOtp']);
Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);


Route::middleware('auth')->group(function () {

    Route::get('/homepage', [AuthController::class, 'home'])->name('homepage');
    Route::get('/home', [DashController::class, 'index']);
    Route::get('/home/anc', [DashController::class, 'anc']);
    Route::get('/home/skrining', [DashController::class, 'skrining']);
    Route::get('/home/nifas', [DashController::class, 'nifas']);
    Route::get('/home/anak', [DashController::class, 'anak']);
    Route::get('/home/anakimd', [DashController::class, 'anakimd']);
    Route::get('/home/anakmk', [DashController::class, 'anakmk']);

    Route::get('/dsh', [ChartDistributionTargetController::class, 'index']);

    Route::get('/datarme', [DataRmeController::class, 'index']);
    Route::get('/datarme/search', [DataRmeController::class, 'searchpasien']);
    Route::post('/datarme/search', [DataRmeController::class, 'checkdata']);
    Route::get('/datarme/detail', [DataRmeController::class, 'dataPasien']);

});


/* Route::get('/',[LoginUserController::class,'index']);





//Route::resource('/Dashboard', \App\Http\Controllers\DashboardController::class);

Route::get('/fhir', [GetFhirController::class, 'index']);
Route::get('/fhir/visit', [GetFhirController::class, 'encounter']);
Route::get('/fhir/pasien', [GetFhirController::class, 'patient']);
Route::get('/fhir/observasi', [GetFhirController::class, 'observation']);
Route::get('/fhir/faskes', [GetFhirController::class, 'organization']);

Route::get('/newfhir', [FhirGetDataController::class, 'index']);
Route::get('/newfhir/pasien', [FhirGetDataController::class, 'patient']);
//Route::get('/homepage',[Homepage::class,'index']);
Route::get('/home',[DashController::class,'index']);
Route::get('/home/anc',[DashController::class,'anc']);
Route::get('/home/skrining',[DashController::class,'skrining']);
Route::get('/home/nifas',[DashController::class,'nifas']);
Route::get('/home/anak',[DashController::class,'anak']);
Route::get('/home/anakimd',[DashController::class,'anakimd']);
Route::get('/home/anakmk',[DashController::class,'anakmk']);

Route::get('/dsh',[ChartDistributionTargetController::class,'index']);
Route::get('/datarme',[DataRmeController::class,'index']);
Route::get('/datarme/search',[DataRmeController::class,'searchpasien']);
Route::post('/datarme/search',[DataRmeController::class,'checkdata']);
Route::get('/datarme/detail',[DataRmeController::class,'dataPasien']);

Route::get('/auth/login',[LoginUserController::class,'index']);
Route::post('/auth/usercheck',[LoginUserController::class,'usercheck'])->name('auth.usercheck');


Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// halaman setelah login
Route::get('/homepage', [Homepage::class, 'index'])
    ->middleware('auth')
    ->name('homepage');

/*
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
*/

