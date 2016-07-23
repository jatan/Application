<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class welcomeController extends Controller
{
    public function welcome(){
        return(view('common.welcome'));
    }
}
