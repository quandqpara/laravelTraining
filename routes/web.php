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

Route::get('auth', [LoginController::class, 'index'])->name('login-page');
Route::post('auth/login', [LoginController::class, 'login'])->name('login');

Route::get('auth/registration', [LoginController::class, 'registration'])->name('register-user');
Route::post('auth/custom-registration', [LoginController::class, 'customRegistration'])->name('register.custom');

Route::get('auth/logout', [LoginController::class, 'logOut'])->name('logout');

//display search view -> action search (post)---------------------------------------------------------------------------
Route::get('teams/searchTeam', [TeamsController::class, 'searchTeam'])->name('team.searchTeam');
Route::get('teams/search', [TeamsController::class, 'search'])->name('team.search');

//display create view -> take in data and display confirm view (post) -> create team (get)
Route::get('teams/createTeam', [TeamsController::class, 'createTeam'])->name('team.createTeam');
Route::post('teams/create', [TeamsController::class, 'createConfirm'])->name('team.createConfirm');
route::get('teams/create/{confirm}', [TeamsController::class, 'create'])->name('team.create');

//display edit view -> take in data and display confirm view (post) -> edit team (get)
Route::get('teams/editTeam', [TeamsController::class, 'editTeam'])->name('team.editTeam');
Route::post('teams/edit', [TeamsController::class, 'editConfirm'])->name('team.editConfirm');
route::get('teams/edit/{confirm}', [TeamsController::class, 'edit'])->name('team.edit');

//display search view -> action search (post)---------------------------------------------------------------------------
Route::get('employees/searchEmployee', [employeesController::class, 'searchEmployee'])->name('employee.searchEmployee');
Route::get('employees/search', [employeesController::class, 'search'])->name('employee.search');

//display create view -> take in data and display confirm view (post) -> create employee (get)
Route::get('employees/createEmployee', [employeesController::class, 'createEmployee'])->name('employee.createEmployee');
Route::post('employees/create', [employeesController::class, 'createConfirm'])->name('employee.createConfirm');
route::get('employees/create/{confirm}', [employeesController::class, 'create'])->name('employee.create');

//display edit view -> take in data and display confirm view (post) -> edit employee (get)
Route::get('employees/editEmployee', [employeesController::class, 'editEmployee'])->name('employee.editEmployee');
Route::post('employees/edit', [employeesController::class, 'editConfirm'])->name('employee.editConfirm');
route::get('employees/edit/{confirm}', [employeesController::class, 'edit'])->name('employee.edit');
