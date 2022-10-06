<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\EmployeesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth/index');
});

//--AUTH----------------------------------------------------------------------------------------------------------------
Route::get('auth', [LoginController::class, 'index'])->name('login-page');
Route::post('auth/login', [LoginController::class, 'login'])->name('login');

Route::get('auth/registration', [LoginController::class, 'registration'])->name('register-user');
Route::post('auth/custom-registration', [LoginController::class, 'customRegistration'])->name('register.custom');

Route::get('auth/logout', [LoginController::class, 'logOut'])->name('logout');

//--TEAM----------------------------------------------------------------------------------------------------------------
//display SEARCH view -> action search (post)---------------------------------------------------------------------------
Route::get('teams/searchTeam', [TeamsController::class, 'searchTeam'])->name('team.searchTeam')->middleware('check.admin');
Route::get('teams/search/{column}/{direction}', [TeamsController::class, 'index'])->name('team.search')->middleware('check.admin');;

//display CREATE view -> take in data and display confirm view (post) -> create team (get)
Route::get('teams/createTeam', [TeamsController::class, 'createTeam'])->name('team.createTeam')->middleware('check.admin');;
Route::post('teams/createConfirm', [TeamsController::class, 'createTeamConfirm'])->name('team.createConfirm')->middleware('check.admin');;
Route::post('teams/create', [TeamsController::class, 'store'])->name('team.create')->middleware('check.admin');;

//display EDIT view -> take in data and display confirm view (post) -> edit team (post)
Route::get('teams/editTeam/{id}', [TeamsController::class, 'editTeam'])->where('id', '[0-9]+')->name('team.editTeam')->middleware('check.admin');;
Route::post('teams/editConfirm', [TeamsController::class, 'editTeamConfirm'])->name('team.editConfirm')->middleware('check.admin');;
Route::post('teams/edit/', [TeamsController::class, 'update'])->name('team.edit')->middleware('check.admin');;

//delete team
Route::get('teams/deleteTeam/{id}', [TeamsController::class, 'destroy'])->where('id', '[0-9]+')->name('team.delete')->middleware('check.admin');

//--EMPLOYEE------------------------------------------------------------------------------------------------------------
//display SEARCH view -> action search (post)---------------------------------------------------------------------------
Route::get('employees/searchEmployee', [employeesController::class, 'searchEmployee'])->name('employee.searchEmployee')->middleware('check.admin');;
Route::get('employees/search/{column}/{direction}', [employeesController::class, 'search'])->name('employee.search')->middleware('check.admin');;

//display CREATE view -> take in data and display confirm view (post) -> create employee (get)
Route::get('employees/createEmployee', [employeesController::class, 'createEmployee'])->name('employee.createEmployee')->middleware('check.admin');;
Route::post('employees/createConfirm', [employeesController::class, 'createEmployeeConfirm'])->name('employee.createConfirm')->middleware('check.admin');;
Route::post('employees/create', [employeesController::class, 'store'])->name('employee.create')->middleware('check.admin');;

//display EDIT view -> take in data and display confirm view (post) -> edit employee (get)
Route::get('employees/editEmployee', [employeesController::class, 'editEmployee'])->name('employee.editEmployee')->middleware('check.admin');;
Route::post('employees/edit', [employeesController::class, 'editConfirm'])->name('employee.editConfirm')->middleware('check.admin');;
Route::post('employees/edit/{confirm}', [employeesController::class, 'edit'])->name('employee.edit')->middleware('check.admin');;
