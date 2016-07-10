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
        $categories = json_decode($response->getBody(), true);
        // var_dump($categories);



		foreach ($categories as $category_key => $category_value){
			$category = Categories::find($category_value['id']);
            if(!$category){
                $category = new Categories();
                $category['id'] = $category_value['id'];

                $category['type'] = $category_value['type'];
                isset($category_value['hierarchy'][0]) ? $category['c1'] = $category_value['hierarchy'][0] : $category['c1'] = null;
                isset($category_value['hierarchy'][1]) ? $category['c2'] = $category_value['hierarchy'][1] : $category['c2'] = null;
                isset($category_value['hierarchy'][2]) ? $category['c3'] = $category_value['hierarchy'][2] : $category['c3'] = null;
                // if(isset($category_value['meta']['location']['city']))
                //     $category['location_city'] = $category_value['meta']['location']['city'];
                
                var_dump($category);
                $category->save();
                // die;
            }
		}
		return (view('categories')->with('data', $category));
    }
}
