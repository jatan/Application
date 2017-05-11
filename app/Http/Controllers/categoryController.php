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
use Illuminate\Support\Facades\Redirect;

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

        $subDays = isset($_GET['days']) ? $_GET['days'] : 0;
        require "rawdata.php";
        $ListofCategories = ['Bank Fees','Cash Advance', 'Gas and Fuel', 'Community','Food and Drink','Healthcare',
	                        'Interest','Payment','Recreation','Service','Shops','Tax','Transfer', 'Travel'];
		$AllCategories = Categories::select('id', 'c1', 'c2')->get()->toArray();
        $accounts = Auth::user()->visible_accounts()->toArray();

        $newTransaction = new transaction();
		$newTransaction['id'] = 1234561+rand(15415,542424);
        $newTransaction['bank_accounts_id'] = $accounts[rand(0,count($accounts)-1)]['id'];                     //123456121490143318
        $newTransaction['date'] = Carbon::now()->subDays($subDays)->toDateString();                        //  '2017-03-31'
        $newTransaction['amount'] = number_format(rand(1,2500)/5.0, 2, '.','');
        $newTransaction['name'] = $data[rand(1,500)];
        // $newTransaction['location_city'] = 'Hillsborough';
        // $newTransaction['location_state'] = 'New Jersey';
        $newTransaction['pending'] = false;
        $newTransaction['type_primary'] = 'expense';
        $newTransaction['category'] = $ListofCategories[rand(0,count($ListofCategories)-1)];
        $newTransaction['category_id'] = $AllCategories[rand(0,500)]['id'];
        $newTransaction['score'] = '1';
        $newTransaction['plaid_core'] = \GuzzleHttp\json_encode($newTransaction);
		$newTransaction->save();
        // dd($newTransaction->toArray());

        $bc = new BudgetController();
        $bc->update();

        $ac = new AccountController();
        $ac->updateTotals($newTransaction['bank_accounts_id'], $newTransaction['amount']);

        return (redirect::to('user/transaction'));
    }
}
