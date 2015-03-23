<?php

Route::get('home', ['as' => 'home', 'uses' => 'InstagramsController@index']);
/*********************************************************************************************************
 * Instagram Routes
 ********************************************************************************************************/
Route::get('instagrams/{id}/authenticate','InstagramsController@authenticate');
Route::get('instagrams/account/confirm-authenticate','InstagramsController@confirmAuthenticate');
Route::resource('instagrams', 'InstagramsController');
//Route::get('/', function () {
//    $url = 'https://api.instagram.com/v1';
//    $user=$_ENV['INSTA_USER'];
//    $instaID= $_ENV['INSTA_ID'];
//    $instaSecret = $_ENV['INSTA_SECRET'];
////    $file = file_get_contents($url.'/media/popular?client_id='.$instaID);
////    https://api.instagram.com/v1/media/{media-id}?access_token=ACCESS-TOKEN
//    $json = file_get_contents($url.'/users/'.$_ENV['INSTA_USER'].'/?client_id='.$_ENV['INSTA_ID']);
//    $j = json_decode($json, true);
//
//    echo $j['data']['username'];
//    dd($j['data']['username']);
//    return $j;
//https://instagram.com/oauth/authorize/?client_id=37ef9ee4f70d488d9b1ffb3b8f14b4b6&redirect_uri=http://insta.app/instagrams/account/authenticate/?&response_type=code&scope=likes+comments
//1572144928.37ef9ee.60b83a1436ae486785b08c26473c95da
//https://api.instagram.com/v1/users/self/feed?access_token=1572144928.37ef9ee.60b83a1436ae486785b08c26473c95da

//});

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::get('test', function () {
    // get user info
    App::make('Instagram');
    $url = 'https://api.instagram.com/v1/users/search?q=zals88&client_id=' . $_ENV['INSTA_ID'];
    dd(file_get_contents($url));
});

Route::get('/', 'InstagramsController@index');
