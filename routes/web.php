<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Ticket\TicketController;
use Illuminate\Support\Facades\Route;


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

    Route::post('/notifications/{notification}/mark-as-read', [\App\Http\Controllers\NotificationController::class,'markAsRead'])
        ->name('notifications.markAsRead');

    Route::post('/notifications/mark-all-as-read', [\App\Http\Controllers\NotificationController::class,'markAllAsRead'])
        ->name('notifications.markAllAsRead');

    //Export Excel
    Route::get('/export', [App\Http\Controllers\School\SchoolController::class,'exportExcel'])->name('export-excel');

    Route::get('/employee/show-assigned-tickets', [TicketController::class,'showAssignedTickets'])
        ->name('employee.show-assigned-tickets');

    Route::get('/employee/view-ticket/{ticketId}', [TicketController::class,'viewTicket'])
        ->name('employee.view-ticket');

    Route::post('/tickets/{ticketId}/status', [TicketController::class,'changeStatus'])
        ->name('employee.tickets.changeStatus');
    Route::post('/tickets/{ticket}/add-comment', [TicketController::class,'addComment'])
        ->name('employee.tickets.addComment');


    Route::get('/employee/monthly-plan', [\App\Http\Controllers\Employee\MonthlyPlan::class,'monthlyPlan'])->name('employee.monthly-plan');

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

    Route::get('/head/show-tickets', [TicketController::class, 'showDepTickets'])
        ->name('head.show-tickets');

    Route::get('/head/show-assigned-tickets', [TicketController::class, 'showTicketsDep'])
        ->name('head.show-assigend-tickets');

    Route::post('/head/assignticket/{ticketId}', [TicketController::class, 'assignTicket'])
        ->name('head.tickets.assign');

    Route::get('/head/tickets/filter', [TicketController::class, 'headfilterTickets'])
        ->name('head.tickets.filter');

    Route::get('/head/viewVisits',[\App\Http\Controllers\School\SchoolController::class,'viewDepVisits'])
        ->name('head.view-visits');

});

//Admin Routes
Route::middleware(['auth', 'role:Administrator'])->group(function ()
{




    Route::get('/admin/override-plan-restrictions', [AdminController::class, 'showOverridePlanRestrictionsForm'])->name('admin.override-plan-restrictions');
    Route::post('/admin/override-plan-restrictions', [AdminController::class, 'overridePlanRestrictions'])->name('admin.apply-override-plan-restrictions');
    Route::get('/fetch-department-users', [AdminController::class,'fetchDepartmentUsers'])->name('fetch.department.users');
    Route::delete('/admin/plan-restrictions/{id}', [AdminController::class,'deletePlanRestriction'])->name('admin.delete-plan-restriction');

    Route::get('/admin/showschoolsvisits', [App\Http\Controllers\School\SchoolController::class, 'showSchoolsVisits'])
        ->name('admin.show-schools-visits');

    Route::get('/admin/get-user-timeline',[\App\Http\Controllers\Admin\AdminController::class,'getUserTimeline'])
        ->name('admin.getUserTimeline');

    Route::get('/admin/timeline', [App\Http\Controllers\Admin\AdminController::class, 'timeline'])
        ->name('admin.timeline');


    Route::get('/admin/show-tickets', [TicketController::class, 'showTicketsAdmin'])
        ->name('admin.show-tickets');

    Route::post('/tickets/{ticketId}/assign', [TicketController::class, 'assignTicketDepAdmin'])
        ->name('admin.tickets.assign');

    Route::get('/admin/tickets/details', [TicketController::class, 'getTicketDetails'])
        ->name('admin.tickets.details');

    Route::get('/admin/tickets/comments', [TicketController::class, 'getComments'])
        ->name('admin.tickets.comments');

    Route::get('/admin/tickets/filter', [TicketController::class, 'showTicketsAdmin'])
        ->name('admin.tickets.filter');


    Route::get('/admin/search', [App\Http\Controllers\Admin\AdminController::class, 'search'])
        ->name('admin.search-plan');

    Route::get('/admin/searchschooldate', [App\Http\Controllers\Admin\AdminController::class, 'searchSchoolDate'])
        ->name('admin.search-plan-school-date');


    Route::get('/admin/showplan/{id}',[App\Http\Controllers\Admin\AdminController::class,'show'])
        ->name('admin.show-plan');

    Route::get('/admin/search-plan-school', [App\Http\Controllers\Admin\AdminController::class, 'searchPlanBySchool'])->name('admin.search-plan-school');

    Route::get('/admin/search-plan-date', [App\Http\Controllers\Admin\AdminController::class, 'searchPlanByDate'])->name('admin.search-plan-date');

    Route::get('/admin/allschools', [App\Http\Controllers\Admin\AdminController::class, 'searchAllSchool'])->name('admin.search-all-schools');

});
Route::middleware(['auth', 'role:School'])->group(function () {

    Route::get('/school/index', [App\Http\Controllers\School\SchoolController::class, 'index'])->name('school.index');
    Route::get('users/search', [App\Http\Controllers\School\SchoolController::class, 'usersSearch'])->name('users.search');

    Route::get('/school/createvisits', [App\Http\Controllers\School\SchoolController::class, 'create'])->name('school.create-visits');
    Route::get('/school/addvisits', [App\Http\Controllers\School\SchoolController::class, 'addvisits'])->name('school.add-visits');
    Route::get('/school/fetchdepartmentusers', [App\Http\Controllers\School\SchoolController::class, 'fetchDepartmentUsers'])->name('school.fetch.department.users');
    Route::post('/school/storevisits', [App\Http\Controllers\School\SchoolController::class,  'store'])->name('school.store-visits');
    Route::delete('/school/deletevisits/{id}', [App\Http\Controllers\School\SchoolController::class, 'destroy'])->name('school.delete-visit');

    Route::get('/school/show-tickets', [App\Http\Controllers\Ticket\TicketController::class, 'index'])->name('school.show-tickets');
    Route::get('/school/create-ticket', [App\Http\Controllers\Ticket\TicketController::class, 'create'])->name('school.create-ticket');
    Route::post('/school/store-ticket', [App\Http\Controllers\Ticket\TicketController::class, 'store'])->name('school.store-ticket');

    Route::get('/school/tickets/details', [TicketController::class, 'getTicketDetails'])
        ->name('school.tickets.details');

    Route::get('/tickets/comments', [TicketController::class, 'getComments'])
        ->name('school.tickets.comments');

});
