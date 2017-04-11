<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\transaction;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\budgetController as BudgetController;

class transactionController extends Controller
{
    public function index(){
        $accounts = Auth::user()->visible_accounts()->toArray();
        $ids = '';				// This is okay for older versions
		$ids = array();			// This is required for PHP 7.1 or higher
		foreach ($accounts as $account) {
			$ids[] = $account['id'];
		}
		$transactions = DB::table('transactions')
		                     ->whereIn('bank_accounts_id', $ids)
		                     ->orderBy('date', 'desc')
//							 ->get();
	                         ->paginate(10);
        // var_dump(array_search('1', array_column($accounts, 'id')));
        // die();
		return (view('transaction.tr_Index')->with([
                                                    'accounts' => $accounts,
                                                    'transactions' => $transactions]));
    }

    public function createTransaction(){
        $data = $_POST;
        var_dump($data);

        $newTransaction = new transaction();
        $newTransaction['name'] = $_POST['Merchant'];
        $newTransaction['category'] = $_POST['Category'];
        $newTransaction['date'] = $_POST['TransDate'];
        $newTransaction['amount'] = $_POST['amount'];
        $newTransaction['bank_accounts_id'] = $_POST['accountID'];

        var_dump($newTransaction->toArray());
        $newTransaction->save();
        $bc = new BudgetController();
        $bc->update();
        return (redirect::to('/user/transaction'));
    }

    public function getTransaction_byId($id){
        $data = '<h3>Read Operation Successful for ID: '.$id.'</h3>';
        return ($data);
    }

    public function getTransactions(){
        $data = '<h3>Read All Operation Successful</h3>';
        return ($data);
    }

    public function updateTransaction_byId($id){
        $data = '<h3>Update Operation Successful with ID: '.$id.'</h3>';
        return ($data);
    }

    public function deleteTransaction_byId($id){
        $data = '<h3>Delete Operation Successful with ID: '.$id.'</h3>';
        return ($data);
    }
}
