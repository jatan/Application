<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class userController extends Controller
{
    /**
     *
     */
    public function dashboard(){

        return (view('user.dashboard'));

    }

    public function addAccount(){
        return(view('user.addAccount'));
    }

    public function addAccountProcess(){
        //Code to plaid/Auth/Add bank data goes here
    }

    /**
     *
     */
    public function logout(){
        auth::logout();
        return(Redirect()->intended('login'));
    }
}
