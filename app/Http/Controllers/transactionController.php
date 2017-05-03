<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\transaction;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\budgetController as BudgetController;
use App\Http\Requests;
use Request;
use Log;

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
	                         ->paginate(10);

		return (view('transaction.tr_Index')->with([
                                                    'accounts' => $accounts,
                                                    'transactions' => $transactions]));
    }

    public function createTransaction(){
        $data = $_POST;
        var_dump($data);

        $newTransaction = new transaction();
        $newTransaction['id'] = 1234561+rand(15415,542424);
        $newTransaction['name'] = $_POST['Merchant'];
        $newTransaction['category'] = $_POST['Category'];
        $newTransaction['date'] = $_POST['TransDate'];
        $newTransaction['amount'] = $_POST['amount'];
        $newTransaction['bank_accounts_id'] = $_POST['accountID'];

        var_dump($newTransaction->toArray());
        $newTransaction->save();

        $bc = new BudgetController();
        $bc->update();

        $ac = new AccountController();
        $ac->updateTotals($_POST['accountID'], $_POST['amount']);

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
        $trans = transaction::find($id);
        $trans->delete();

        $bc = new BudgetController();
        $bc->update();

        $ac = new AccountController();
        $ac->updateTotals($trans->bank_accounts_id, $trans->amount, 'IN');

        return (redirect::to('/user/transaction'));
    }

    public function editTransaction_byId($id){
	    $input = Request::all();
	    Log::alert($input);
	    $trans = transaction::find($id);
		$trans['name'] = $input['Merchant'];
		$trans['amount'] = ($input['Amount'] < 0) ? abs($input['Amount']) : $input['Amount'];
		$trans['category'] = $input['Category'];
		$trans->save();
        return($trans);
    }

    public function fetch(){
        $input = Request::all();
        $userID = Auth::user()->id;
        // dd($input['year']);
        $requestedTransactions = transaction::where('date', '<=', $input['year'].'-0'.$input['month'].'-31')
                                            ->where('date', '>=', $input['year'].'-0'.$input['month'].'-01')
                                            ->where('category', $input['budgetName'])
                                            ->selectRaw('date, name, amount, category')
                                            ->get()
                                            ->toArray();

        // dd($requestedTransactions);
        return ($requestedTransactions);
    }
}
