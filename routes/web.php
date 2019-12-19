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

    Route::get('/', 'DashboardController@index')->name('dashboard.index');

    Route::get('/users', 'UserController@index')->name('users.index');
    Route::get('/users/create', 'UserController@create')->name('users.create')->middleware('master');
    Route::post('/users', 'UserController@store')->name('users.store')->middleware('master');
    Route::get('/users/{user_id}/edit', 'UserController@edit')->name('users.edit');
    Route::put('/users/{user_id}', 'UserController@update')->name('users.update');
    Route::post('/users/avatar', 'UserController@editUserAvatar')->name('edit.avatar');
    Route::delete('/users/{user_id}', 'UserController@destroy')->name('users.destroy');
    Route::get('/users/password', 'UserController@showEditPassword')->name('users.password');
    Route::post('/users/password', 'UserController@editPassword')->name('password.update');

    Route::get('/companies', 'ClientController@index')->name('companies.index');
    Route::post('/companies', 'ClientController@store')->name('companies.store');
    Route::get('/companies/create', 'ClientController@create')->name('companies.create');
    Route::delete('/companies/{company_id}', 'ClientController@delete')->name('companies.delete');

    Route::get('/rights', 'RolePermissionController@index')->name('rights.index');
    Route::post('/rights/permission', 'RolePermissionController@permissionStore')->name('rights.permission.store');
    Route::post('/rights/role', 'RolePermissionController@roleStore')->name('rights.role.store');
    Route::delete('/rights/permission/{perm_id}', 'RolePermissionController@permissionDelete')->name('permissions.delete');
    Route::delete('/rights/role/{role_id}', 'RolePermissionController@roleDelete')->name('roles.delete');

    Route::get('/windfarms', 'SiteController@index')->name('windfarms.index');
    Route::get('/windfarms/create', 'SiteController@create')->name('windfarms.create');
    Route::get('/windfarms/{farm_id}', 'SiteController@show')->name('windfarms.show');
    Route::post('/windfarms', 'SiteController@store')->name('windfarms.store');

    Route::get('/towers/{tower_id}', 'TowerController@show')->name('towers.show');
    Route::get('/towers/create/{farm_id}', 'TowerController@create')->name('towers.create');
    Route::post('/towers/{farm_id}', 'TowerController@store')->name('towers.store');

    Route::get('/reports', 'ReportController@index')->name('reports.index');


//    Route::get('/post', 'FileLogController@showPostData')->name('show.data');

//    Route::get('openfile', 'SensorController@openFile')->name('open.file');

});