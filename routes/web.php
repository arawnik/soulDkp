<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/login', 'SoulAuthController@LoginView')->name('login');
Route::post('/login', 'SoulAuthController@authenticate');

Route::get('/logout', function () {
    Auth::logout();
	return redirect()->route('home');
});

// Routes for non authenticated users
Route::get('/', 'HomeController@index')->name('home');
Route::get('raids', 'HomeController@raidList');
Route::get('stats', 'HomeController@statsList');

Route::get('raid/{id}', 'HomeController@specificRaid');
Route::get('char/{id}', 'HomeController@specificCharacter');



//Routes for authenticated users.
Route::get('/user', 'UserController@index');

Route::post('/raid_management', 'UserController@addRaid')->name('add_raid');
Route::post('/update_raid', 'UserController@updateRaid')->name('update_raid');
Route::post('/delete_raid', 'UserController@deleteRaid')->name('delete_raid');
Route::get('/raid_management', 'UserController@raidManagement');
Route::get('/modify_raid/{id}', 'UserController@modifySpecificRaid')->name('modify_raid/{id}');

Route::post('/modify_raid/attendance', 'UserController@modifyRaidAttendance')->name('modify_specific_raid_attendance');
Route::post('/modify_raid/item', 'UserController@addRaidItem')->name('add_raid_item');
Route::post('/delete_raid/item', 'UserController@deleteRaidItem')->name('delete_raid_item'); //Had to use post and different name for some weird issues with routing... (couldnt get delete method to work..)
Route::post('/modify_raid/adjustment', 'UserController@addRaidAdjustment')->name('add_raid_adjustment');
Route::post('/delete_raid/adjustment', 'UserController@deleteRaidAdjustment')->name('delete_raid_adjustment');

Route::post('/normalization_management', 'UserController@addNormalization')->name('add_normalization');
Route::post('/delete_normalization', 'UserController@deleteNormalization')->name('delete_normalization');
Route::post('/update_normalization_points', 'UserController@updateNormalizationPoints')->name('update_normalization_points');
Route::get('/normalization_management', 'UserController@normalizationManagement')->name('normalization_management');
Route::get('/modify_latest_normalization', 'UserController@modifyLatestNormalization')->name('modify_latest_normalization');

Route::post('/character_management', 'UserController@addCharacter')->name('add_character');
Route::post('/update_character', 'UserController@updateCharacter')->name('update_character');
Route::post('/delete_character', 'UserController@deleteCharacter')->name('delete_character');
Route::get('/character_management', 'UserController@characterManagement')->name('character_management');
Route::get('/modify_character/{id}', 'UserController@modifySpecificCharacter')->name('modify_character/{id}');



