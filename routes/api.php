<?php

use Illuminate\Http\Request;

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

Route::post('/register','API\APIRegisterController@store');
Route::post('/login','API\APILoginController@store');

Route::middleware('jwt.verify')->prefix('user')->group(function() {
    Route::get('/profile','API\APIUsersController@me');
    Route::put('/update','API\APIUsersController@update');
    Route::delete('/delete','API\APIUsersController@delete');
    Route::post('/upload-photo','API\APIUsersController@updatePhoto');
});


