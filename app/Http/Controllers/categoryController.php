<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use App\Categories;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class categoryController extends Controller
{

    public function reloadCategories(){

        $uri = 'https://tartan.plaid.com/categories';

        $client = new Client();
        $response = $client->get($uri);
        $categories = json_decode($response->getBody(), true);

		foreach ($categories as $category_key => $category_value){
			$category = Categories::find($category_value['id']);
            if(!$category){
                $category = new Categories();

                $category['id'] = $category_value['id'];
                $category['type'] = $category_value['type'];
                isset($category_value['hierarchy'][0]) ? $category['c1'] = $category_value['hierarchy'][0] : $category['c1'] = null;
                isset($category_value['hierarchy'][1]) ? $category['c2'] = $category_value['hierarchy'][1] : $category['c2'] = null;
                isset($category_value['hierarchy'][2]) ? $category['c3'] = $category_value['hierarchy'][2] : $category['c3'] = null;
                echo "New Category added with ID: ".$category_value['id'];
                $category->save();
            }
		}
        echo "Processing complete";
    }

    public function viewCategories(){

        $uri = 'https://tartan.plaid.com/categories';

        $client = new Client();
        $response = $client->get($uri);
        $array = json_decode($response->getBody(), true);

        return (view('common.categories')->with('data', $array));
    }
}
