<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::resource('cliente', 'App\Http\Controllers\ClienteController');
Route::apiResource('cliente', 'App\Http\Controllers\ClienteController')->middleware('jwt.login');
Route::apiResource('carro', 'App\Http\Controllers\CarroController')->middleware('jwt.login');
Route::apiResource('locacao', 'App\Http\Controllers\LocacaoController')->middleware('jwt.login');
Route::apiResource('marca', 'App\Http\Controllers\MarcaController')->middleware('jwt.login');
Route::apiResource('modelo', 'App\Http\Controllers\ModeloController')->middleware('jwt.login');

Route::post('login', 'App\Http\Controllers\AuthController@login');
Route::post('logout','App\Http\Controllers\AuthController@logout');
Route::post('refresh','App\Http\Controllers\AuthController@refresh');
Route::post('me','App\Http\Controllers\AuthController@me');
