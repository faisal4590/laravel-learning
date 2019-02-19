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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources(['users' => 'API\UserController']);//API directory er under e UserController ke use korte bollam

Route::get('profile','API\UserController@profile');//keu jokhn user er profile pawar jonno get request pathabe,
//tokhon ami je UserController.php API ta create korcilam otar profile() method ta execute kore profile er info fetch kore anbe.

Route::put('profile','API\UserController@updateProfile');