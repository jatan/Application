<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use App\Categories;
use App\transaction;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

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

    public function processTransactions(){

        require "rawdata.php";

        $accounts = Auth::user()->visible_accounts()->toArray();
        $newTransaction = new transaction();

        $newTransaction['bank_accounts_id'] = '1';
        $newTransaction['date'] = Carbon::now()->toDateString();        //  '2017-03-31'
        $newTransaction['amount'] = 300;
        $newTransaction['name'] = $data[rand(1,500)];
        // $newTransaction['location_city'] = 'Hillsborough';
        // $newTransaction['location_state'] = 'New Jersey';
        $newTransaction['pending'] = false;
        $newTransaction['type_expense'] = 'expense';
        $newTransaction['category'] = 'Shops';
        $newTransaction['category_id'] = '';
        $newTransaction['score'] = '';
        $newTransaction['plaid_core'] = '';

        var_dump(count($data));
        var_dump($data[rand(1,500)]);
        var_dump(Carbon::now()->toDateString());

        // var_dump($accounts);
        // var_dump($newTransaction->toArray());
    }
}
