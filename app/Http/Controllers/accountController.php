<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\bank_accounts;
use App\transaction;
use Request;
use GuzzleHttp\Client;
use Curl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Log;
use Carbon\Carbon;

class accountController extends Controller
{
	public function index(){
		return (view('account.ac_index'));
	}

	public function createAccount(){
		$resp = view('account.ac_create')->render();
		return ($resp);
	}

	public function createAccount_process(){
		if(Request::ajax()) {

			$input = Request::all();

			Log::info($input);

			if (isset($input['radio'])) {
				Log::info("step1");
				$uri = 'https://tartan.plaid.com/connect/step';
				$options = '{"send_method":{"mask":"' . $input["radio"] . '"}}';
				$parameters = [
					'json' => [
						'client_id' => env('PLAID_CLIENT_ID'),
						'secret' => env('PLAID_SECRET'),
						'access_token' => $input['access_token'],
						'options' => $options
					]
				];
			} else if (isset($input['access_token'])) {
				Log::info("step2");
				$uri = 'https://tartan.plaid.com/connect/step';
				$parameters = [
					'json' => [
						'client_id' => env('PLAID_CLIENT_ID'),
						'secret' => env('PLAID_SECRET'),
						'access_token' => $input['access_token'],
						'mfa' => $input['ans']
					]
				];
			} else {
				Log::info("step3");
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
			Log::info("stepCommon");
			if (isset($array['type'])) {
				Log::info("step4");
				Log::info("URI Used: ".$uri);
				//Log::info("Response: ".toArray($parameters['json']));
				if ($array['type'] == "questions") {
					Log::info("step5");
					$mfa = $array['mfa'][0];
					$pass = $mfa['question'];
					return (view('account.ac_create_p')->with('pass', $pass)->with('access_token', $array['access_token']));
				} else if ($array['type'] == "device") {
					$mfa = $array['mfa'];
					$pass = $mfa['message'];
					return (view('account.ac_create_p')->with('pass', $pass)->with('access_token', $array['access_token']));
				} else if ($array['type'] == "list") {
					$mfa = $array['mfa'][0];
					$pass = $array['mfa'];
					return (view('account.ac_create_p')->with('pass', $pass)->with('access_token', $array['access_token']));
				}
			}
			//Save each account returned for the given account credentials for a given Bank.
			$accounts = $array['accounts'];
			$accessToken = $array['access_token'];
			foreach($accounts as $account_key => $account_value ){
				$this->setAccount($account_value, $accessToken);
			}

			//Save all the transactions from each account of the given Bank.
			$transactions = $array['transactions'];
			foreach($transactions as $transaction_key => $transaction_value){
				$this->setTransaction($transaction_value, $accessToken);
			}

			return (redirect::to('user/dashboard'));
		}
	}

	public function getAccount_byId($id){
		$account = bank_accounts::find($id);
		$data = '<h3>Read Operation Successful for ID: '.$id.' and its Current balance is: '.$account['current_balance'].'</h3>';
		return ($data);
	}

	public function getAccounts(){
		// $uri = 'https://tartan.plaid.com/info/get';
		// $parameters = [
		// 		'json' => [
		// 				'client_id' => env('PLAID_CLIENT_ID'),
		// 				'secret' => env('PLAID_SECRET'),
		// 				'access_token' => 'd33a1149288034e8618bb70e2dec4961de17e2b0e71a05a22ebf55401de77d12d1bb8e29ffb9e99da54a02cde98b48e01e3ed2b63eb56ea2a9976f23b4ad7cd6632f6df7e9421807dd51e9b824802667',
		//
		// 		]
		// ];
		//
		// $client = new Client();
		// $response = $client->post($uri, $parameters);
		// $array = json_decode($response->getBody(), true);
		//



		$accounts = Auth::user()->accounts->groupBy('access_token');
		$resp = view('account.ac_getAll')->with('accounts', $accounts);
		return ($resp);
	}

	public function updateAccount_byId($id){
		$data = '<h3>Update Operation Successful for ID: '.$id.'</h3>';
		return ($data);
	}

	public function deleteAccount($token){

		$accounts = bank_accounts::all()->where('access_token', $token);
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

		Log::info($array);

		$respText = "";
		if (strpos($array['message'], 'Success') !== false) {				// If response message contains Success
			foreach ($accounts as $account) {
				//Log::info($account);
				if($account->delete()){
					$respText = $respText."<h3>".$account['name']." Account Deleted successfully</h3>";
				}
			}
		}
		else {
			$respText = $array['message'];
		}

		return ($respText);

	}

	public function hideToggle($id){
		$account = bank_accounts::find($id);
		if ($account->hidden_flag == false)
			$account->update(['hidden_flag' => 1]);
		else $account->update(['hidden_flag' => 0]);

		return(redirect::to('user/account/getAll'));
	}

	public function syncAccount($id){

		$account = bank_accounts::find($id);
		$token = $account ->access_token;

		$options = ["pending" => true,
					"account" => str_replace($token, '', $account->id)];

		// 'options' => '{"pending":"true",
		// 							 "gte":"2014-05-17",
		// 							 "lte":"2014-07-01"
		// 						}'
		$uri = 'https://tartan.plaid.com/connect/get';
		$parameters = [
				'json' => [
						'client_id' => env('PLAID_CLIENT_ID'),
						'secret' => env('PLAID_SECRET'),
						'access_token' => $token,
						'options' => json_encode($options)
				]
		];

		$client = new Client();
		$response = $client->post($uri, $parameters);
		$array = json_decode($response->getBody(), true);

		$accounts = $array['accounts'];
		$transactions = $array['transactions'];

		foreach($accounts as $account_key => $account_value ){
			$bank_account = bank_accounts::find($token.$account_value['_id']);
			
			if(isset($bank_account) && $id == $token.$account_value['_id']){
				$bank_account['current_balance'] = $account_value['balance']['current'];
				$bank_account['available_balance'] = $account_value['balance']['available'];
				if(isset($account_value['meta']['limit'])){
					$bank_account['acc_limit'] = $account_value['meta']['limit'];
				}
					$bank_account['LastSynced_at'] = Carbon::now();

					$bank_account['SyncCount'] = $bank_account['SyncCount']+1;
				$bank_account ->save();
			}
		}

		foreach($transactions as $transaction_key => $transaction_value){

			if(str_contains($token,'test')){
				$transaction = transaction::find($token.$transaction_value['_id']);
			}
			else{
				$transaction = transaction::find($transaction_value['_id']);
			}

			if(!$transaction){
				$this->setTransaction($transaction_value, $token);
			}
		}
		return(redirect::to('user/account/getAll'));
	}



	//Stores given single account to DB
	private function setAccount($account_value, $accessToken){
		$bank_account= new bank_accounts();
		if(str_contains($accessToken,'test')){
			$bank_account['id']=$accessToken.$account_value['_id'];
		}
		else{
			$bank_account['id']=$account_value['_id'];
		}
		$bank_account['user_id']=Auth::user()->id;
		$bank_account['access_token'] = $accessToken;
		//if(isset($account_value['balance']['current']))
		$bank_account['current_balance'] = $account_value['balance']['current'];
		//if(isset($account_value['balance']['available']))
		$bank_account['available_balance'] = $account_value['balance']['available'];

		if($account_value['institution_type'] == 'fake_institution'){
			switch ($accessToken) {
				case 'test_bofa':
					$bank_account['bank_name'] = 'Bank Of America';
					break;
				case 'test_chase':
					$bank_account['bank_name'] = 'Chase';
					break;
				default:
					$bank_account['bank_name'] = $account_value['institution_type'];
					break;
			}
		}

		if(isset($account_value['meta']['limit']))
			$bank_account['acc_limit'] = $account_value['meta']['limit'];

		if(isset($account_value['subtype']))
			$bank_account['account_subtype'] = $account_value['subtype'];

		$bank_account['name'] = $account_value['meta']['name'];
		$bank_account['number'] = $account_value['meta']['number'];
		$bank_account['account_type'] = $account_value['type'];
		$bank_account['LastSynced_at'] = Carbon::now();
		$bank_account['plaid_core'] = serialize($account_value);
		$bank_account->save();
	}

	//Stores given single transaction to DB
	private function setTransaction($transaction_value, $accessToken){
		$transaction = new transaction();
		if(str_contains($accessToken,'test')){
			$transaction['id'] = $accessToken.$transaction_value['_id'];
		}
		else{
			$transaction['id'] = $transaction_value['_id'];
		}
		if(str_contains($accessToken,'test')){
			$transaction['bank_accounts_id'] = $accessToken.$transaction_value['_account'];
		}
		else{
			$transaction['bank_accounts_id'] = $transaction_value['_account'];
		}

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
}
