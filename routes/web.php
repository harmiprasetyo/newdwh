<?php

use App\Http\Controllers\charts\ChartDistributionTargetController;
use App\Http\Controllers\DashController;
use App\Http\Controllers\fhir\GetFhirController;
use App\Http\Controllers\FhirGetDataController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\rme\DataRmeController;


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

Route::get('/',[DashController::class,'index']);





//Route::resource('/Dashboard', \App\Http\Controllers\DashboardController::class);

Route::get('/fhir', [GetFhirController::class, 'index']);
Route::get('/fhir/visit', [GetFhirController::class, 'encounter']);
Route::get('/fhir/pasien', [GetFhirController::class, 'patient']);
Route::get('/fhir/observasi', [GetFhirController::class, 'observation']);
Route::get('/fhir/faskes', [GetFhirController::class, 'organization']);

Route::get('/newfhir', [FhirGetDataController::class, 'index']);
Route::get('/newfhir/pasien', [FhirGetDataController::class, 'patient']);

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

