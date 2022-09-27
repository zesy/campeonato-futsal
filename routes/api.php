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
Route::namespace('App\Http\Controllers\Api\\')->group(function (){
    Route::post('/register', 'UserController@register');
    Route::post('/signin', 'UserController@signin');
    Route::middleware('auth:sanctum')->post('/logout', 'UserController@logout');
    Route::middleware('auth:sanctum')->get('/users', 'UserController@selectAll');
});

// ========================= MY ROUTES ========================= //

Route::namespace('App\Http\Controllers\Api\\')->middleware('auth:sanctum')->group(function (){
    // === TEAMS ROUTES
    Route::get('/teams', 'TeamController@selectAll');
    Route::get('/teams/{id}', 'TeamController@selectOne');
    Route::post('/teams', 'TeamController@insert');
    Route::put('/teams/{id}', 'TeamController@update');
    Route::delete('/teams/{id}', 'TeamController@delete');
    Route::patch('/teams/{id}/player/add/{pid}', 'TeamController@addPlayer');
    Route::patch('/teams/{id}/player/remove/{pid}', 'TeamController@removePlayer');

    // === PLAYERS ROUTES
    Route::get('/players', 'PlayerController@selectAll');
    Route::get('/players/{id}', 'PlayerController@selectOne');
    Route::post('/players', 'PlayerController@insert');
    Route::put('/players/{id}', 'PlayerController@update');
    Route::delete('/players/{id}', 'PlayerController@delete');
    
    // === TEAM MATCHES
    Route::get('/matches', 'TeamMatchController@selectAll');
    Route::get('/matches/{id}', 'TeamMatchController@selectOne');
    Route::post('/matches', 'TeamMatchController@insert');
    Route::put('/matches/{id}', 'TeamMatchController@update');
    Route::delete('/matches/{id}', 'TeamMatchController@delete');

    // === CLASSIFICATION
    Route::get('classification', 'ClassificationController@index');
});