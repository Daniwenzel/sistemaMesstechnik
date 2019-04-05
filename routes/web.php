<?php

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
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function() {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('/register', 'UserController@showRegisterUser')->name('show.register');

    Route::get('/registercompany', 'CompanyController@showRegisterCompany')->name('show.register.company');

    Route::post('/register', 'UserController@registerUser')->name('create.register');

    Route::post('registercompany', 'CompanyController@registerCompany')->name('create.company');

    Route::get('/management', 'ManagementController@index')->name('management');

    Route::get('/userlist', 'UserController@showUserList')->name('user.list');

    Route::get('companylist', 'CompanyController@showCompanyList')->name('company.list');

    Route::get('rolespermissions', 'UserController@showRolesPermissions')->name('role.permission');
});