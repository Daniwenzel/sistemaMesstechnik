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
        Route::get('/config/{user_id}', 'UserController@showUserConfig')->name('show.config');
        Route::post('/edit/{user_id}', 'UserController@editUserConfig')->name('edit.config');
        Route::post('/avatar/{user_id}', 'UserController@editUserAvatar')->name('edit.avatar');
        Route::get('/register', 'UserController@showRegisterUser')->name('show.register')->middleware('master');
        Route::post('/register', 'UserController@registerUser')->name('create.register')->middleware('master');
        Route::post('/delete/{user_id}', 'UserController@deleteUser')->name('delete.user');
        Route::get('/password', 'UserController@showEditPassword')->name('user.password');
        Route::post('/password/edit', 'UserController@editPassword')->name('edit.password');
    });

    Route::group(['prefix' => 'company'], function () {
        Route::get('/', 'CompanyController@showCompanyList')->name('company');
        Route::post('/register', 'CompanyController@registerCompany')->name('register.company')->middleware('admin');
        Route::get('/register', 'CompanyController@showRegisterCompany')->name('show.register.company')->middleware('admin');
        Route::post('/delete/{company_id}', 'CompanyController@deleteCompany')->name('delete.company');
    });

    Route::group(['prefix' => 'entitlement'], function () {
        Route::get('/', 'UserController@showRolesPermissions')->name('role.permission');
        Route::post('/registerpermission', 'RolePermissionController@insertPermission')->name('create.permission');
        Route::post('/roledel/{role_id}', 'RolePermissionController@deleteRole')->name('delete.role');
        Route::post('/permdel/{perm_id}', 'RolePermissionController@deletePerm')->name('delete.permission');
        Route::post('/registerrole', 'RolePermissionController@insertRole')->name('create.role');
    });

    Route::group(['prefix' => 'windfarms'], function () {
        Route::get('/', 'WindFarmController@showWindfarmList')->name('windfarm');
        Route::get('/{farm_id}', 'WindFarmController@showWindfarm')->name('windfarm.info');
        Route::get('/tower/{tower_id}', 'TowerController@showTowerInfo')->name('tower.info');
    });

    Route::get('openfile', 'SensorController@openFile')->name('open.file');

});