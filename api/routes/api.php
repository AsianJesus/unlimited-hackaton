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

Route::get('/test', function (Request $request) {
   return date('Y-m-d');
});
Route::get('/test/time', function (Request $request) {
   return \App\Helper\Helper::getWeek();
});
Route::get('/test/date', function (Request $request) {
   $date = new DateTime();
   $date->modify("-1 week");
   return $date->format('Y-m-d');
});

Route::post('/users', 'AuthenticationController@add');
Route::post('/users/login', 'AuthenticationController@login');
Route::get('/users/{id}', 'AuthenticationController@getById');
Route::get('/user', 'AuthenticationController@getMyInfo');
Route::post('/user', 'AuthenticationController@changeUserInfo');

Route::get('/languages', 'LanguagesController@getAll');
Route::get('/skills', 'SkillsController@getAll');

Route::get('/fields', 'FieldsController@getAll');
Route::get('/fields/{name}', 'FieldsController@getByName');

Route::get('/specialities/{name}', 'SpecialitiesController@getByName');

Route::get('/courses/{id}', 'CoursesController@getById');
Route::get('/courses/{id}/check', 'CoursesController@checkIfGoing');
Route::post('/courses/{id}', 'CoursesController@join');
Route::delete('/courses/{id}', 'CoursesController@exit');
Route::get('/courses/{id}/dashboard', 'CoursesController@getDashboard');

Route::get('/lessons/{id}', 'LessonsController@getById');
Route::post('/lessons/{id}', 'LessonsController@pass');

Route::get('/exams/{id}', 'ExamController@getById');
Route::post('/exams/{id}', 'ExamController@pass');

Route::get('/statistics/points', 'StatisticsController@getPointsStatistics');
Route::get('/statistics/week', 'StatisticsController@getWeekInformation');
Route::get('/statistics/skill-gain', 'StatisticsController@getUserSkillsInfo');
Route::get('/statistics/last-lessons', 'StatisticsController@getLastLessons');
Route::get('/statistics/skills', 'StatisticsController@getUserSkills');

Route::get('/friends', 'FriendsController@getFriends');
Route::post('/friends/{id}', 'FriendsController@addFriend');
Route::delete('/friends/{id}', 'FriendsController@deleteFriend');

Route::get('/chats', 'MessagesController@getChats');
Route::get('/chats/{id}', 'MessagesController@getMessages');
Route::post('/chats/{id}', 'MessagesController@sendMessage');
