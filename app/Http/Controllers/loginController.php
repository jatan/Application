<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class loginController extends Controller
{
   public function login(){
       return view('login');
   }

    /**
     * @param loginUserRequest $request
     * @return string
     * @internal param $ Requests\loginUserRequest $
     */
    public function loginProcess(loginUserRequest $request){
        $credentials = [
            'email' => Input::get('email'),
            'password' => Input::get('password'),
            'confirmed' => 1
        ];

        /** @var TYPE_NAME $credentials */
        if ( ! Auth::attempt($credentials))
        {
            return Redirect::back()
                ->withInput()
                ->withErrors([
                    'credentials' => 'We were unable to sign you in.'
                ]);
        }
        return Redirect()->intended('user/dashboard');
    }
}
