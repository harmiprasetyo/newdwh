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
use App\Http\Controllers\NewLplpo\LplpoController;
use App\Http\Controllers\Lplpo\DashboardController as OldDashboard;
use App\Http\Controllers\master\LabelLplpoController;
use App\Http\Controllers\master\MasterWebObatController;
use App\Http\Controllers\Lplpo\LplpoFinalController;
use App\Http\Controllers\Dashboard\FhirImportController;
use App\Http\Controllers\Dashboard\DashboardPageController;
use App\Http\Controllers\Lplpo\BaselineFormController;
//use App\Http\Controllers\Lplpo\LplpoBekasiController;
use App\Http\Controllers\NewLplpo\LplpoItemController;
use App\Http\Controllers\NewLplpo\DashboardController;
  use App\Http\Controllers\NewLplpo\MasterObatController;
  use App\Http\Controllers\NewLplpo\LplpoVerificationController;
  use App\Http\Controllers\NewLplpo\LplpoPemberianController;
use App\Http\Controllers\NewLplpo\LplpoArsipController;
 use App\Http\Controllers\AdminPanel\PosyanduController;
 use App\Http\Controllers\AdminPanel\Master\TargetSasaranController;
 use App\Http\Controllers\AdminPanel\WilayahKerja\WilayahKerjaPosyanduController;
 use App\Http\Controllers\NewLplpo\StokEsensialController;
 use App\Http\Controllers\NewLplpo\ProgramController;
 use App\Http\Controllers\NewLplpo\MasterDataObatController;
 use App\Http\Controllers\NewLplpo\LplpoRekapController;
 use App\Http\Controllers\NewLplpo\KunjunganController;
 use App\Http\Controllers\BekasiLplpo\LplpoReportController;
use App\Http\Controllers\NewLplpo\LplpoBekasiController;
use App\Http\Controllers\AdminPanel\ActivityLogController;
use App\Http\Controllers\AdminPanel\UserPanel\UserGroupController;
use App\Http\Controllers\AdminPanel\UserPanel\UserRoleController;
use App\Http\Controllers\AdminPanel\UserPanel\UserAppController;
use App\Http\Controllers\AdminPanel\WilayahKerja\WilayahKerjaPuskesmasController;
use App\Http\Controllers\AdminPanel\Master\MasterFaskesController;

use App\Http\Controllers\TestMailController;




/*
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Route::get('/bekasi/lplpo', [LplpoBekasiController::class, 'index']);
//Route::get('/bekasi/lplpo/api/data', [LplpoBekasiController::class, 'data']);
Route::get(
    '/test-mail',
    [TestMailController::class, 'send']
);

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
    Route::prefix('adminpanel')->group(function () {// Dashboard




    Route::prefix('activity-log')
    ->name('activity-log.')
    ->group(function () {

        Route::get(
            '/',
            [ActivityLogController::class, 'index']
        )->name('index');


        Route::get(
            '/datatable',
            [ActivityLogController::class, 'datatable']
        )->name('datatable');

    });


    Route::get('/', fn() => view('admin.adminhome'));
    Route::get('/usergroups', fn() => view('admin.groups'));
    Route::get('/faskes', fn() => view('admin.masterfaskes'));
    Route::get('/typefaskes', fn() => view('admin.listtypefaskes'));
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
    Route::get('/provinsi', fn() => view('admin.propinsi'));
    Route::get('/kota', fn() => view('admin.kota'));
    Route::get('/kecamatan', fn() => view('admin.kecamatan'));
    Route::get('/desa', fn() => view('admin.desa'));
    Route::prefix('geojson')->group(function () {
    Route::get('/provinsi', [IndonesiaController::class, 'geojsonProvinsi']);
    });
    Route::get('/label-lplpo', [LabelLplpoController::class, 'index']);
    Route::post('/label-lplpo', [LabelLplpoController::class, 'store']);
    Route::get('/masterobat', [MasterWebObatController::class, 'index']);
    });
    Route::prefix('lplpo-final')->group(function () {
    Route::get('/', [LplpoFinalController::class, 'index'])->name('lplpo.final.index');
    Route::get('/data', [LplpoFinalController::class, 'data'])->name('lplpo.final.data');

    Route::get('/detail/{header_id}', [LplpoFinalController::class, 'detail'])->name('lplpo.final.detail');
    Route::get('/detail-data/{header_id}', [LplpoFinalController::class, 'detailData'])->name('lplpo.final.detail.data');
    });







Route::prefix('adminpanel')
    ->group(function () {

        Route::prefix('master/faskes')
            ->name('adminpanel.master.faskes.')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | HALAMAN
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/',
                    [MasterFaskesController::class, 'index']
                )->name('index');


                /*
                |--------------------------------------------------------------------------
                | DATATABLE
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/datatable',
                    [MasterFaskesController::class, 'datatable']
                )->name('datatable');


                /*
                |--------------------------------------------------------------------------
                | COMBO MASTER
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/master/types',
                    [MasterFaskesController::class, 'types']
                )->name('types');

                Route::get(
                    '/master/provinces',
                    [MasterFaskesController::class, 'provinces']
                )->name('provinces');

                Route::get(
                    '/master/cities',
                    [MasterFaskesController::class, 'cities']
                )->name('cities');

                Route::get(
                    '/master/districts',
                    [MasterFaskesController::class, 'districts']
                )->name('districts');

                Route::get(
                    '/master/facilities',
                    [MasterFaskesController::class, 'facilities']
                )->name('facilities');

                Route::get(
                    '/master/hierarchy',
                    [MasterFaskesController::class, 'hierarchy']
                )->name('hierarchy');


                /*
                |--------------------------------------------------------------------------
                | CREATE
                |--------------------------------------------------------------------------
                */

                Route::post(
                    '/',
                    [MasterFaskesController::class, 'store']
                )->name('store');


                /*
                |--------------------------------------------------------------------------
                | DETAIL
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/{id}',
                    [MasterFaskesController::class, 'show']
                )->name('show');


                /*
                |--------------------------------------------------------------------------
                | UPDATE
                |--------------------------------------------------------------------------
                */

                Route::put(
                    '/{id}',
                    [MasterFaskesController::class, 'update']
                )->name('update');


                /*
                |--------------------------------------------------------------------------
                | DELETE
                |--------------------------------------------------------------------------
                */

                Route::delete(
                    '/{id}',
                    [MasterFaskesController::class, 'destroy']
                )->name('destroy');

            });

    });

Route::prefix('adminpanel')
    ->name('adminpanel.')
    ->group(function () {

        Route::prefix('wilayahkerja/puskesmas')
            ->name('wilayahkerja.puskesmas.')
            ->middleware(['web', 'auth'])
            ->group(function () {

                Route::get(
                    '/',
                    [WilayahKerjaPuskesmasController::class, 'index']
                )->name('index');

                Route::get(
                    '/datatable',
                    [WilayahKerjaPuskesmasController::class, 'datatable']
                )->name('datatable');

                 Route::get(
            '/faskes',
            [WilayahKerjaPuskesmasController::class, 'faskes']
        )->name('faskes');

        Route::get(
            '/desa-by-faskes',
            [WilayahKerjaPuskesmasController::class, 'desaByFaskes']
        )->name('desaByFaskes');


                Route::get(
                    '/{id}',
                    [WilayahKerjaPuskesmasController::class, 'show']
                )->name('show');

                Route::post(
                    '/',
                    [WilayahKerjaPuskesmasController::class, 'store']
                )->name('store');

                Route::put(
                    '/{id}',
                    [WilayahKerjaPuskesmasController::class, 'update']
                )->name('update');

                Route::delete(
                    '/{id}',
                    [WilayahKerjaPuskesmasController::class, 'destroy']
                )->name('destroy');

            });
    });

Route::prefix('adminpanel')->group(function(){

    Route::get(
        '/posyandu',
        [PosyanduController::class,'index']
    );

    Route::post(
        '/posyandu/store',
        [PosyanduController::class,'store']
    );



    Route::get(
        '/faskes/list',
        [PosyanduController::class,'faskes']
    );


    Route::prefix('master')
    ->name('master.')
    ->group(function(){

    Route::get(
        'target-sasaran/datatable',
        [TargetSasaranController::class,'datatable']
    )->name('target-sasaran.datatable');

    Route::resource(
        'target-sasaran',
        TargetSasaranController::class
    );

});

});



Route::prefix('adminpanel')
    ->name('adminpanel.')
    ->middleware('auth')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | USER PANEL
        |--------------------------------------------------------------------------
        */

         Route::prefix('userpanel')
            ->name('userpanel.')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | USERS
                |--------------------------------------------------------------------------
                */

                Route::prefix('users')
                    ->name('users.')
                    ->group(function () {

                        Route::get(
                            '/',
                            [UserAppController::class, 'index']
                        )->name('index');

                        Route::get(
                            '/datatable',
                            [UserAppController::class, 'datatable']
                        )->name('datatable');

                        Route::get(
                            '/faskes',
                            [UserAppController::class, 'faskes']
                        )->name('faskes');

                        Route::get(
                            '/groups',
                            [UserAppController::class, 'groups']
                        )->name('groups');

                        Route::get(
                            '/{id}',
                            [UserAppController::class, 'show']
                        )->name('show');

                        Route::post(
                            '/',
                            [UserAppController::class, 'store']
                        )->name('store');

                        Route::put(
                            '/{id}',
                            [UserAppController::class, 'update']
                        )->name('update');

                        Route::delete(
                            '/{id}',
                            [UserAppController::class, 'destroy']
                        )->name('destroy');

                    });


                /*
                |--------------------------------------------------------------------------
                | ROLES
                |--------------------------------------------------------------------------
                */

                Route::prefix('roles')
                    ->name('roles.')
                    ->group(function () {

                        Route::get(
                            '/',
                            [UserRoleController::class, 'index']
                        )->name('index');

                        Route::get(
                            '/datatable',
                            [UserRoleController::class, 'datatable']
                        )->name('datatable');

                        Route::get(
                            '/groups',
                            [UserRoleController::class, 'groups']
                        )->name('groups');

                        Route::get(
                            '/bygroup',
                            [UserRoleController::class, 'rolesByGroup']
                        )->name('bygroup');

                        Route::get(
                            '/{id}',
                            [UserRoleController::class, 'show']
                        )->name('show');

                        Route::post(
                            '/',
                            [UserRoleController::class, 'store']
                        )->name('store');

                        Route::put(
                            '/{id}',
                            [UserRoleController::class, 'update']
                        )->name('update');

                        Route::delete(
                            '/{id}',
                            [UserRoleController::class, 'destroy']
                        )->name('destroy');

                    });


                /*
                |--------------------------------------------------------------------------
                | GROUPS
                |--------------------------------------------------------------------------
                */

                Route::prefix('groups')
                    ->name('groups.')
                    ->group(function () {

                        Route::get(
                            '/',
                            [UserGroupController::class, 'index']
                        )->name('index');

                        Route::get(
                            '/datatable',
                            [UserGroupController::class, 'datatable']
                        )->name('datatable');

                        Route::post(
                            '/',
                            [UserGroupController::class, 'store']
                        )->name('store');

                        Route::get(
                            '/{id}',
                            [UserGroupController::class, 'show']
                        )->name('show');

                        Route::put(
                            '/{id}',
                            [UserGroupController::class, 'update']
                        )->name('update');

                        Route::delete(
                            '/{id}',
                            [UserGroupController::class, 'destroy']
                        )->name('destroy');

                    });

            });

    });
    // END USER PANEL

Route::prefix('adminpanel/posyandu')
    ->name('adminpanel.posyandu.')
    ->group(function () {

        Route::get(
            '/select-posyandu',
            [WilayahKerjaPosyanduController::class, 'selectPosyandu']
        )->name('selectPosyandu');


        /*
        |--------------------------------------------------------------------------
        | WILAYAH KERJA POSYANDU
        |--------------------------------------------------------------------------
        */

        Route::prefix('wilayah-kerja')
            ->name('wilayahkerja.')
            ->group(function () {

                Route::get(
                    '/',
                    [WilayahKerjaPosyanduController::class, 'index']
                )->name('index');

                Route::get(
                    '/datatable',
                    [WilayahKerjaPosyanduController::class, 'datatable']
                )->name('datatable');

                Route::get(
                    '/create',
                    [WilayahKerjaPosyanduController::class, 'create']
                )->name('create');

                Route::post(
                    '/',
                    [WilayahKerjaPosyanduController::class, 'store']
                )->name('store');

                Route::get(
                    '/{id}/edit',
                    [WilayahKerjaPosyanduController::class, 'edit']
                )->name('edit');

                Route::put(
                    '/{id}',
                    [WilayahKerjaPosyanduController::class, 'update']
                )->name('update');

                Route::delete(
                    '/{id}',
                    [WilayahKerjaPosyanduController::class, 'destroy']
                )->name('destroy');

            });


        /*
        |--------------------------------------------------------------------------
        | MASTER POSYANDU
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [PosyanduController::class, 'index']
        )->name('index');

        Route::get(
            '/data',
            [PosyanduController::class, 'data']
        )->name('data');

        Route::get(
            '/create',
            [PosyanduController::class, 'create']
        )->name('create');

        Route::post(
            '/store',
            [PosyanduController::class, 'store']
        )->name('store');

        Route::delete(
            '/delete/{id}',
            [PosyanduController::class, 'destroy']
        )->name('destroy');


        /*
        |--------------------------------------------------------------------------
        | WILAYAH
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/provinces',
            [PosyanduController::class, 'provinces']
        )->name('provinces');

        Route::get(
            '/cities',
            [PosyanduController::class, 'cities']
        )->name('cities');

        Route::get(
            '/districts',
            [PosyanduController::class, 'districts']
        )->name('districts');

        Route::get(
            '/villages',
            [PosyanduController::class, 'villages']
        )->name('villages');

        Route::get(
    '/edit/{id}',
    [PosyanduController::class, 'edit']
)->name('edit');

Route::put(
    '/update/{id}',
    [PosyanduController::class, 'update']
)->name('update');


        /*
        |--------------------------------------------------------------------------
        | FASKES
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/faskes',
            [PosyanduController::class, 'faskes']
        )->name('faskes');

        Route::get(
    '/location',
    [PosyanduController::class, 'location']
)->name('location');


    });



Route::get(
    '/propinsi',
    [PosyanduController::class,'provinces']
);






Route::prefix('newlplpo')->name('newlplpo.')->group(function () {



Route::prefix('bekasi')
    ->name('bekasi.')
    ->group(function () {

        Route::get(
            '/',
            [LplpoBekasiController::class, 'index']
        )->name('index');

        Route::get(
            '/data',
            [LplpoBekasiController::class, 'data']
        )->name('data');

        Route::prefix('rekap')
    ->name('rekap.')
    ->group(function () {

    Route::get(
    '/',
    [LplpoBekasiController::class, 'rekap']
)->name('index');
Route::get(
    '/data',
    [LplpoBekasiController::class, 'rekapData']
)->name('data');



    });



    });




        Route::get(
            '/{report}/kunjungan/create',
            [KunjunganController::class, 'create']
        )->name('kunjungan.create');

        Route::post(
            '/{report}/kunjungan',
            [KunjunganController::class, 'store']
        )->name('kunjungan.store');

        Route::get(
            '/{report}/kunjungan/edit',
            [KunjunganController::class, 'edit']
        )->name('kunjungan.edit');

        Route::put(
            '/{report}/kunjungan',
            [KunjunganController::class, 'update']
        )->name('kunjungan.update');

        Route::delete(
            '/{report}/kunjungan',
            [KunjunganController::class, 'destroy']
        )->name('kunjungan.destroy');




Route::prefix('stok-esensial')
    ->name('stok-esensial.')
    ->controller(StokEsensialController::class)
    ->group(function () {
         Route::post('/duplikasi',[StokEsensialController::class, 'duplicate'])->name('duplicate');
         Route::get('/setting',[StokEsensialController::class, 'setting'])->name('setting');


        Route::get(
            '/',
            'index'
        )->name('index');

        Route::get(
            '/datatable',
            'datatable'
        )->name('datatable');

        Route::post(
            '/',
            'store'
        )->name('store');

        Route::get(
            '/{id}',
            'show'
        )->name('show');

        Route::put(
            '/{id}',
            'update'
        )->name('update');

        Route::delete(
            '/{id}',
            'destroy'
        )->name('destroy');

    });


 Route::get(
            '/rekap',
            [LplpoRekapController::class, 'index']
        )->name('rekap');

        Route::get(
            '/rekap/data',
            [LplpoRekapController::class, 'data']
        )->name('rekap.data');



Route::prefix('masterdataobat')
    ->name('masterdataobat.')
    ->group(function () {
        Route::get(
    '/datatableforcanvas',
    [MasterDataObatController::class, 'datatableforcanvas']
)->name('datatableforcanvas');

        Route::get(
            '/',
            [MasterDataObatController::class, 'index']
        )->name('index');

        Route::get(
            '/datatable',
            [MasterDataObatController::class, 'datatable']
        )->name('datatable');

        Route::post(
            '/',
            [MasterDataObatController::class, 'store']
        )->name('store');

        Route::get(
            '/{id}',
            [MasterDataObatController::class, 'show']
        )->name('show');

        Route::put(
            '/{id}',
            [MasterDataObatController::class, 'update']
        )->name('update');

        Route::delete(
            '/{id}',
            [MasterDataObatController::class, 'destroy']
        )->name('destroy');


    });

Route::prefix('program')
            ->name('program.')
            ->group(function () {

                Route::get(
                    '/',
                    [ProgramController::class, 'index']
                )->name('index');

                Route::get(
                    '/datatable',
                    [ProgramController::class, 'datatable']
                )->name('datatable');

                Route::post(
                    '/',
                    [ProgramController::class, 'store']
                )->name('store');

                Route::get(
                    '/{id}',
                    [ProgramController::class, 'show']
                )->name('show');

                Route::put(
                    '/{id}',
                    [ProgramController::class, 'update']
                )->name('update');

                Route::delete(
                    '/{id}',
                    [ProgramController::class, 'destroy']
                )->name('destroy');

            });


    Route::prefix('arsip')->name('arsip.')->group(function () {

    Route::get('/', [LplpoArsipController::class,'index'])
        ->name('index');

    Route::get('/datatable', [LplpoArsipController::class,'datatable'])
        ->name('datatable');

    Route::get('/{id}', [LplpoArsipController::class,'detail'])
        ->name('detail');

    Route::get('/{id}/print', [LplpoArsipController::class,'print'])
        ->name('print');

});



    Route::prefix('verifikasi')->name('verifikasi.')->group(function(){
                    Route::get('/',[LplpoVerificationController::class,'index'])->name('index');
                    Route::get('/datatable',[LplpoVerificationController::class,'datatable'])->name('datatable');
                    Route::get('/{id}',[LplpoVerificationController::class,'detail'])->name('detail');
                    Route::post('/{id}/approve',[LplpoVerificationController::class,'approve'])->name('approve');
                    Route::post('/{id}/reject',[LplpoVerificationController::class,'reject'])->name('reject');
                    });
    Route::prefix('pemberian')->name('pemberian.')->group(function(){


    Route::post(
    '/{report}/tambah-obat',
    [LplpoPemberianController::class, 'tambahObat']
)->name('tambah-obat');

        Route::get('/',[LplpoPemberianController::class,'index'])->name('index');
        Route::get('/datatable',[LplpoPemberianController::class,'datatable'])->name('datatable');
        Route::get('/{id}',[LplpoPemberianController::class,'detail'])->name('detail');
        Route::post('/item/{id}',[LplpoPemberianController::class,'updatePemberian'])->name('item.update');
        Route::post('/{id}/finish',[LplpoPemberianController::class,'finish'])->name('finish');
    });


     Route::prefix('item')->name('item.')->group(function () {
        Route::post('/copy-previous-month',[LplpoItemController::class, 'copyPreviousMonth'])->name('copy-previous-month');
        Route::get('/default',[LplpoItemController::class,'defaultValue'])->name('default');
        Route::get('/{report}', [LplpoItemController::class,'list'])->name('list');
         Route::get('/{id}/edit',[LplpoItemController::class, 'edit'])->name('edit');
        Route::post('/', [LplpoItemController::class,'store'])->name('store');

        Route::put('/{id}', [LplpoItemController::class,'update'])->name('update');
        Route::delete('/{id}', [LplpoItemController::class,'destroy'])->name('destroy');
        });
    Route::prefix('masterobat')->name('masterobat.')->group(function(){
        Route::get('/datatable',[MasterObatController::class,'datatable'])->name('datatable');
        });
    Route::get('/laporan', [LplpoController::class,'laporan'])->name('laporan');
    Route::get('/laporan/datatable', [LplpoController::class,'laporanDatatable'])->name('laporan.datatable');
    Route::get('/', [DashboardController::class,'index'])->name('index');
    Route::get('/buatlplpo',[LplpoController::class,'create'])->name('create');
    Route::post('/buatlplpo',[LplpoController::class,'store'])->name('store');
    Route::get('/{id}/edit',[LplpoController::class,'edit'])->name('edit');
    Route::get('/{id}/detail', [LplpoController::class,'detail'])->name('detail');
    Route::get('/arsiplplpo',[LplpoController::class,'arsip'])->name('arsip');
    Route::get('/pemberian-obat',[LplpoController::class,'pemberian'])->name('pemberian');
    Route::put('/{id}',[LplpoController::class,'update'])->name('update');
    Route::delete('/{id}',[LplpoController::class,'destroy'])->name('destroy');

        });

                    });//End Of AUTH


Route::get('/get-kabupaten/{province_code}', [LabelLplpoController::class, 'getKabupaten']);
Route::prefix('dashboard')->group(function () {
  Route::get('/', [DashboardPageController::class, 'index']);
Route::get('/realtime', [DashboardPageController::class, 'realtime']);

Route::put('/{id}',[LplpoController::class, 'update'])->name('update');
});
Route::get('/dashboard-lplpo', fn() => view('dashboard.lplpo'));
