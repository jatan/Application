<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use GuzzleHttp\Client;
use App\longtailinst;
use Log;

class userController extends Controller
{
	public function dashboard(){
		return (view('user.dashboard'));
	}

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
			$results = $array['results'];

			foreach ($results as $curResult) {
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
					//Log::info($ex);
					Log::info($longtailInst);
				}
			}
		}
		return($array);
	}
}
