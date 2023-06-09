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

//Common Routes
Route::get('/logout', [\App\Http\Controllers\Auth\LogoutController::class,'index'])->name('auth.logout');

Route::middleware(['auth'])->group(function()
{
//    Route::get('/welcome', function () {
//        return view('welcome');
//    })->name('home');

    Route::get('/', function () {
        return view('welcome');
    })->name('home');



    Route::get('/employee/enterplan',[App\Http\Controllers\Employee\MonthlyPlan::class,'index'])
        ->name('employee.select-month-year-plan');

    Route::get('/employee/createplan/{month}/{year}',[App\Http\Controllers\Employee\MonthlyPlan::class,'create'])
        ->name('employee.create-plan');

    Route::post('/employee/storeplan',[App\Http\Controllers\Employee\MonthlyPlan::class,'store'])
        ->name('employee.store-plan');

    Route::get('/employee/showplan',[App\Http\Controllers\Employee\MonthlyPlan::class,'show'])
        ->name('employee.show-plan');
    Route::get('/employee/showonlyplan',[App\Http\Controllers\Employee\MonthlyPlan::class,'showonly'])
        ->name('employee.showonly-plan');

    Route::get('/employee/editplan/{plan}',[App\Http\Controllers\Employee\MonthlyPlan::class,'edit'])
        ->name('employee.edit-plan');

    Route::get('/employee/deleteplan/{plan}',[App\Http\Controllers\Employee\MonthlyPlan::class,'destroy'])
        ->name('employee.delete-plan');

    Route::put('/employee/updateplan/{plan}',[App\Http\Controllers\Employee\MonthlyPlan::class,'update'])
        ->name('employee.update-plan');

    Route::get('/employee/addDay/{date}',[App\Http\Controllers\Employee\MonthlyPlan::class,'addDay'])
        ->name('employee.add-day');
    Route::post('/employee/storeDay',[App\Http\Controllers\Employee\MonthlyPlan::class,'storeDay'])
        ->name('employee.store-day');

});

//Employee Routes
Route::middleware(['auth', 'role:Employee'])->group(function ()

{

});

//Department Head Routes
Route::middleware(['auth', 'role:Department_Head'])->group(function ()
{

    Route::get('/head/showplan/{id}',[App\Http\Controllers\Head\HeadController::class,'show'])
        ->name('head.show-plan');
//
//    Route::get('/employee/enterplan',[App\Http\Controllers\Employee\MonthlyPlan::class,'index'])
//        ->name('employee.select-month-year-plan');
//    Route::get('/employee/createplan/{month}/{year}',[App\Http\Controllers\Employee\MonthlyPlan::class,'create'])
//        ->name('employee.create-plan');
//
//    Route::post('/employee/storeplan',[App\Http\Controllers\Employee\MonthlyPlan::class,'store'])
//        ->name('employee.store-plan');
//
//    Route::get('/employee/showplan',[App\Http\Controllers\Employee\MonthlyPlan::class,'show'])
//        ->name('employee.show-plan');
//    Route::get('/employee/showonlyplan',[App\Http\Controllers\Employee\MonthlyPlan::class,'showonly'])
//        ->name('employee.showonly-plan');
//
//    Route::get('/employee/editplan/{plan}',[App\Http\Controllers\Employee\MonthlyPlan::class,'edit'])
//        ->name('employee.edit-plan');
//
//    Route::get('/employee/deleteplan/{plan}',[App\Http\Controllers\Employee\MonthlyPlan::class,'destroy'])
//        ->name('employee.delete-plan');
//
//    Route::post('/employee/updateplan/{plan}',[App\Http\Controllers\Employee\MonthlyPlan::class,'update'])
//        ->name('employee.update-plan');
//
//    Route::get('/employee/addDay/{date}',[App\Http\Controllers\Employee\MonthlyPlan::class,'addDay'])
//        ->name('employee.add-day');
//    Route::post('/employee/storeDay',[App\Http\Controllers\Employee\MonthlyPlan::class,'storeDay'])
//        ->name('employee.store-day');
});

//Admin Routes
Route::middleware(['auth', 'role:Administrator'])->group(function ()
{
//    Route::get('/', function () {
//        return view('welcome');
//    })->name('home');

//    Route::get('/admin/search',function(){
//        return view('admin.search-plan')->with('departments',\App\Models\Department::all());
//    })->name('admin.search-plan');
//
//    Route::get('/admin/searchresults',[App\Http\Controllers\Admin\AdminController::class,'search'])
//        ->name('admin.search-results');

    Route::get('/admin/search', [App\Http\Controllers\Admin\AdminController::class, 'search'])
        ->name('admin.search-plan');

    Route::get('/admin/searchschooldate', [App\Http\Controllers\Admin\AdminController::class, 'searchSchoolDate'])
        ->name('admin.search-plan-school-date');


    Route::get('/admin/showplan/{id}',[App\Http\Controllers\Admin\AdminController::class,'show'])
        ->name('admin.show-plan');
});
