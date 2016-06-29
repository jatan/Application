<?php

namespace App\Http\Controllers;


use App\bank_accounts;
use App\transaction;
use GuzzleHttp\Client;
use Request;
use Curl;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class userController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard(){
        //return (Auth::user()->visible_accounts());
        return (view('user.dashboard'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addAccount(){
        return(view('user.addAccount'));
    }

    public function getAccountDetails($id){
        $account = bank_accounts::find($id);
        $i = 0;
        foreach ($account->transaction as $transactions) {
            $toDisplay[$i]['name'] = $transactions->name;
            $toDisplay[$i]['amount'] = $transactions->amount;
            $toDisplay[$i]['location'] = $transactions->location_city.",".$transactions->location_state;
            $toDisplay[$i]['date'] = $transactions->date;
            $i++;
        }
        // var_dump($account ->transaction);
        //var_dump($toDisplay);
        //die;
        return view('user.getAccountDetails',['display'=>$toDisplay,'id'=>$id]);
    }

    public function sync($id){

        $account = bank_accounts::find($id);
        $token = $account ->access_token;
        var_dump($token);
        $uri = 'https://tartan.plaid.com/connect/get';
            $parameters = [
                'json' => [
                    'client_id' => env('PLAID_CLIENT_ID'),
                    'secret' => env('PLAID_SECRET'),
                    'access_token' => $token
                ]
            ];
        $client = new Client();
        $response = $client->post($uri, $parameters);
        $array = json_decode($response->getBody(), true);
        // var_dump($array);
        $transactions = $array['transactions'];
        //return $transactions;
        foreach($transactions as $transaction_key => $transaction_value){
            $transaction = transaction::find($transaction_value['_id']);
            if(!$transaction){
                $transaction = new transaction();
                $transaction['id'] = $transaction_value['_id'];

                $transaction['bank_accounts_id'] = $transaction_value['_account'];
                $transaction['amount'] = $transaction_value['amount'];
                $transaction['date'] = $transaction_value['date'];
                $transaction['name'] = $transaction_value['name'];
                if(isset($transaction_value['meta']['location']['city']))
                    $transaction['location_city'] = $transaction_value['meta']['location']['city'];
                if(isset($transaction_value['meta']['location']['state']))
                    $transaction['location_state'] = $transaction_value['meta']['location']['state'];
                $transaction['pending'] = $transaction_value['pending'];
                $transaction['type_primary'] = $transaction_value['type']['primary'];
                if(isset($transaction_value['category']))
                    $transaction['category'] = serialize($transaction_value['category']);
                if(isset($transaction_value['category_id']))
                $transaction['category_id'] = $transaction_value['category_id'];
                $transaction['score'] = serialize($transaction_value['score']);
                $transaction['plaid_core'] = serialize($transaction_value);
                var_dump($transaction);
                $transaction->save();
            }
        }
        return(redirect::to('user/'.$id.'/account'));
    }

    /**
     * @return bank_accounts
     */
    public function addAccountProcess(){
        $input = Request::all();
        var_dump($input);
        //return($input);
        if(isset($input['radio'] )){
            $uri = 'https://tartan.plaid.com/connect/step';
            $access_token = '{"send_method":{"mask":"'.$input["radio"].'"}}';
            $parameters = [
                'json' => [
                    'client_id' => env('PLAID_CLIENT_ID'),
                    'secret' => env('PLAID_SECRET'),
                    'access_token' => $input['access_token'],
                    'options' => $options
                ]
            ];
        }else if(isset($input['access_token'] )){
            $uri = 'https://tartan.plaid.com/connect/step';
            $parameters = [
                'json' => [
                    'client_id' => env('PLAID_CLIENT_ID'),
                    'secret' => env('PLAID_SECRET'),
                    'access_token' => $input['access_token'],
                    'mfa' => $input['ans']
                ]
            ];
        }else {
            $uri = 'https://tartan.plaid.com/connect';
            $parameters = [
                'json' => [
                    'client_id' => env('PLAID_CLIENT_ID'),
                    'secret' => env('PLAID_SECRET'),
                    'username' => $input['username'],
                    'password' => $input['password'],
                    'type' => $input['bank'],
                    'options' => '{"list":true}'
                ]
            ];
        }
        //curl.cainfo ="{filepath}\cacert.pem" add this line to php.ini & cacert.pem file to location
        $client = new Client();
        $response = $client->post($uri, $parameters);
        $array = json_decode($response->getBody(), true);
        //return ($response);
        if(isset($array['type'] )) {
            if ($array['type'] == "questions") {
                $mfa = $array['mfa'][0];
                $pass = $mfa['question'];
                return (view('user.addAccountStep')->with('pass', $pass)->with('access_token',$array['access_token']));
            } else if ($array['type'] == "device") {
                $mfa = $array['mfa'];
                $pass = $mfa['message'];
                return (view('user.addAccountStep')->with('pass', $pass)->with('access_token',$array['access_token']));
            } else if ($array['type'] == "list") {
                $mfa = $array['mfa'][0];
                $pass = $array['mfa'];
                return (view('user.addAccountStep')->with('pass', $pass)->with('access_token',$array['access_token']));
            }
        }
        $accounts = $array['accounts'];
        //return (dump($accounts));
        foreach($accounts as $account_key => $account_value ){
            $bank_account= new bank_accounts();
            $bank_account['id']=$account_value['_id'];
            $bank_account['user_id']=Auth::user()->id;
            $bank_account['access_token'] = $array['access_token'];
            //if(isset($account_value['balance']['current']))
            $bank_account['current_balance'] = $account_value['balance']['current'];
            //if(isset($account_value['balance']['available']))
            $bank_account['available_balance'] = $account_value['balance']['available'];
            $bank_account['bank_name'] = $account_value['institution_type'];

            if(isset($account_value['meta']['acc_limit']))
                $bank_account['acc_limit'] = $account_value['meta']['acc_limit'];

            if(isset($account_value['subtype']))
            $bank_account['account_subtype'] = $account_value['subtype'];

            $bank_account['name'] = $account_value['meta']['name'];
            $bank_account['number'] = $account_value['meta']['number'];
            $bank_account['account_type'] = $account_value['type'];
            $bank_account['plaid_core'] = serialize($account_value);
            $bank_account->save();
        }

        $transactions = $array['transactions'];
        //return $transactions;
        foreach($transactions as $transaction_key => $transaction_value){
            $transaction = new transaction();
            $transaction['bank_accounts_id'] = $transaction_value['_account'];
            $transaction['amount'] = $transaction_value['amount'];
            $transaction['date'] = $transaction_value['date'];
            $transaction['name'] = $transaction_value['name'];
            if(isset($transaction_value['meta']['location']['city']))
                $transaction['location_city'] = $transaction_value['meta']['location']['city'];
            if(isset($transaction_value['meta']['location']['state']))
                $transaction['location_state'] = $transaction_value['meta']['location']['state'];
            $transaction['pending'] = $transaction_value['pending'];
            $transaction['type_primary'] = $transaction_value['type']['primary'];
            if(isset($transaction_value['category']))
                $transaction['category'] = serialize($transaction_value['category']);
            if(isset($transaction_value['category_id']))
            $transaction['category_id'] = $transaction_value['category_id'];
            $transaction['score'] = serialize($transaction_value['score']);
            $transaction['plaid_core'] = serialize($transaction_value);
            $transaction->save();
        }
        //return $transaction;
        return(redirect::to('user/dashboard'));
    }

    /**
     *
     */
    public function logout(){
        auth::logout();
        return(Redirect()->intended('login'));
    }

    public function delete($id){
        $account = bank_accounts::find($id);
        $token = $account->access_token;
        $uri = 'https://tartan.plaid.com/connect';
        $parameters = [
            'json' => [
                'client_id' => env('PLAID_CLIENT_ID'),
                'secret' => env('PLAID_SECRET'),
                'access_token' => $token
            ]
        ];
        $client = new Client();
        $response = $client->delete($uri, $parameters);
        $array = json_decode($response->getBody(), true);
        bank_accounts::where('access_token',$token)->delete();
        return($array) ;
    }

    public function hide($id){
        bank_accounts::find($id)->update(['hidden_flag'=> 1]);
        return(redirect::to('user/dashboard'));
    }

    public function unhide($id){
        bank_accounts::find($id)->update(['hidden_flag'=> 0]);
        return(redirect::to('user/dashboard'));
    }

    public function budget(){

        return (view('user.budget'));
    }
}
