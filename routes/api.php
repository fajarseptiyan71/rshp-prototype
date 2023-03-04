<?php

use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\SpecialistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('v1')->group(function(){
    // Doctor
    Route::prefix('doctors')->controller(DoctorController::class)->group(function(){
        Route::get('/', 'index');
        Route::get('{id}/patients', 'indexPatients');
        Route::get('/{id}', 'show');
        Route::post('/store', 'store');
        Route::post('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    // Consultations
    Route::prefix('consultations')->controller(ConsultationController::class)->group(function (){
        Route::get('/', 'index');
        Route::get('/patients', 'indexPatients');
        Route::get('/{id}', 'show');
        Route::post('store', 'store');
        Route::post('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    //specialist
    Route::prefix('specialist')->controller(SpecialistController::class)->group(function (){
        Route::get('/', 'index');
        Route::post('/store', 'store');
        Route::post('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
});
