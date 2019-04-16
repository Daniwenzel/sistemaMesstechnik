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

    Route::get('/', 'DashboardController@showDashboard')->name('dashboard');

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', 'UserController@showUserList')->name('user');
        Route::get('/config/{user_id}', 'UserController@showUserConfig')->name('user.config');
        Route::get('/register', 'UserController@showRegisterUser')->name('show.register');
        Route::post('/register', 'UserController@registerUser')->name('create.register');
        Route::post('/delete/{user_id}', 'UserController@deleteUser')->name('delete.user');
    });

    Route::group(['prefix' => 'company'], function () {
        Route::get('/', 'CompanyController@showCompanyList')->name('company');
        Route::post('/register', 'CompanyController@registerCompany')->name('create.company');
        Route::get('/register', 'CompanyController@showRegisterCompany')->name('show.register.company');
        Route::post('/delete/{company_id}', 'CompanyController@deleteCompany')->name('delete.company');
    });

    Route::group(['prefix' => 'entitlement'], function () {
        Route::get('/', 'UserController@showRolesPermissions')->name('role.permission');
        Route::post('/registerpermission', 'RolePermissionController@insertPermission')->name('create.permission');
        Route::post('/roledel/{role_id}', 'RolePermissionController@deleteRole')->name('delete.role');
        Route::post('/permdel/{perm_id}', 'RolePermissionController@deletePerm')->name('delete.permission');
        Route::post('/registerrole', 'RolePermissionController@insertRole')->name('create.role');
    });

    Route::get('windfarms', 'WindFarmController@showWindfarmList')->name('windfarm');
    Route::get('windfarms/{farm_id}', 'WindFarmController@showWindfarm')->name('windfarm.info');

    Route::get('windfarms/tower/{tower_id}', 'TowerController@showTowerInfo')->name('tower.info');

    Route::get('openfile', 'SensorController@openFile')->name('open.file');

});