<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/','welcomeController@welcome');
    Route::get('/login','loginController@login');

    Route::get('/register','registerController@register');
        Route::get('register/verify/{confirmationCode}',
        [
            'uses' =>'registerController@confirm'
        ]);

    Route::get('user/dashboard',
        [
            'middleware' => 'auth',
            'uses' => 'usercontroller@dashboard'
        ]);
    Route::get('user/logout',
        [
            'middleware' => 'auth',
            'uses' => 'usercontroller@logout'
        ]);
    Route::get('user/addAccount',
        [
            'middleware' => 'auth',
            'uses' => 'usercontroller@addAccount'
        ]);
    Route::post('user/addAccount','userController@addAccountProcess');

    Route::post('/register','registerController@registerProcess');
    Route::post('/login','loginController@loginProcess');

});
