<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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




Route::post('/auth/login', [AuthController::class,'login']);
Route::post('/auth/logout', [AuthController::class,'logout']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum','role:Administrator'])->group(function () {
    Route::resource('/plans', \App\Http\Controllers\Api\AdminController::class);

});

Route::middleware(['auth:sanctum','role:Employee'])->group(function () {
//    Route::resource('/plans', \App\Http\Controllers\Api\AdminController::class);
    Route::get('/employee/showplan', [\App\Http\Controllers\Api\EmployeeController::class,'show']);
});

Route::middleware(['auth:sanctum','role:Department_Head'])->group(function () {
//    Route::resource('/plans', \App\Http\Controllers\Api\AdminController::class);
    Route::get('/head/dep-users', [\App\Http\Controllers\Api\HeadController::class,'showDepartmentEmp']);

});



