<?php

use App\Http\Controllers\Employee\GetAllEmployeeController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\GetOneEmployeeController;
use App\Http\Controllers\Employee\GetOneEmployeeNewController;
use App\Http\Controllers\Employee\EmployeeGradeController;

use App\Http\Controllers\Employee\EmployeeFamilyController;
use App\Http\Controllers\Employee\GetEmployeeFamilyController;

use App\Http\Controllers\Employee\GetEmployeeByOrgController;
use App\Http\Controllers\Employee\Hierarchy\GetPeerController;
use App\Http\Controllers\Employee\Hierarchy\GetAtasanController;
use App\Http\Controllers\Employee\GetEmployeeByCompanyController;
use App\Http\Controllers\Employee\GetEmployeeByUnitController;

use App\Http\Controllers\Employee\GetEmployeeByGradeClusterController;

use App\Http\Controllers\Employee\Hierarchy\GetBawahanController;
use App\Http\Controllers\Organization\GetOrgHierarchyController;
use App\Http\Controllers\Organization\GetUnitKerjaController;
use App\Http\Controllers\Payroll\GetEsptController;
use App\Http\Controllers\Payroll\GetPayslipController;
use App\Http\Controllers\Payroll\PayslipController;

use App\Http\Controllers\Master\GetMasterClusterController;

use App\Http\Controllers\Employee\EmployeeEducationController;
use App\Http\Controllers\Employee\EmployeeHobbyController;
use App\Http\Controllers\Employee\EmployeeTrainingController;
use App\Http\Controllers\LDAPController;
use App\Http\Controllers\Auth\AuthController;

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
Route::get('ldap', [LDAPController::class,'store']);

Route::post('login', [AuthController::class,'login']);
// Route::post('forgot-password', [AuthController::class,'forgot']);

// Route::group(['middleware' => 'auth.api'], function() {
    // Education
    Route::get('employee/education-list', [EmployeeEducationController::class,'indexByEmployee']);
    Route::get('employee/education-show/{id}', [EmployeeEducationController::class,'showByEmployee']);
    Route::resource('employee/education', EmployeeEducationController::class);

    // Hobby
    Route::get('employee/hobby-list', [EmployeeHobbyController::class,'indexByEmployee']);
    Route::get('employee/hobby-show/{id}', [EmployeeHobbyController::class,'showByEmployee']);
    Route::resource('employee/hobby', EmployeeHobbyController::class);

    // Training
    Route::get('employee/training-list', [EmployeeTrainingController::class,'indexByEmployee']);
    Route::get('employee/training-show/{id}', [EmployeeTrainingController::class,'showByEmployee']);
    Route::resource('employee/training', EmployeeTrainingController::class);

    // Employee Grade
    Route::get('employee/grade-list', [EmployeeGradeController::class,'indexByEmployee']);
    Route::get('employee/grade-show/{id}', [EmployeeGradeController::class,'showByEmployee']);
    Route::resource('employee/grade', EmployeeGradeController::class);

    // Employee
    Route::resource('employees', EmployeeController::class);
    Route::get('employee', GetOneEmployeeController::class);

    // Payslip
    Route::get('payslip/show', [PayslipController::class,'showByEmployee']);
    Route::resource('payslip', PayslipController::class);

    // Route::get('employee/show', GetOneEmployeeNewController::class);
    Route::get('employee/all', GetAllEmployeeController::class);
    Route::get('employee/all-new', [GetAllEmployeeController::class,'index']);
    Route::get('employee/company', GetEmployeeByCompanyController::class);
    // Route::get('employee/organization', GetEmployeeByOrgController::class);
    Route::get('employee/organization', [GetEmployeeByOrgController::class,'index']);

    // Route::get('employee/unit-kerja', GetEmployeeByUnitController::class);
    Route::get('employee/unit-kerja', [GetEmployeeByUnitController::class,'index']);

    Route::resource('employee/familys', EmployeeFamilyController::class);
    Route::get('employee/family', GetEmployeeFamilyController::class);

    Route::get('employee/hierarchy/atasan', GetAtasanController::class);
    Route::get('employee/hierarchy/bawahan', GetBawahanController::class);
    Route::get('employee/hierarchy/peer', GetPeerController::class);

    Route::get('employee/grade-cluster', GetEmployeeByGradeClusterController::class);

    Route::get('unit-kerja', GetUnitKerjaController::class);
    Route::get('organization-hierarchy', GetOrgHierarchyController::class);

    Route::get('espt', GetEsptController::class);


    Route::get('master/cluster', GetMasterClusterController::class);


// });
