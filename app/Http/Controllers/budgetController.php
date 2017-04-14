<?php

namespace App\Http\Controllers;

use App\Budget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Carbon\Carbon;
use App\transaction;

class budgetController extends Controller
{

    public function update(){

        $loggedinUserID = Auth::user()->id;

        // Will update only visible bank accounts related transactions
	    $visibleBankAccounts = Auth::user()->visible_accounts()->toArray();
		$bank_accounts_ids = array();
		foreach ($visibleBankAccounts as $current_visibleBankAccount){
			$bank_accounts_ids[] = $current_visibleBankAccount['id'];
		}

        //Get Months and Years array based on if transaction occured for that month of the year
        $inScopeTransactions = transaction::where('pending', 0)
                                          ->whereIn('bank_accounts_id', $bank_accounts_ids)
                                          ->where('category', '!=', '')
                                          ->select('date')
                                          ->get()
                                          ->toArray();
        var_dump($inScopeTransactions);
        exit();
        $budgetUpdateScope = array();
        $transactionCountCheck = '';
        for ($i=0; $i < 12; $i++) {
            # code...
        }
        $budgetUpdateScope = ['2017-3', '2017-4', '2016-12'];
        // Then for each of those combinations, run below code.

        //This will give below format table
        //------------ sourceTransactions --------
        //    category      |      Total
        //      Food        |        400
        //      shop        |        200
        //----------------------------------------
        // SELECT category, sum(amount) as Total
        // FROM transactions
        // WHERE bank_accounts_id IN (SELECT id FROM bank_accounts WHERE user_id IN (SELECT id FROM users WHERE email = 'a@b.c')) AND
        // date >= '2017-01-01' AND
        // category != '' AND
        // pending = 0
        // GROUP BY category;
        $sourceTransactions = transaction::where('pending', 0)
                                        ->where('date', '>=', '2017-03-01')
                                        ->where('date', '<=', '2017-03-31')
                	                    ->whereIn('bank_accounts_id', $bank_accounts_ids)
                	                    ->where('category', '!=', '')
                                        ->groupBy('category')
                                        ->selectRaw('category, sum(amount) as Total')
                                        ->get()             // Returns collection object
                                        ->toArray();        // Converts collection into Array

        $allBudgetsOfUser = Budget::all()->where('User_ID', $loggedinUserID)
                                        ->where('Month', 3)
                                        ->where('Year', 2017);

        foreach ($allBudgetsOfUser as $current_allBudgetsOfUser) {
            foreach ($sourceTransactions as $current_sourceTransactions) {
                if ($current_allBudgetsOfUser['Name'] == $current_sourceTransactions['category']) {
                    $current_allBudgetsOfUser['SpentValue'] = $current_sourceTransactions['Total'];
                }
            }
            $current_allBudgetsOfUser->save();
        }

	    return (redirect::to('user/budget'));
    }

    public function index(){

	   $monthShortName = [
		   1 => 'Jan',
		   2 => 'Feb',
		   3 => 'Mar',
		   4 => 'Apr',
		   5 => 'May',
		   6 => 'Jun',
		   7 => 'Jul',
		   8 => 'Aug',
		   9 => 'Sep',
		   10 => 'Oct',
		   11 => 'Nov',
		   12 => 'Dec'
       ];
	    $monthFullName = [
		    1 => 'January',
		    2 => 'February',
		    3 => 'March',
		    4 => 'April',
		    5 => 'May',
		    6 => 'June',
		    7 => 'July',
		    8 => 'August',
		    9 => 'September',
		    10 => 'October',
		    11 => 'November',
		    12 => 'December'
	    ];

	    $now = Carbon::now();
	    $year = $now->year;
	    $month = $now->month;

	    $sortMonthShortName = [];
	    $sortMonthFullName = [];
	    for ($i = $month+12; $i > $month; $i--){
	    	$i > 12 ? $sortMonthShortName[$i-12] = $monthShortName[$i-12] : $sortMonthShortName[$i] = $monthShortName[$i];
	    	$i > 12 ? $sortMonthFullName[$i-12] = $monthFullName[$i-12] : $sortMonthFullName[$i] = $monthFullName[$i];
	    }

	    $reversed = array_reverse($sortMonthShortName, true);
	    $reversedFull = array_reverse($sortMonthFullName, true);
	    $allBudgets = Budget::select('User_ID', 'Name', 'SetValue', 'SpentValue', 'Month', 'Year')
	                        // ->where('User_ID', Auth::user()->id)
		                    ->where([['Month', '>' , $month],
                                    ['Year', '=', $year-1],
                                    ['User_ID', Auth::user()->id]])
		                    ->orWhere([['Month', '<=' , $month],
                                      ['Year', '=', $year],
                                      ['User_ID', Auth::user()->id]])
		                    ->get()
	                        ->toArray();
	    $masterList = [];
	    foreach ($allBudgets as $budget){
	    	$masterList[$budget['Month']][] = $budget;
	    }

        return (view('budget.bu_index')->with([
        	                                    'CurrentYear' => $year,
	                                            'CurrentMonth' => $month,
        	                                    'budgetLists' => $masterList,
	                                            'monthFullName' => $reversedFull,
	                                            'monthShortName' => $reversed]));
    }

    public function createBudget(){

	    $newBudget = new Budget();

	    $newBudget['User_ID'] = Auth::user()->id;
	    $newBudget['Name'] = $_POST['category'];
	    $newBudget['SetValue'] = $_POST['Setamount'];
	    $newBudget['SpentValue'] = 0;
	    $newBudget['Month'] = $_POST['budgetForMonth'];
	    $newBudget['Year'] = $_POST['budgetForYear'];

	    $newBudget->save();

	    return (redirect::to('/update'));
    }

    public function updateBudget(){
        $userID = Auth::user()->id;

        $findBudget = Budget::where('User_ID', $userID)
                            ->where('Name', $_POST['category'])
	                        ->where('Month', $_POST['budgetForMonth'])
                            ->where('Year', $_POST['budgetForYear'])
                            ->first();
                            //->toArray();
        if (count($findBudget) > 0) {

            $findBudget['SetValue'] = $_POST['Setamount'];
            $findBudget->save();
        } else {

            $newBudget = new Budget();

            $newBudget['User_ID'] = Auth::user()->id;
            $newBudget['Name'] = $_POST['category'];
            $newBudget['SetValue'] = $_POST['Setamount'];
            $newBudget['SpentValue'] = 0;
	        $newBudget['Month'] = $_POST['budgetForMonth'];
	        $newBudget['Year'] = $_POST['budgetForYear'];

            $newBudget->save();

        }
        return (redirect::to('/update'));
    }

    public function deleteBudget_byId(){
	    $userID = Auth::user()->id;

	    $deletedRows = Budget::where('User_ID', $userID)
		                        ->where('Name', $_POST['Name'])
		                        ->delete();
        return (redirect::to('user/budget'));
    }
}
