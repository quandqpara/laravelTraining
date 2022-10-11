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



//--AUTH----------------------------------------------------------------------------------------------------------------
Route::middleware(['check.login'])->group(function () {
    Route::get('/', function () {
        return view('auth/index');
    });

    Route::prefix('auth')->group(function () {
        Route::get('', [LoginController::class, 'index'])->name('login-page');
        Route::post('/login', [LoginController::class, 'login'])->name('login');

        Route::get('/registration', [LoginController::class, 'registration'])->name('register-user');
        Route::post('/custom-registration', [LoginController::class, 'customRegistration'])->name('register.custom');

        Route::get('/logout', [LoginController::class, 'logOut'])->name('logout');
    });
});

route::middleware(['check.admin'])->group(function () {
    //--TEAM------------------------------------------------------------------------------------------------------------
    Route::prefix('teams')->group(function () {
       Route::name('team.')->group(function () {
           //display SEARCH view -> action search (post)-------------------------------------------------------------------
           Route::get('/searchTeam', [TeamsController::class, 'searchTeam'])->name('searchTeam');
           Route::get('/search/{column}/{direction}', [TeamsController::class, 'index'])->name('search');;

           //display CREATE view -> take in data and display confirm view (post) -> create team (get)
           Route::get('/createTeam', [TeamsController::class, 'createTeam'])->name('createTeam');;
           Route::post('/createConfirm', [TeamsController::class, 'createTeamConfirm'])->name('createConfirm');;
           Route::post('/create', [TeamsController::class, 'store'])->name('create');;

           //display EDIT view -> take in data and display confirm view (post) -> edit team (post)
           Route::get('/editTeam/{id}', [TeamsController::class, 'editTeam'])->where('id', '[0-9]+')->name('editTeam');;
           Route::post('/editConfirm', [TeamsController::class, 'editTeamConfirm'])->name('editConfirm');;
           Route::post('/edit/', [TeamsController::class, 'update'])->name('edit');;

           //delete team
           Route::get('/deleteTeam/{id}', [TeamsController::class, 'destroy'])->where('id', '[0-9]+')->name('delete');
       });
    });

    //--EMPLOYEE--------------------------------------------------------------------------------------------------------
    Route::prefix('employees')->group(function () {
        Route::name('employee.')->group(function () {
            //display SEARCH view -> action search (post)-------------------------------------------------------------------
            Route::get('/searchEmployee', [employeesController::class, 'searchEmployee'])->name('searchEmployee');;
            Route::get('/search', [employeesController::class, 'index'])->name('search');;

            //display CREATE view -> take in data and display confirm view (post) -> create employee (get)
            Route::get('/createEmployee', [employeesController::class, 'createEmployee'])->name('createEmployee');;
            Route::post('/createConfirm', [employeesController::class, 'createEmployeeConfirm'])->name('createConfirm');;
            Route::post('/create', [employeesController::class, 'store'])->name('create');;

            //display EDIT view -> take in data and display confirm view (post) -> edit employee (get)
            Route::get('/editEmployee/{id}', [employeesController::class, 'editEmployee'])->where('id', '[0-9]+')->name('editEmployee');;
            Route::post('/editConfirm', [employeesController::class, 'editEmployeeConfirm'])->name('editConfirm');;
            Route::post('/edit', [employeesController::class, 'update'])->name('edit');;

            //delete team
            Route::get('/deleteEmployee/{id}', [EmployeesController::class, 'destroy'])->where('id', '[0-9]+')->name('delete');
        });
    });
});



