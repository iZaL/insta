<?php

Route::get('home', ['as' => 'home', 'uses' => 'InstagramsController@index']);
/*********************************************************************************************************
 * Instagram Routes
 ********************************************************************************************************/
Route::get('instagrams/authorize', 'InstagramsController@authorize');
Route::get('instagrams/account/authenticate', 'InstagramsController@authenticate');
Route::get('instagrams/usearch/{username}', 'InstagramsController@getUserInfoByUsername');
Route::get('instagrams/isearch/{id}', 'InstagramsController@getUserInfoByID');
Route::get('instagrams/dislike-media/{username}/{mediaID}', 'InstagramsController@dislikeMedia');
Route::get('instagrams/like-media/{username}/{mediaID}', 'InstagramsController@likeMedia');
Route::get('instagrams/likes', 'InstagramsController@getLikes');
Route::get('instagrams/like', 'InstagramsController@getLike');

Route::get('instagrams/medias', 'InstagramsController@getMedias');
Route::get('instagrams/media', 'InstagramsController@getMedia');

Route::resource('instagrams', 'InstagramsController');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::get('/', 'InstagramsController@index');
Route::get('test', 'InstagramsController@loadMore');
