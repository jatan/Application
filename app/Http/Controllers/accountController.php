<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\bank_accounts;
use App\transaction;
use GuzzleHttp\Client;
use Request;
use Curl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Log;

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
			$this->setAccount($accounts, $accessToken);

			//Save all the transactions from each account of the given Bank.
			$transactions = $array['transactions'];
			$this->setTransaction($transactions, $accessToken);

			return (redirect::to('user/dashboard'));
		}
	}

	public function getAccount_byId($id){
		$account = bank_accounts::find($id);
		$data = '<h3>Read Operation Successful for ID: '.$id.' and its Current balance is: '.$account['current_balance'].'</h3>';
		return ($data);
	}

	public function getAccounts(){
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

		$respText = "";
		foreach ($accounts as $account) {
			Log::info($account);
			if($account->delete()){
				$respText = $respText."<h3>".$account['name']." Account Deleted successfully</h3>";
			}
		}
		$data = '<h3>Delete Operation Successful for ID: '.$token.'</h3>';
		return ($respText);
	}


	public function longtail(){
		$uri = 'https://tartan.plaid.com/institutions/longtail';
		$parameters = [
			'json' => [
				'client_id' => 'test_id',
				'secret' => 'test_secret',
				'count' => 2000,
				'offset' => 12000
			]
		];
		$client = new Client();
		$response = $client->post($uri, $parameters);
		$array = json_decode($response->getBody(), true);
		Log::info(count($array));

		return($array);
	}
	//Stores given array of accounts to DB
	private function setAccount($accounts, $accessToken){
		foreach($accounts as $account_key => $account_value ){
			$bank_account= new bank_accounts();
			$bank_account['id']=$accessToken.$account_value['_id'];
			$bank_account['user_id']=Auth::user()->id;
			$bank_account['access_token'] = $accessToken;
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
	}

	//Stores given array of transactions to DB
	private function setTransaction($transactions, $accessToken){
		foreach($transactions as $transaction_key => $transaction_value){
			$transaction = new transaction();
			$transaction['id'] = $accessToken.$transaction_value['_id'];
			$transaction['bank_accounts_id'] = $accessToken.$transaction_value['_account'];
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
}
