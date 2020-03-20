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
    Route::get('/users/create', 'UserController@create')->name('users.create');
    Route::post('/users', 'UserController@store')->name('users.store');
    Route::get('/users/{user_id}/edit', 'UserController@edit')->name('users.edit');
    Route::put('/users/{user_id}', 'UserController@update')->name('users.update');
    Route::post('/users/avatar', 'UserController@editUserAvatar')->name('edit.avatar');
    Route::delete('/users/{user_id}', 'UserController@destroy')->name('users.destroy');
    Route::get('/users/password', 'UserController@showEditPassword')->name('users.password');
    Route::post('/users/password', 'UserController@editPassword')->name('password.update');

    Route::get('/companies', 'CompanyController@index')->name('companies.index');
    Route::post('/companies', 'CompanyController@store')->name('companies.store');
    Route::get('/companies/create', 'CompanyController@create')->name('companies.create');
    Route::delete('/companies/{company_id}', 'CompanyController@delete')->name('companies.delete');

    Route::get('/windfarms', 'WindFarmController@index')->name('windfarms.index');
    Route::get('/windfarms/create', 'WindFarmController@create')->name('windfarms.create');
    Route::get('/windfarms/{farm_id}', 'WindFarmController@show')->name('windfarms.show');
    Route::post('/windfarms', 'WindFarmController@store')->name('windfarms.store');

    Route::get('/towers/{tower_id}', 'TowerController@show')->name('towers.show');
    Route::get('/towers/create/{farm_id}', 'TowerController@create')->name('towers.create');
    Route::post('/towers/{farm_id}', 'TowerController@store')->name('towers.store');

    Route::get('/reports', 'ReportController@index')->name('reports.index');
    Route::get('/reports/compare', 'ReportController@showCompare')->name('reports.compare.show');
    Route::get('/reports/ajaxCompare', 'ReportController@compare')->name('reports.compare');
    Route::get('/reports/plots/{folder}', 'ReportController@showPlots')->name('reports.plots');
    Route::get('/reports/list', 'ReportController@list')->name('reports.list');

    Route::get('/log', 'FileLogController@showFileLog')->name('log');

//    Route::get('/post', 'FileLogController@showPostData')->name('show.data');

//    Route::get('openfile', 'SensorController@openFile')->name('open.file');

});
