<?php

use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\Registration\RegistrationRajalController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Registration\RegistrationIgdController;
use App\Http\Controllers\Registration\RegistrationRanapController;
use App\Http\Controllers\Penunjang\PenunjangController;
use App\Http\Controllers\Satusehat\SsPasienController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::middleware(['token'])->group(function () {
    // DOKTER SERVICE
    Route::get('dokter', [DokterController::class,'get']);
    Route::get('dokter/detail/{kode}', [DokterController::class,'getByKode']);
    Route::post('dokter/ihs/{kode}', [DokterController::class,'updateIHSbyKode']);


    // PASIEN SERVICE
    Route::get('pasien', [PasienController::class,'get']);
    Route::get('pasien/detail/{nik}', [PasienController::class,'getByNik']);
    Route::post('pasien/ihs/{norm}', [PasienController::class,'updateIHSByNorm']);

    // Route::get('ss/pasien', [SsPasienController::class,'get']);
    // Route::get('ss/pasien/sync', [SsPasienController::class,'sync']);

    Route::get('registration', [RegistrationController::class,'get']);
    Route::get('registration/detail', [RegistrationController::class,'getByNoreg']);
    Route::post('registration/update/encounterid', [RegistrationController::class,'updateEncounterId']);
    Route::get('registration/dokter', [RegistrationController::class,'getByDokter']);


    Route::get('registration/rajal/count', [RegistrationRajalController::class,'getCount']);
    Route::get('registration/rajal', [RegistrationRajalController::class,'get']);
    Route::get('registration/rajal/lastday', [RegistrationRajalController::class,'getlastday']);
    Route::get('registration/rajal/date', [RegistrationRajalController::class,'getDate']);
    Route::get('registration/rajal/detail', [RegistrationRajalController::class,'getByNoreg']);

    // Route::get('registration/rajal/dokter', [RegistrationRajalController::class,'getByDokter']);

    Route::get('registration/ranap', [RegistrationRanapController::class,'get']);
    Route::get('registration/ranap/detail', [RegistrationRanapController::class,'getByNoreg']);
    Route::get('registration/ranap/dokter', [RegistrationRanapController::class,'getByDokter']);

    Route::get('registration/igd', [RegistrationIgdController::class,'get']);
    Route::get('registration/igd/lastday', [RegistrationIgdController::class,'getlastday']);

    Route::get('registration/igd/detail', [RegistrationIgdController::class,'getByNoreg']);
    Route::get('registration/igd/dokter', [RegistrationIgdController::class,'getByDokter']);

    //Penunjang
    Route::get('penunjang/farmasi', [PenunjangController::class,'farmasi']);
    Route::get('penunjang/laboratorium', [PenunjangController::class,'laboratorium']);
    Route::get('penunjang/radiologi', [PenunjangController::class,'radiologi']);

});
