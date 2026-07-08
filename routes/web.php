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
use App\Http\Controllers\master\IndonesiaController;
use App\Http\Controllers\UserWebController;
use App\Http\Controllers\Lplpo\LplpoController;
use App\Http\Controllers\Lplpo\DashboardController;
use App\Http\Controllers\master\LabelLplpoController;
use App\Http\Controllers\master\MasterWebObatController;
use App\Http\Controllers\Lplpo\LplpoFinalController;
use App\Http\Controllers\Dashboard\FhirImportController;
use App\Http\Controllers\Dashboard\DashboardPageController;
use App\Http\Controllers\Lplpo\BaselineFormController;
use App\Http\Controllers\Lplpo\LplpoBekasiController;


/*
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/bekasi/lplpo', [LplpoBekasiController::class, 'index']);
Route::get('/bekasi/lplpo/api/data', [LplpoBekasiController::class, 'data']);


Route::get('/', [AuthController::class, 'index']); // default ke login
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::get('/ssologin', [AuthController::class, 'loginsso'])->name('ssologin');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::post('/send-otp', [OtpController::class, 'sendOtp']);
Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);


Route::middleware('auth')->group(function () {
    Route::post('/lplpo/bulk-update-pemberian', [LplpoController::class, 'bulkUpdatePemberian']);
    Route::get('lplpo/baseline-form', [BaselineFormController::class, 'index'])->name('lplpo.baseline_form');

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

    Route::get('/propinsi', [IndonesiaController::class, 'provinces'])->name('provinces');
    Route::get('/kabkota', [IndonesiaController::class, 'cities'])->name('cities');
    Route::get('/kecamatan', [IndonesiaController::class, 'districts'])->name('districts');
    Route::get('/kelurahan', [IndonesiaController::class, 'villages'])->name('villages');

    Route::prefix('lplpo')->group(function(){

    Route::get('/dashboard', [LplpoController::class, 'index']);
    Route::get('/upload', [LplpoController::class, 'uploadPage']);
    Route::post('/import', [LplpoController::class, 'import']);
    Route::get('/data', [LplpoController::class, 'data']);
     Route::get('/dataview', [LplpoController::class, 'dataview']);


Route::prefix('faskes')->group(function () {
Route::get('/dashboard', [DashboardController::class, 'dashboard']);
Route::get('/dashboard-data', [DashboardController::class, 'dashboardData']);
});
Route::prefix('dinkes')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard-data', [DashboardController::class, 'data']);
    Route::get('/pivot', [DashboardController::class, 'pivot']);
Route::get('/pivot-data', [DashboardController::class, 'pivotData']);
Route::get('/pivot-chart', [DashboardController::class, 'pivotChart']);

});



    });







Route::prefix('adminpanel')->group(function () {

    // Dashboard
    Route::get('/', fn() => view('admin.adminhome'));
    Route::get('/usergroups', fn() => view('admin.groups'));

     Route::get('/faskes', fn() => view('admin.masterfaskes'));
     Route::get('/typefaskes', fn() => view('admin.listtypefaskes'));

    // USER
    Route::prefix('users')->group(function () {
        Route::get('/', [UserWebController::class,'index']);
        Route::get('/list', [UserWebController::class,'data']);
        Route::get('/{id}', [UserWebController::class,'show']);
        Route::post('/', [UserWebController::class,'store']);
        Route::post('/{id}', [UserWebController::class,'update']);
        Route::delete('/{id}', [UserWebController::class,'destroy']);

    });

    Route::prefix('wilayah')->group(function () {
         Route::get('/listpropinsi', [IndonesiaController::class, 'listprovince'])->name('listprovince');
         Route::get('/mapprovince', [IndonesiaController::class, 'mapprovince'])->name('mapprovince');
         Route::get('/listkota', [IndonesiaController::class, 'listkota'])->name('listkota');
        Route::get('/listkecamatan', [IndonesiaController::class, 'listkecamatan'])->name('listkecamatan');
        Route::get('/listdesa', [IndonesiaController::class, 'listdesa'])->name('listdesa');
    });

    // GROUP USER

    //Route::get('/groups', [UserWebController::class,'groups']);

    // WILAYAH (VIEW ONLY)
    Route::get('/provinsi', fn() => view('admin.propinsi'));
    Route::get('/kota', fn() => view('admin.kota'));
    Route::get('/kecamatan', fn() => view('admin.kecamatan'));
    Route::get('/desa', fn() => view('admin.desa'));

    Route::prefix('geojson')->group(function () {
    Route::get('/provinsi', [IndonesiaController::class, 'geojsonProvinsi']);
    });




   Route::get('/label-lplpo', [LabelLplpoController::class, 'index']);
Route::post('/label-lplpo', [LabelLplpoController::class, 'store']);

// routes/web.php
Route::get('/masterobat', [MasterWebObatController::class, 'index']);
// AJAX kabupaten by kode provinsi


});





Route::prefix('lplpo-final')->group(function () {
    Route::get('/', [LplpoFinalController::class, 'index'])->name('lplpo.final.index');
    Route::get('/data', [LplpoFinalController::class, 'data'])->name('lplpo.final.data');

    Route::get('/detail/{header_id}', [LplpoFinalController::class, 'detail'])->name('lplpo.final.detail');
    Route::get('/detail-data/{header_id}', [LplpoFinalController::class, 'detailData'])->name('lplpo.final.detail.data');
});


});


Route::get('/get-kabupaten/{province_code}', [LabelLplpoController::class, 'getKabupaten']);
Route::prefix('dashboard')->group(function () {
  Route::get('/', [DashboardPageController::class, 'index']);
Route::get('/realtime', [DashboardPageController::class, 'realtime']);
});
Route::get('/dashboard-lplpo', fn() => view('dashboard.lplpo'));


