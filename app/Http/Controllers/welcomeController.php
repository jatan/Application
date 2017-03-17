<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Log;

class welcomeController extends Controller
{
    public function welcome(){

	    Log::debug('I am Bugs Bunny!');
	    Log::info("THis is info log");
	    Log::error("This is error log");
        return(view('common.welcome'));
    }
}
