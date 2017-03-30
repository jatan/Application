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
        echo "Processing update for User: ";
        echo (Auth::user()->email);

        $t = transaction::where('pending', 0)
                        ->where('date', '>=', '2017-01-01')
	                    ->where('category', '!=', '')
                        ->groupBy('category')
                        ->selectRaw('category, sum(amount) as Total')
                        ->get()
                        ->toArray();
        //    var_dump($t);
        $b = Budget::all();

        //    var_dump($b);

        foreach ($b as $currentBudget) {
            foreach ($t as $totalSpending) {
                if ($currentBudget['Name'] == $totalSpending['category']) {
                    $currentBudget['SpentValue'] = $totalSpending['Total'];
                }
            }
            $currentBudget->save();
        }
	    return (redirect::to('user/budget'));
    }

    public function index(){

	   $monthList = [
           'JAN' => 'January',
           'FEB' => 'February',
           'MAR' => 'March',
           'APR' => 'April',
           'MAY' => 'May',
           'JUN' => 'June',
           'JUL' => 'July',
           'AUG' => 'August',
           'SEP' => 'September',
           'OCT' => 'October',
           'NOV' => 'November',
           'DEC' => 'December'
       ];

	    $now = Carbon::now();
//		            ->addMonths(2);
	    $year = $now->year;
	    $month = $now->month;

	    $allBudgets = Budget::select('Name', 'SetValue', 'SpentValue', 'Month', 'Year')
	                        ->where('User_ID', Auth::user()->id)
		                    ->where([['Month', '>' , $month], ['Year', '=', $year-1]])
		                    ->orWhere([['Month', '<=' , $month], ['Year', '=', $year]])
		                    ->get()
	                        ->toArray();
	    //dd($allBudgets);
        return (view('budget.bu_index')->with('budgetLists', $allBudgets)->with('monthList', $monthList));
    }

    public function createBudget(){

	    $newBudget = new Budget();

	    $newBudget['User_ID'] = Auth::user()->id;
	    $newBudget['Name'] = $_POST['category'];
	    $newBudget['SetValue'] = $_POST['Setamount'];
	    $newBudget['SpentValue'] = 0;

	    $newBudget->save();

	    return (redirect::to('/update'));
    }

    public function updateBudget(){
        $userID = Auth::user()->id;

        $findBudget = Budget::where('User_ID', $userID)
                            ->where('Name', $_POST['category'])
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
