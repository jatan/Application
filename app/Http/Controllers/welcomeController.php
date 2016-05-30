<?php

namespace App\Http\Controllers;

use App\Categories;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class welcomeController extends Controller
{
    public function welcome(){
        return(view('welcome'));
    }

    public function pullCategories(){

        $uri = 'https://tartan.plaid.com/categories';

        $client = new Client();
        $response = $client->get($uri);
        $array = json_decode($response->getBody(), true);
        //var_dump($array['66']);



		foreach ($array as $record){
			$category = new Categories();
			foreach($record as $key=>$value){

				if($key == 'id')
					$category['Cat_ID'] = $value;
				if($key == 'type')
					$category['Type'] = $value;
				if($key == 'hierarchy') {
					$category['c1'] = $value['0'];
					isset($value['1'])? $category['c2'] = $value['1'] : $category['c2'] = null;
					isset($value['2'])? $category['c3'] = $value['2'] : $category['c3'] = null;
				}
				$category->save();
			}
		}

		return (view('categories')->with('data', $array));
    }
}
