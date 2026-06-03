<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\master\IndonesiaController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserGroupController;
use App\Http\Controllers\Api\MasterFaskesController;
use App\Http\Controllers\Api\ListTypeFaskesController;
use App\Http\Controllers\Api\UsersAppController;
use App\Http\Controllers\Auth\AuthController as AuthAuthController;
use App\Http\Controllers\Api\LplpoController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\UserAppController;
use App\Http\Controllers\Api\MasterObatController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('newauth')->group(function () {
Route::post('/login', [AuthAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

   Route::get('/me', [AuthAuthController::class, 'me']);
Route::post('/logout', [AuthAuthController::class, 'logout']);



});
});

Route::middleware('auth:sanctum')->group(function () {
Route::prefix('user-app')->group(function () {

    Route::post('/register', [UserAppController::class, 'register']);
    Route::post('/login', [UserAppController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [UserAppController::class, 'me']);
    });

});
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('wilayah')->group(function () {
    Route::get('/provinsi', [IndonesiaController::class, 'provinces']);
    Route::get('/kota', [IndonesiaController::class, 'cities']);
    Route::get('/kecamatan', [IndonesiaController::class, 'districts']);
    Route::get('/desa', [IndonesiaController::class, 'villages']);
});


/**
 * route resource post
 */
//Route::resource('/fhir', FhirController::class);

Route::prefix('users')->group(function () {
    Route::get('/datatables', [UserController::class, 'datatables']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::prefix('usersapp')->group(function () {
    Route::get('/', [UsersAppController::class, 'index']);
    Route::post('/', [UsersAppController::class, 'store']);
    Route::put('/{id}', [UsersAppController::class, 'update']);
    Route::delete('/{id}', [UsersAppController::class, 'destroy']);
});

Route::prefix('usergroups')->group(function(){

    Route::get('/', [UserGroupController::class, 'index']);
    Route::post('/', [UserGroupController::class, 'store']);
    Route::get('/{id}', [UserGroupController::class, 'show']);
    Route::put('/{id}', [UserGroupController::class, 'update']);
    Route::delete('/{id}', [UserGroupController::class, 'destroy']);

});

Route::prefix('master')->group(function () {

Route::prefix('typefaskes')->group(function () {
    Route::get('/', [ListTypeFaskesController::class, 'index']);
    Route::post('/', [ListTypeFaskesController::class, 'store']);
    Route::put('/{id}', [ListTypeFaskesController::class, 'update']);
    Route::delete('/{id}', [ListTypeFaskesController::class, 'destroy']);
});

Route::prefix('faskes')->group(function () {
    Route::get('/', [MasterFaskesController::class, 'index']);
    Route::get('/{id}', [MasterFaskesController::class, 'show']);
    Route::post('/', [MasterFaskesController::class, 'store']);
    Route::put('/{id}', [MasterFaskesController::class, 'update']);
    Route::delete('/{id}', [MasterFaskesController::class, 'destroy']);
});



Route::prefix('lplpo')->group(function () {
    Route::get('/', [LplpoController::class, 'index']);
    Route::post('/', [LplpoController::class, 'store']);
    Route::get('/{id}', [LplpoController::class, 'show']);
    Route::put('/{id}', [LplpoController::class, 'update']);
    Route::delete('/{id}', [LplpoController::class, 'destroy']);
});





});




Route::prefix('apiusers')->group(function () {

    Route::post('/register', [UserApiController::class, 'register']);
    Route::post('/login', [UserApiController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [UserApiController::class, 'me']);
    });

});

// routes/api.php
Route::apiResource('master-obat', MasterObatController::class);




