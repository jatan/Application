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
    Route::get('/user/{id}/delete','usercontroller@delete');
    Route::get('/user/{id}/hide','usercontroller@hide');
    Route::get('/user/{id}/unhide','usercontroller@unhide');

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
    Route::get('user/budget',
        [
            'middleware' => 'auth',
            'uses' => 'usercontroller@budget'
        ]);
    Route::get('user/newBudget',
        [
            'middleware' => 'auth',
            'uses' => 'usercontroller@Processbudget'
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
    Route::get('user/addAccountStep',
        [
            'middleware' => 'auth',
            'uses' => 'usercontroller@addAccountStep'
        ]);

    Route::get('user/{id}/account',
        [
            'middleware' => 'auth',
            'uses' => 'usercontroller@getAccountDetails'
        ]);

    Route::get('user/{id}/sync',
        [
            'middleware' => 'auth',
            'uses' => 'usercontroller@sync'
        ]);

    Route::post('user/addAccount','userController@addAccountProcess');

    Route::post('/register','registerController@registerProcess');
    Route::post('/login','loginController@loginProcess');

    Route::get('/categories','categoryController@pullCategories');
});
