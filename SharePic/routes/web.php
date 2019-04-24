<?php

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

//Route to test anyone
Route::post('/test', 'UserController@test');
//Route for Register, Login, Delete User
Route::post('/user', 'UserController@register');
Route::post('/login', 'UserController@login');
//Affiche les tout Users
Route::get('/users', 'UserController@getUsers');
//User by id
Route::get('/getUserById/{id}', 'UserController@getUserById');

Route::group([
    'middleware' => 'auth:api'
 ], function(){

     //logout
Route::post('/logout', 'UserController@logout');

     //all the image by date
Route::get('/store', 'ImageController@getStore');

     //Recherche de User By Name
Route::get('/getUserByName/{name}', 'UserController@getUserByName');

     //Route for Image
Route::get('/actu', 'ImageController@getImageFollower');
Route::get('/profil', 'ImageController@getImageUser');
Route::get('/images/{id}', 'ImageController@getImageUtilisateur');
Route::post('/store', 'ImageController@store');
Route::delete('/store/{id}', 'ImageController@deleteStore');
Route::put('/like/increase/{id}', 'ImageController@like');
Route::put('/like/decrease/{id}', 'ImageController@dislike');

    //Route for follow
Route::post('/follow/{followed}', 'UserController@follow');
Route::delete('/unfollow/{unfollowed}', 'UserController@unfollow');
Route::get('/getFollower', 'UserController@getFollower');
Route::get('/getFollowed', 'UserController@getFollowed');

     //Route for comment
Route::post('/add/comment/{id}', 'ImageController@addComment');
Route::delete('/comment', 'ImageController@deleteComment');
Route::get('/comment/{id}', 'ImageController@getComment');
});