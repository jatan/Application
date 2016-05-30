<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use App\Categories;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class categoryController extends Controller
{

    public function pullCategories(){

        $uri = 'https://tartan.plaid.com/categories';

        $client = new Client();
        $response = $client->get($uri);
        $array = json_decode($response->getBody(), true);
        //var_dump($array['66']);



		foreach ($array as $record){
			$category = new Categories();
			foreach($record as $key=>$value){
				if($key == 'id'){
					if(Categories::where('Cat_ID','=',$value) -> exists())
						break;
					$category['Cat_ID'] = $value;
				}
				//echo "I should not be here";
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
