<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EtpmbUserController;

Route::middleware('etpmb.api')
    ->prefix('etpmb/users')
    ->group(function () {

        Route::get('/', [EtpmbUserController::class, 'index']);

        Route::post('/', [EtpmbUserController::class, 'store']);

        Route::get('/{userid}', [EtpmbUserController::class, 'show']);

        Route::put('/{userid}', [EtpmbUserController::class, 'update']);

        Route::delete('/{userid}', [EtpmbUserController::class, 'destroy']);
    });
