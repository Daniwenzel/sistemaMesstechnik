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
    
    // Route::delete('/deletarUsuario', 'UserController@destroy')->name('users.destroy');

    Route::delete('/users/{user_id}', 'UserController@destroy')->name('users.destroy');
    Route::get('/users/password', 'UserController@showEditPassword')->name('users.password');
    Route::post('/users/password', 'UserController@editPassword')->name('password.update');

    Route::get('/reports', 'ReportController@index')->name('reports.index');
    Route::get('/reports/plots/{folder}', 'ReportController@showPlots')->name('reports.plots');
    Route::get('/reports/list', 'ReportController@showTorresList')->name('reports.list');
    Route::get('/reports/generate', 'ReportController@showGenerate')->name('reports.generate.show');
    Route::get('/reports/ajaxGenerate', 'ReportController@generate')->name('reports.generate');
    Route::get('/reports/compare', 'ReportController@showCompare')->name('reports.compare.show');
    Route::get('/reports/ajaxCompare', 'ReportController@compare')->name('reports.compare');
    Route::post('/reports/torreepe', 'ReportController@generateEpe')->name('reports.torreepe');
    Route::post('/reports/compareEpe', 'ReportController@compareEpe')->name('reports.compareepe');

    // Route::get('/clients', 'SiteController@index')->name('company.index');
    Route::get('/clients/{clicodigo}', 'SiteController@mostrarClienteSites')->name('company.sites');

    Route::get('/log', 'LogController@showLog')->name('log.index');

    Route::get('/station/{sitcodigo}', 'SiteController@showSite')->name('site.index');

    Route::get('/stations', 'SiteController@index')->name('stations.index');

    Route::post('/adicionarAtendimentoTorre', 'SiteController@adicionarAtendimentoTorre')->name('adicionar.atendimento');
    Route::post('/adicionarPendenciaTorre', 'SiteController@adicionarPendenciaTorre')->name('adicionar.pendencia');
    Route::post('/adicionarArquivoTorre', 'SiteController@adicionarArquivoTorre')->name('adicionar.arquivo');

    Route::get('/generateCsv', 'ReportController@generateCsv')->name('generateCsv');

    Route::get('/getAtendimentosTorre/{sitcodigo}', 'SiteController@mostrarAtendimentosTorre')->name('show.atendimentos');
    Route::get('/getPendenciasTorre/{sitcodigo}', 'SiteController@mostrarPendenciasTorre')->name('show.pendencias');

//    Route::get('/post', 'FileLogController@showPostData')->name('show.data');

//    Route::get('openfile', 'SensorController@openFile')->name('open.file');

});
