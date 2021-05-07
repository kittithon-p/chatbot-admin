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

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'category'
    ],
    function ($router) {
        Route::post('/store', 'App\Http\Controllers\CategoryController@store');
        Route::get('/index', 'App\Http\Controllers\CategoryController@index');
        Route::get('/show/{id}', 'App\Http\Controllers\CategoryController@show');
        Route::put('/update/{id}', 'App\Http\Controllers\CategoryController@update');
        Route::delete('/destroy/{id}', 'App\Http\Controllers\CategoryController@destroy');
    }
);

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'keyword'
    ],
    function ($router) {
        Route::post('/store', 'App\Http\Controllers\KeywordController@store');
        Route::get('/index', 'App\Http\Controllers\KeywordController@index');
        Route::get('/show/{id}', 'App\Http\Controllers\KeywordController@show');
        Route::put('/update/{id}', 'App\Http\Controllers\KeywordController@update');
        Route::delete('/destroy/{id}', 'App\Http\Controllers\KeywordController@destroy');
    }
);

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'answers'
    ],
    function ($router) {
        Route::post('/store', 'App\Http\Controllers\AnswersController@store');
        Route::get('/index', 'App\Http\Controllers\AnswersController@index');
        Route::get('/show/{id}', 'App\Http\Controllers\AnswersController@show');
        Route::put('/update/{id}', 'App\Http\Controllers\AnswersController@update');
        Route::delete('/destroy/{id}', 'App\Http\Controllers\AnswersController@destroy');
    }
);
Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'question'
    ],
    function ($router) {
        Route::post('/store', 'App\Http\Controllers\QuestionController@store');
        Route::get('/index', 'App\Http\Controllers\QuestionController@index');
        Route::get('/show/{id}', 'App\Http\Controllers\QuestionController@show');
        Route::put('/update/{id}', 'App\Http\Controllers\QuestionController@update');
        Route::delete('/destroy/{id}', 'App\Http\Controllers\QuestionController@destroy');
    }
);
