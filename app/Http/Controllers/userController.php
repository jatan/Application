<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class userController extends Controller
{
	public function dashboard(){
		return (view('user.dashboard'));
	}
}
