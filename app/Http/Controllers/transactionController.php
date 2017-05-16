<?php

namespace App\Http\Controllers;

use App\Budget;
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

	    $visibleBankAccounts = Auth::user()->visible_accounts()->toArray();
	    $bank_accounts_ids = array();
	    foreach ($visibleBankAccounts as $current_visibleBankAccount){
		    $bank_accounts_ids[] = $current_visibleBankAccount['id'];
	    }
        // dd($input['year']);
	    if ($input['budgetName'] !== "UnBudgeted"){
		    $requestedTransactions = transaction::where('date', '<=', $input['year'].'-0'.$input['month'].'-31')
											    ->where('date', '>=', $input['year'].'-0'.$input['month'].'-01')
											    ->where('category', $input['budgetName'])
											    ->selectRaw('date, name, amount, category')
											    ->get()
											    ->toArray();

		    // dd($requestedTransactions);
		    return ($requestedTransactions);
	    }
	    else {
	    	$unBudgetedCategories = transaction::where('pending', 0)
											    ->where('date', '>=', $input['year'].'-0'.$input['month'].'-01')
											    ->where('date', '<=', $input['year'].'-0'.$input['month'].'-31')
											    ->whereIn('bank_accounts_id', $bank_accounts_ids)
											    ->where('category', '!=', '')
											    ->groupBy('category')
											    ->selectRaw('category, sum(amount) as Total')
											    ->get()             // Returns collection object
											    ->toArray();        // Converts collection into Array
			return ($unBudgetedCategories);
	    }

    }


    public function import(){

        $target_dir = $_SERVER["DOCUMENT_ROOT"]."/uploads//";

        $target_file = $target_dir . date_timestamp_get(date_create()) . "_" . basename($_FILES["file"]["name"]);
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

        $parseResponse = $this->csv_to_array($target_file);     // This will return an array of array

        $distinctCategories = array();
        foreach ($parseResponse as $key => $value) {
            if (!in_array($value['Account Name'], $distinctCategories, true)){
                $distinctCategories[] = $value['Account Name'];
            }
        }
        sort($distinctCategories, SORT_STRING);     // Alphabetical sorting
        // dd($distinctCategories);
        return (json_encode($distinctCategories));
    }

    /**
     * Convert a comma separated file into an associated array.
     * The first row should contain the array keys.
     *
     * Example:
     *
     * @param string $filename Path to the CSV file
     * @param string $delimiter The separator used in the file
     * @return array
     * @link http://gist.github.com/385876
     * @author Jay Williams <http://myd3.com/>
     * @copyright Copyright (c) 2010, Jay Williams
     * @license http://www.opensource.org/licenses/mit-license.php MIT License
     */
     private function csv_to_array($filename='', $delimiter=',') {

        ini_set('auto_detect_line_endings',TRUE);
        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                if (!$header) {
                    $header = $row;
                }
                else {
                    if (count($header) > count($row)) {
                        $difference = count($header) - count($row);
                        for ($i = 1; $i <= $difference; $i++) {
                            $row[count($row) + 1] = $delimiter;
                        }
                    }
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }
}
