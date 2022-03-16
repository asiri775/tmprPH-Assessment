<?php

use App\Http\Controllers\OnBoarding\OnboardingController;
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


Route::get('/charts/weeklyData', [OnboardingController::class, 'weeklyData']);
Route::get('/charts/upcaseData', [OnboardingController::class, 'upcaseData']);
Route::get('/charts/dataValidation', [OnboardingController::class, 'dataValidation']);