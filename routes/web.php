<?php

use Illuminate\Http\Request;
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

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});


// Guest Users
Route::middleware(['guest','PreventBackHistory'])->group(function()
{
    Route::get('/', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('/');
    Route::get('login', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'] )->name('login');
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('signin');

});



// Authenticated users
Route::middleware(['auth','PreventBackHistory'])->group(function()
{

    // Auth Routes
    Route::get('edit-profile', [App\Http\Controllers\Admin\DashboardController::class, 'editProfile'] )->name('edit-profile');
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('home', fn () => redirect()->route('dashboard'))->name('home');
    Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'Logout'])->name('logout');
    Route::get('show-change-password', [App\Http\Controllers\Admin\AuthController::class, 'showChangePassword'] )->name('show-change-password');
    Route::post('change-password', [App\Http\Controllers\Admin\AuthController::class, 'changePassword'] )->name('change-password');



    // Masters routes
    Route::resource('departments', App\Http\Controllers\Admin\Masters\DepartmentController::class );
    Route::get('departments/{department}/sub_departments', [App\Http\Controllers\Admin\Masters\DepartmentController::class, 'getSubDepartments'] )->name('departments.sub_departments');
    Route::resource('sub-departments', App\Http\Controllers\Admin\Masters\SubDepartmentController::class );
    Route::resource('wards', App\Http\Controllers\Admin\Masters\WardController::class );
    Route::get('wards/{ward}/departments', [App\Http\Controllers\Admin\Masters\DepartmentController::class, 'getWardDepartments'] )->name('wards.departments');
    Route::resource('clas', App\Http\Controllers\Admin\Masters\ClasController::class );
    Route::resource('designations', App\Http\Controllers\Admin\Masters\DesignationController::class );
    Route::resource('holidays', App\Http\Controllers\Admin\Masters\HolidayController::class );
    Route::resource('leave_types', App\Http\Controllers\Admin\Masters\LeaveTypeController::class );
    Route::resource('leaves', App\Http\Controllers\Admin\Masters\LeaveController::class );
    Route::resource('shifts', App\Http\Controllers\Admin\Masters\ShiftController::class );
    Route::resource('devices', App\Http\Controllers\Admin\Masters\DeviceController::class );


    // Users Roles n Permissions
    Route::resource('users', App\Http\Controllers\Admin\UserController::class );
    Route::get('users/{user}/toggle', [App\Http\Controllers\Admin\UserController::class, 'toggle' ])->name('users.toggle');
    Route::put('users/{user}/change-password', [App\Http\Controllers\Admin\UserController::class, 'changePassword' ])->name('users.change-password');
    Route::get('users/{user}/get-role', [App\Http\Controllers\Admin\UserController::class, 'getRole' ])->name('users.get-role');
    Route::put('users/{user}/assign-role', [App\Http\Controllers\Admin\UserController::class, 'assignRole' ])->name('users.assign-role');
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class );


    // Employees Routes
    Route::resource('employees', App\Http\Controllers\Admin\EmployeeController::class );
    Route::get('employees/{employee:emp_code}/info', [App\Http\Controllers\Admin\EmployeeController::class, 'fetchInfo'] )->name('employees.info');


    // Punches Routes
    Route::resource('punches', App\Http\Controllers\Admin\PunchController::class );


    // Leaves Routes
    Route::resource('leave-requests', App\Http\Controllers\Admin\LeaveRequestController::class );
    Route::put('leave-requests/{leave_request}/change-request', [App\Http\Controllers\Admin\LeaveRequestController::class, 'changeRequest'] )->name('leave-requests.change-request');
    Route::get('active-medical-leave-requests', [App\Http\Controllers\Admin\LeaveRequestController::class, 'activeMedicalLeaveRequest'] )->name('leave-requests.active-medical-leave');
    Route::get('completed-medical-leave-requests', [App\Http\Controllers\Admin\LeaveRequestController::class, 'completedMedicalLeaveRequest'] )->name('leave-requests.completed-medical-leave');
    Route::get('leave-applications', [App\Http\Controllers\Admin\LeaveRequestController::class, 'pendingLeaveRequest'] )->name('leave-requests.application');


    // Employee Shifts
    Route::resource('rosters', App\Http\Controllers\Admin\RosterController::class );
    Route::get('sample_roster', [App\Http\Controllers\Admin\RosterController::class, 'downloadSample'] )->name('rosters.sample');
    Route::post('import_shift_roster', [App\Http\Controllers\Admin\RosterController::class, 'importShiftRoster'] )->name('rosters.import');


    // Reports
    Route::resource('reports', App\Http\Controllers\Admin\ReportController::class );
    Route::get('reports/muster', [App\Http\Controllers\Admin\ReportController::class, 'musterReport'] )->name('reports.muster');
    Route::get('reports/get/month-wise-date', [App\Http\Controllers\Admin\ReportController::class, 'monthWiseDate'] )->name('reports.dates');



});





Route::get('/import-department', function(){
    Artisan::call('department:import');
    return dd(Artisan::output());
});
Route::get('/import-punches', function(){
    Artisan::call('punches:import');
    return dd(Artisan::output());
});
Route::get('/import-employees', function(){
    Artisan::call('employees:import');
    return dd(Artisan::output());
});

Route::get('/php', function(Request $request){
    // if( !auth()->check() )
    //     return 'Unauthorized request';

    Artisan::call($request->artisan);
    return dd(Artisan::output());
});


Route::get('/test-code', function(){
    DB::table('app_employees')->orderBy('id')->chunk(200, function($users){
        foreach($users as $user)
        {
            DB::table('app_users')->where('id', $user->user_id)->update(['ward_id'=> $user->ward_id]);
        }
    });

    return 'done';
});
