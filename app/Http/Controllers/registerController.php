<?php

namespace App\Http\Controllers;


use App\Http\Requests\registerUserRequest;
use Request;
use App\User;
use Mail;
class registerController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function register()
    {
        return view('register');
    }

    /**
     * @param registerUserRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registerProcess(registerUserRequest $request)
    {
        $confirmation_code = str_random(30);

        $input = Request::all();
        $user = new User();
        $user->email = $input['email'];
        $user->password = $input['password'];
        $user->confirmation_code = $confirmation_code;
        if(User::where('email','=',$input['email'])->exists()){
            $user = User::where('email','=',$input['email'])->first();
            $user -> toArray();
        }
        else {
            $user->save();
        }
        //ini_set('xdebug.max_nesting_level', 200);
        Mail::send('email.verify', ['c_code'=>$confirmation_code], function($message) {
            $message->to(Request::get('email'))->subject('Verify your email address');
        });

        //Flash::message('Thanks for signing up! Please check your email.');
        return view('login',['from_register'=>'1']);
    }


    /**
     * @param $confirmationCode
     * @return mixed
     * @throws InvalidConfirmationCodeException
     * @internal param $confirmation_code
     */
    public function confirm($confirmationCode)
    {
        if( ! $confirmationCode)
        {
            throw new InvalidConfirmationCodeException;
        }

        $user = user::where('confirmation_code','LIKE',$confirmationCode)->first();

        if ( ! $user)
        {
           dd($user);
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();

        //Flash::message('You have successfully verified your account.');

        return Redirect::route('login');
    }
}
