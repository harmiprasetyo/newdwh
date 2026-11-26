<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MasterTpmbController;
// Ubah middleware ke alias API key existing bila diperlukan.
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
 Route::apiResource('master-tpmb', MasterTpmbController::class)->parameters(['master-tpmb'=>'tpmb']);
});
