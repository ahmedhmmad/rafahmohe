<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/welcome', function () {
        return view('welcome');
    })->name('home');

});

Route::middleware(['auth'])->prefix('employee')->group(function ()
{
    Route::get('/enterplan',[App\Http\Controllers\Employee\MonthlyPlan::class,'index'])
        ->name('employee.select-month-year-plan');
    Route::get('/createplan/{month}/{year}',[App\Http\Controllers\Employee\MonthlyPlan::class,'create'])
        ->name('employee.create-plan');

    Route::post('/storeplan',[App\Http\Controllers\Employee\MonthlyPlan::class,'store'])
        ->name('employee.store-plan');

    Route::get('/showplan',[App\Http\Controllers\Employee\MonthlyPlan::class,'show'])
        ->name('employee.show-plan');
    Route::get('/showonlyplan',[App\Http\Controllers\Employee\MonthlyPlan::class,'showonly'])
        ->name('employee.showonly-plan');

    Route::get('editplan/{plan}',[App\Http\Controllers\Employee\MonthlyPlan::class,'edit'])
        ->name('employee.edit-plan');

    Route::get('deleteplan/{plan}',[App\Http\Controllers\Employee\MonthlyPlan::class,'destroy'])
        ->name('employee.delete-plan');

    Route::post('updateplan/{plan}',[App\Http\Controllers\Employee\MonthlyPlan::class,'update'])
        ->name('employee.update-plan');

    Route::get('/addDay/{date}',[App\Http\Controllers\Employee\MonthlyPlan::class,'addDay'])
        ->name('employee.add-day');
    Route::post('/storeDay',[App\Http\Controllers\Employee\MonthlyPlan::class,'storeDay'])
        ->name('employee.store-day');


});


Route::get('/logout', [\App\Http\Controllers\Auth\LogoutController::class,'index'])->name('auth.logout');
