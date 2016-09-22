<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use GuzzleHttp\Client;
use App\longtailinst;
use Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class userController extends Controller
{
	public function dashboard(){
		$accounts = Auth::user()->visible_accounts();
		$ids = '';
		foreach ($accounts as $account) {
			$ids[] = $account->id;
		}
		$transactions = DB::table('transactions')
		                     ->whereIn('bank_accounts_id', $ids)
		                     ->orderBy('date', 'desc')
							 ->get();
		return (view('user.dashboard')->with(['accounts' => $accounts, 'transactions' => $transactions]));
	}

	/*
	 * This will go through each 19500 institutes of PLAID in the chunk of 500
	 * and store them in DB.
	 * @return Complete list of all supported inst. in JSON format
	 * */
	public function longtail(){
		for($i = 0; $i < 19501; $i = $i+500) {

			$uri = 'https://tartan.plaid.com/institutions/longtail';
			$parameters = [
					'json' => [
							'client_id' => 'test_id',
							'secret' => 'test_secret',
							'count' => 500,
							'offset' => $i
					]
			];

			$client = new Client();
			$response = $client->post($uri, $parameters);
			$array = json_decode($response->getBody(), true);
			Log::info("Plaid call to URI: ".$uri." is Successful");

			$results = $array['results'];

			foreach ($results as $curResult) {
				// Initiate new longtail object
				$longtailInst = new longtailinst();
				try {
					$longtailInst['type'] = intval($curResult['type']);
					$longtailInst['url'] = isset($curResult['url']) ? $curResult['url'] : null;
					$longtailInst['Name'] = $curResult['name'];
					$longtailInst['has_mfa'] = boolval($curResult['has_mfa']);
					$longtailInst['mfaArray'] = json_encode($curResult['mfa']);
					$longtailInst['productsArray'] = json_encode($curResult['products']);
					$longtailInst['credentialsJSON'] = json_encode($curResult['credentials']);
					$longtailInst['currencyCode'] = $curResult['currencyCode'];

					$longtailInst->save();
				} catch (\Exception $ex) {
					// Log any exceptions and the failed object also.
					Log::info($ex);
					Log::info($longtailInst);
					// After logging failed record, process will continue on to next record.
				}
			}
		}
		// return Complete list of all supported inst. in JSON format
		return($array);
	}
}
