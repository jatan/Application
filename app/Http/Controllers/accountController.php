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
	/*
	*	Main Accounts page.
	*/
	public function index(){
		return (view('account.ac_index'));
	}

	/*
	*	Load Create Account Form.
	*/
	public function createAccount(){
		$resp = view('account.ac_create')->render();
		return ($resp);
	}

	/*
	*	Process request to Create/Add new financial account
	*/
	public function createAccount_process(){
		// Only process ajax requests.
		if(Request::ajax()) {

			$input = Request::all();
			Log::alert($input);			// [This will log un-encrypted username and password]

			// There are 3 checks to identify at which step is the user of account creation process
			// This will help determine which Plaid URI to call and what should be the parameters
			// 1st Check -
			if (isset($input['radio'])) {
				Log::info("Radio Button selected");
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
			}
			// 2nd Check -
			else if (isset($input['access_token'])) {
				Log::info("Access tocken is set");

				$uri = 'https://tartan.plaid.com/connect/step';
				$parameters = [
						'json' => [
								'client_id' => env('PLAID_CLIENT_ID'),
								'secret' => env('PLAID_SECRET'),
								'access_token' => $input['access_token'],
								'mfa' => $input['ans']
						]
				];
			}
			// 3rd Check - First Step
			else {
				Log::info("User is at the first step of the process");

				$uri = 'https://tartan.plaid.com/connect';
				$parameters = [
						'json' => [
								'client_id' => env('PLAID_CLIENT_ID'),
								'secret' => env('PLAID_SECRET'),
								'username' => $input['username'],	// Bank Username - given by user
								'password' => $input['password'],	// Bank password - given by user
								'type' => $input['bank'],			// Bank Name - in type specific to Plaid ex: bofa
								'options' => '{"list":true}'		// Hardcoded true - This will return MFA if available
						]
				];
			}

			// After deciding which URI to call and its parameters, Request will be sent.
			// And response is decoded from JSON to array
			$client = new Client();
			$response = $client->post($uri, $parameters);
			$array = json_decode($response->getBody(), true);
			Log::info("Plaid call to URI: ".$uri." is Successful");

			// Now Decide Response.
			// If Bank is MFA enabled, then only it will return 'type' field.
			// If bank is not MFA enabled - All bank accounts and transactions will be inserted.
			// Do multiple checks to decide what should be the response for MFA.
			if (isset($array['type'])) {

				if ($array['type'] == "questions") {
					Log::info("Question based MFA");

					$mfa = $array['mfa'][0];
					$pass = $mfa['question'];
					return (view('account.ac_create_p')->with('pass', $pass)->with('access_token', $array['access_token']));
				} else if ($array['type'] == "device") {
					Log::info("Device based MFA");

					$mfa = $array['mfa'];
					$pass = $mfa['message'];
					return (view('account.ac_create_p')->with('pass', $pass)->with('access_token', $array['access_token']));
				} else if ($array['type'] == "list") {
					Log::info("Provide MFA list to User to select from");

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
			// TODO: This is not a proper response of ajax call.
			return (redirect::to('user/dashboard'));
		}
	}

	public function getAccount_byId($id){
		$account = bank_accounts::find($id);
		$data = '<h3>Read Operation Successful for ID: '.$id.' and its Current balance is: '.$account['current_balance'].'</h3>';
		return ($data);
	}

	public function getAccounts(){
		/*
		$uri = 'https://tartan.plaid.com/info/get';
	    $parameters = [
	        'json' => ['client_id' => env('PLAID_CLIENT_ID'),
						'secret' => env('PLAID_SECRET'),
						'access_token' => '',]
		];

		 $client = new Client();
		 $response = $client->post($uri, $parameters);
		 $array = json_decode($response->getBody(), true);
		*/


		$accounts = bank_accounts::all()->groupBy('access_token');

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
