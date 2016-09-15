<?php


Route::group(['middleware' => ['web']], function () {

    //Landing Page
    Route::get('/','welcomeController@welcome');

    //Registration
    Route::get('/register','registerController@register');
    Route::post('/register','registerController@registerProcess');
    Route::get('register/verify/{confirmationCode}','registerController@confirm');

    // Login-Logout
    Route::get('/login','loginController@login');
    Route::post('/login','loginController@loginProcess');
    Route::get('user/logout',['middleware' => 'auth','uses' => 'loginController@logout']);

    //Dashboard
    Route::get('user/dashboard',['middleware' => 'auth', 'uses' => 'usercontroller@dashboard']);

    //Transactions
    Route::get('user/transaction',['middleware' => 'auth', 'uses' => 'transactionController@index']);
    Route::get('user/transaction/getAll',['middleware' => 'auth', 'uses' => 'transactionController@getTransactions']);
    Route::get('user/transaction/getbyId/{id}',['middleware' => 'auth', 'uses' => 'transactionController@getTransaction_byId']);
    Route::get('user/transaction/create',['middleware' => 'auth', 'uses' => 'transactionController@createTransaction']);
    Route::get('user/transaction/update/{id}',['middleware' => 'auth', 'uses' => 'transactionController@updateTransaction_byId']);
    Route::get('user/transaction/delete/{id}',['middleware' => 'auth', 'uses' => 'transactionController@deleteTransaction_byId']);

    //Budgets
    Route::get('user/budget',['middleware' => 'auth', 'uses' => 'budgetController@index']);
    Route::get('user/budget/getAll',['middleware' => 'auth', 'uses' => 'budgetController@getBudgets']);
    Route::get('user/budget/getbyId/{id}',['middleware' => 'auth', 'uses' => 'budgetController@getBudget_byId']);
    Route::get('user/budget/create',['middleware' => 'auth', 'uses' => 'budgetController@createBudget']);
    Route::get('user/budget/update/{id}',['middleware' => 'auth', 'uses' => 'budgetController@updateBudget_byId']);
    Route::get('user/budget/delete/{id}',['middleware' => 'auth', 'uses' => 'budgetController@deleteBudget_byId']);

    //Accounts
    Route::get('user/account',['middleware' => 'auth', 'uses' => 'accountController@index']);
    Route::get ('user/account/getAll',['middleware' => 'auth', 'uses' => 'accountController@getAccounts']);
    Route::post('user/account/getbyId/{id}',['middleware' => 'auth', 'uses' => 'accountController@getAccount_byId']);
    Route::get ('user/account/create',['middleware' => 'auth', 'uses' => 'accountController@createAccount']);
    Route::post('user/account/create',['middleware' => 'auth', 'uses' => 'accountController@createAccount_process']);
    Route::get('user/account/update/{id}',['middleware' => 'auth', 'uses' => 'accountController@updateAccount_byId']);
    Route::get('user/account/delete/{token}',['middleware' => 'auth', 'uses' => 'accountController@deleteAccount']);
	Route::post('user/account/hide_toggle/{id}',['middleware' => 'auth', 'uses' => 'accountController@hideToggle']);
	Route::post('user/account/sync/{id}',['middleware' => 'auth', 'uses' => 'accountController@syncAccount']);
    //Bills

    Route::get('user/bill',['middleware' => 'auth', 'uses' => 'billController@index']);
    Route::get('user/bill/getAll',['middleware' => 'auth', 'uses' => 'billController@getBills']);
    Route::get('user/bill/getbyId/{id}',['middleware' => 'auth', 'uses' => 'billController@getBill_byId']);
    Route::get('user/bill/create',['middleware' => 'auth', 'uses' => 'billController@createBill']);
    Route::get('user/bill/update/{id}',['middleware' => 'auth', 'uses' => 'billController@updateBill_byId']);
    Route::get('user/bill/delete/{id}',['middleware' => 'auth', 'uses' => 'billController@deleteBill_byId']);

    //Profile
    Route::get('user/profile',['middleware' => 'auth', 'uses' => 'profileController@index']);
    Route::get('user/profile/getAll',['middleware' => 'auth', 'uses' => 'profileController@getProfiles']);
    Route::get('user/profile/getbyId/{id}',['middleware' => 'auth', 'uses' => 'profileController@getProfile_byId']);
    Route::get('user/profile/create',['middleware' => 'auth', 'uses' => 'profileController@createProfile']);
    Route::get('user/profile/update/{id}',['middleware' => 'auth', 'uses' => 'profileController@updateProfile_byId']);
    Route::get('user/profile/delete/{id}',['middleware' => 'auth', 'uses' => 'profileController@deleteProfile_byId']);

    //Categories
    Route::get('/categories_reload','categoryController@reloadCategories');
    Route::get('/categories','categoryController@viewCategories');

    Route::get('/longtail', 'userController@longtail');

});
