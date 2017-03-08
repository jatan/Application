<?php

namespace App\Http\Controllers;

use App\Budget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;

use App\transaction;

class budgetController extends Controller
{

    public function update(){
        echo "Processing update for User: ";
        echo (Auth::user()->email);

        $t = transaction::where('pending', 0)
                        ->where('date', '<=', '03-15-2017')
                        ->groupBy('category')
                        ->selectRaw('category, sum(amount) as Total')
                        ->get()
                        ->toArray();
        var_dump($t);

        $b = Budget::find(1);
                    //->toArray();

        var_dump($b);
        echo ($t[0]['Total']);
        $b->SpentValue = intval($t[0]['Total']);
        var_dump($b);
        $b->save();

    }

    public function index(){

	    $allBudgets = Budget::all()->where('User_ID', Auth::user()->id)->toArray();
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

//        $input = [
//            'category' => $_GET['category'],
//            'frequency' => $_GET['frequency'],
//            'rollOverFlag' => $_GET['rollOverFlag'],
//            'Setamount' => $_GET['Setamount'],
//        ];
//        return json_encode($input);

//        $data = '<h3>Create Operation Successful</h3>';
//        return ($data);
        return (redirect::to('user/budget'));
    }

    public function getBudget_byId($id){
        $data = '<h3>Read Operation Successful for ID: '.$id.'</h3>';
        return ($data);
    }

    public function getBudgets(){
        $data = '<h3>Read All Operation Successful</h3>';
        return ($data);
    }

    public function updateBudget_byId($id){
        $data = '<h3>Update Operation Successful for ID: '.$id.'</h3>';
        return ($data);
    }

    public function deleteBudget_byId($id){
        $data = '<h3>Delete Operation Successful for ID: '.$id.'</h3>';
        return ($data);
    }
}
