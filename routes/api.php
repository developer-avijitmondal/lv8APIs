<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\OTPLoginController;
use App\Http\Controllers\API\AdminController;
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

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::post('mobile-otp', [OTPLoginController::class, 'index']);
Route::post('otp-verify', [OTPLoginController::class, 'verifyOTP']);
Route::post('save-session', [OTPLoginController::class, 'saveUserSessions']);
Route::get('remove-session/{user_id}', [OTPLoginController::class, 'removeUserSessions']);
Route::get('user-list', [AdminController::class, 'getCommonUsers']);
// Route::get('/user', [UserController::class, 'index']);//removeUserSessions
//Route::get('otp/login', 'API\OTPLoginController@index');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
