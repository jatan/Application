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

	   // dd(new Carbon('last day of January 2017'));

	    $allBudgets = Budget::all()->where('User_ID', Auth::user()->id)
      //                              ->where('date', '<=' , new Carbon('last day of January 2017'))
      //                              ->where('date', '>=' , new Carbon('first day of January 2017'))
                                    ->toArray();
//	    var_dump($allBudgets[0]['SetValue']);
        return (view('budget.bu_index')->with('budgetLists', $allBudgets));
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
