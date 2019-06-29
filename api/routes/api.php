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

Route::get('/test', function (Request $request) {
   return '';
});

Route::post('/users', 'AuthenticationController@add');
Route::post('/users/login', 'AuthenticationController@login');
Route::get('/users/{id}', 'AuthenticationController@getById');

Route::get('/languages', 'LanguagesController@getAll');
Route::get('/skills', 'SkillsController@getAll');

Route::get('/fields', 'FieldsController@getAll');
Route::get('/fields/{name}', 'FieldsController@getByName');

Route::get('/specialities/{name}', 'SpecialitiesController@getByName');

Route::get('/courses/{id}', 'CoursesController@getById');
Route::get('/courses/{id}/dashboard', 'CoursesController@getDashboard');

Route::get('/lessons/{id}', 'LessonsController@getById');

Route::get('/exams/{id}', 'ExamController@getById');
