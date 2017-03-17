<?php

namespace App\Http\Controllers;

use App\Plaid_RQRS_Logs;
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
				Log::info("Access token is set");

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
				$this->setTransaction($transaction_value);
			}
			// TODO: This is not a proper response of ajax call.
			// return (redirect::to('user/dashboard'));
		}
	}

	/* Not fully implemented

	*/
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
		$accounts = Auth::user()->accounts->groupBy('access_token');

		$resp = view('account.ac_getAll')->with('accounts', $accounts);
		return ($resp);
	}

	public function updateAccount_byId($id){
		$resp = view('account.ac_edit')->with('access_token', $id)->render();
		return ($resp);
	}

	public function updateAccount_process()
	{
		
		// Only process ajax requests.
		// if(Request::ajax()) 
		{
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
						'access_token' => $input['access_token']	// Bank Name - in type specific to Plaid ex: bofa
					]
				];
			}

			// After deciding which URI to call and its parameters, Request will be sent.
			// And response is decoded from JSON to array
			$client = new Client();
			$response = $client->patch($uri, $parameters);
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
					return (view('account.ac_edit_p')->with('pass', $pass)->with('access_token', $array['access_token']));
				} else if ($array['type'] == "device") {
					Log::info("Device based MFA");

					$mfa = $array['mfa'];
					$pass = $mfa['message'];
					return (view('account.ac_edit_p')->with('pass', $pass)->with('access_token', $array['access_token']));
				} else if ($array['type'] == "list") {
					Log::info("Provide MFA list to User to select from");

					$mfa = $array['mfa'][0];
					$pass = $array['mfa'];
					return (view('account.ac_edit_p')->with('pass', $pass)->with('access_token', $array['access_token']));
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
				$this->setTransaction($transaction_value);
			}
			// TODO: This is not a proper response of ajax call.
			return (redirect::to('user/dashboard'));
		}
	}

	/*	Delete All accounts related to given access_token.
		If there are 3 accounts related to 1 access_token, all will be deleted.
		Due to cascading set, transactions from those accounts will also be deleted.
		Tables impacted - bank_accounts , transactions
	*/
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
		Log::info("Plaid call to URI: ".$uri." is Successful");

		$respText = "";
		// Check if plaid response is Success meaning Plaid successfully deleted those accounts.
		// So now we can delete these from out system.
		if (strpos($array['message'], 'Success') !== false) {
			foreach ($accounts as $account) {
				// Delete account from out database.
				if($account->delete()){
					$respText = $respText."<h3>".$account['name']." Account Deleted successfully</h3>";
				}
			}
		}
		else {
			$respText = $array['message'];
		}
		// Returning just plain text response. No HTML/CSS
		return ($respText);
	}

	/* Hide-Unhide toggle for individual accounts
	*/
	public function hideToggle($id){
		// Get the account with the provided ID
		$account = bank_accounts::find($id);

		if ($account->hidden_flag == false)
			$account->update(['hidden_flag' => 1]);
		else $account->update(['hidden_flag' => 0]);

		// Redirect to Get All Accounts view.
		return(redirect::to('user/account/getAll'));
	}

	/* It will sync single account based on its ID.
	*/
	public function syncSingleAccount($id){

		// Get the account with the provided ID
		$account = bank_accounts::find($id);
		$token = $account ->access_token;	// access_token attached to this account.

		$options = ["pending" => true,              // Set to true - to include pending tranxns as well.
					"account" => $account->id,      // account ID to be synced.
					"gte" => Carbon::now()->subDays(15)->toDateString()];       //15 days prior to current date. in YYYY-MM-DD format

		// URI call with below options to specify Start and End dates of Sync.
		// 'options' => '{  "pending":"true",
		// 				    "gte":"2014-05-17",
		// 					"lte":"2014-07-01"
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
	//	Log::info("Plaid call to URI: ".$uri." is Successful");
		$DBLog = new Plaid_RQRS_Logs();
		$DBLog['URL'] = $uri;
		$DBLog['Request'] = json_encode($parameters['json']);
		$DBLog['Response'] = $response->getBody();
		$DBLog->save();

		// Even though we call with only single account ID, Plaid will return all accounts associated with the access_token
		// But tranxns will be only from that particular account.
		// This is actually helpful because this way we will know if any new accounts are added to the access_token.
		$accounts = $array['accounts'];         // So this will have all accounts
		$transactions = $array['transactions']; // This will have all tranxns of requested account.

		foreach($accounts as $account_key => $account_value ){
			$bank_account = bank_accounts::find($account_value['_id']);

			if(isset($bank_account) && $id == $account_value['_id']){
				$bank_account['current_balance'] = $account_value['balance']['current'];
				$bank_account['available_balance'] = $account_value['balance']['available'];
				if(isset($account_value['meta']['limit'])){
					$bank_account['acc_limit'] = $account_value['meta']['limit'];
				}
				$bank_account['name'] = isset($account_value['meta']['official_name']) ? $account_value['meta']['official_name'] : $account_value['meta']['name'];
				$bank_account['LastSynced_at'] = Carbon::now();
				$bank_account['SyncCount'] = $bank_account['SyncCount']+1;
				$bank_account['bank_name'] = $account_value['institution_type'];
				$bank_account ->save();
			}
			if ($bank_account === NULL) {
				$this->setAccount($account_value, $array['access_token']);
			}
		}
		$newTransCount = 0;
		foreach($transactions as $transaction_key => $transaction_value){

			$transaction = transaction::find($transaction_value['_id']);

			if(!$transaction){
				// Log::info($transaction_key.": Transaction not found");
				// Check if current transaction was fetched before as pending.
				// If a previously pending transaction is now cleared,
				// The cleared transaction will have new _id with _pendingTransaction
				// attribute set to previous ID value.
				if (isset($transaction_value['_pendingTransaction'])) {
					Log::info($transaction_key.": Transaction has a pending attribute");
					$pendingTransaction = transaction::find($transaction_value['_pendingTransaction']);
					if(isset($pendingTransaction)){
						Log::info($transaction_key.": Pending transaction needs to be deleted.");
						$pendingTransaction->delete();
						Log::info($transaction_key.": Delete completed.");
						$this->setTransaction($transaction_value);
						$newTransCount++;
					}
					else {
						Log::info($transaction_key.": Pending attribute is there But pending ID is not found.");
						$this->setTransaction($transaction_value);
						$newTransCount++;
					}
				}
				else {
					$this->setTransaction($transaction_value);
					$newTransCount++;
				}
			}
		}
		Log::info("Total No of Transactins updated are: ".$newTransCount);
		return(redirect::to('user/account/getAll'));
	}

	public function syncAll($token){
		Log::info("Sync all accounts for given token: ".$token);
		$uri = 'https://tartan.plaid.com/connect/get';
		$options = ["pending" => true,];              // Set to true - to include pending tranxns as well.
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
		Log::info("Plaid call to URI: ".$uri." is Successful");

		$accounts = $array['accounts'];         // This will have all accounts
		$transactions = $array['transactions']; // This will have all tranxns of all accounts.
		foreach($accounts as $account_key => $account_value ){
			$bank_account = bank_accounts::find($account_value['_id']);
			// TODO: Can this be just update operation to update those values that have changed

			if(isset($bank_account)){
				$bank_account['current_balance'] = $account_value['balance']['current'];
				$bank_account['available_balance'] = $account_value['balance']['available'];
				if(isset($account_value['meta']['limit'])){
					$bank_account['acc_limit'] = $account_value['meta']['limit'];
				}
				$bank_account['name'] = isset($account_value['meta']['official_name']) ? $account_value['meta']['official_name'] : $account_value['meta']['name'];
				$bank_account['LastSynced_at'] = Carbon::now();
				$bank_account['SyncCount'] = $bank_account['SyncCount']+1;
				$bank_account['bank_name'] = $account_value['institution_type'];
				$bank_account ->save();
			}
			if ($bank_account === NULL) {
				$this->setAccount($account_value, $array['access_token']);
			}
		}

		$newTransCount = 0;
		foreach($transactions as $transaction_key => $transaction_value){

			$transaction = transaction::find($transaction_value['_id']);

			if(!$transaction){
				Log::info($transaction_key.": Transaction not found");
				// Check if current transaction was fetched before as pending.
				// If a previously pending transaction is now cleared,
				// The cleared transaction will have new _id with _pendingTransaction
				// attribute set to previous ID value.
				if (isset($transaction_value['_pendingTransaction'])) {
					Log::info($transaction_key.": Transaction has a pending attribute");
					$pendingTransaction = transaction::find($transaction_value['_pendingTransaction']);
					if(isset($pendingTransaction)){
						Log::info($transaction_key.": Pending transaction needs to be deleted.");
						$pendingTransaction->delete();
						Log::info($transaction_key.": Delete completed.");
						$this->setTransaction($transaction_value);
						$newTransCount++;
					}
					else {
						Log::info($transaction_key.": Pending attribute is there But pending ID is not found.");
						$this->setTransaction($transaction_value);
						$newTransCount++;
					}
				}
				else {
					$this->setTransaction($transaction_value);
					$newTransCount++;
				}
			}
		}
		Log::info("Total No of Transactions updated are: ".$newTransCount);
		return(redirect::to('user/account/getAll'));
	}

	public function syncMaster(){
		$accounts = Auth::user()->access_tokens();
		foreach ($accounts as $currentAccount) {
			$this->syncAll($currentAccount->access_token);
		}
		return(redirect::to('user/account/getAll'));
	}
	//Stores given single account to DB
	private function setAccount($account_value, $accessToken){
		$bank_account= new bank_accounts();

		$bank_account['id']=$account_value['_id'];
		$bank_account['user_id']=Auth::user()->id;
		$bank_account['access_token'] = $accessToken;
		$bank_account['current_balance'] = $account_value['balance']['current'];
		$bank_account['available_balance'] = $account_value['balance']['available'];
		$bank_account['name'] = isset($account_value['meta']['official_name']) ? $account_value['meta']['official_name'] : $account_value['meta']['name'];
		$bank_account['number'] = $account_value['meta']['number'];
		$bank_account['account_type'] = $account_value['type'];
		$bank_account['LastSynced_at'] = Carbon::now();
		$bank_account['plaid_core'] = serialize($account_value);
		if($account_value['institution_type'] == 'fake_institution'){
			switch ($accessToken) {
				case 'test_bofa':
					$bank_account['bank_name'] = 'Bank Of America';
					break;
				case 'test_chase':
					$bank_account['bank_name'] = 'Chase';
					break;
				case 'test_citi':
					$bank_account['bank_name'] = 'Citi Bank';
					break;
				case 'test_amex':
					$bank_account['bank_name'] = 'American Express';
					break;
				case 'test_wells':
					$bank_account['bank_name'] = 'Wells Fargo';
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

		$bank_account->save();
	}

	//Stores given single transaction to DB
	private function setTransaction($transaction_value){
		$transaction = new transaction();

		$transaction['id'] = $transaction_value['_id'];
		$transaction['bank_accounts_id'] = $transaction_value['_account'];
		$transaction['amount'] = $transaction_value['amount'];
		$transaction['date'] = $transaction_value['date'];
		$transaction['name'] = $transaction_value['name'];
		$transaction['pending'] = $transaction_value['pending'];
		$transaction['type_primary'] = $transaction_value['type']['primary'];

		if(isset($transaction_value['meta']['location']['city']))
			$transaction['location_city'] = $transaction_value['meta']['location']['city'];
		if(isset($transaction_value['meta']['location']['state']))
			$transaction['location_state'] = $transaction_value['meta']['location']['state'];

		if(isset($transaction_value['category_id']))
			$transaction['category_id'] = $transaction_value['category_id'];
		if(isset($transaction_value['category']))
			$transaction['category'] = json_encode($transaction_value['category']);

		$transaction['score'] = json_encode($transaction_value['score']);
		$transaction['plaid_core'] = json_encode($transaction_value);

		$transaction->save();
	}

	public function sign( $number ) {
    	return ( $number > 0 ) ? 1 : ( ( $number < 0 ) ? -1 : 0 );
	}
}
