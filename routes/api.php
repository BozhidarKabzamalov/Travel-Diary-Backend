<?php

use Illuminate\Http\Request;

Route::get('/getMarkers/{username}', 'MarkerController@getMarkers');
Route::get('/getCities/{username}', 'MarkerController@getCities');
Route::get('/getSights/{lat}/{lng}/{type}/{range}/{pageToken?}', 'MarkerController@getSights');
Route::get('/getSight/{placeId}/{userId?}', 'MarkerController@getSight');
Route::get('/get/{source}/{username}', 'PlaceController@getSource');

Route::post('/signup', 'UserController@signup');
Route::post('/signin', 'UserController@signin');

Route::group(['middleware' => ['auth.jwt']], function(){
    Route::post('/createMarker', 'MarkerController@createMarker');
    Route::post('/deleteMarker', 'MarkerController@deleteMarker');
    Route::post('/create/{source}', 'PlaceController@createSource');
});
