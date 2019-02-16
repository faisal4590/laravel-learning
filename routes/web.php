<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// nicher line ta always add kore dibo sobar last e . etar mane url e jekono path paile oita jate ney.
//for ex : localhost/profile nicce na caz erokom kono routes upore define kora nai. localhost/home/profile hole nito
//sejonno regular expression use korlam.
Route::get('{path}', "HomeController@index")->where('path', '([A-z\d-\/_.]+)?');