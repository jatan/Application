<?php

namespace App\Http\Controllers;

use App\Budget;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

class budgetController extends Controller
{

    public function index(){

//	    $allBudgets = Budget::all()->where('User_ID', Auth::user()->id)->toArray();
//	    var_dump($allBudgets[0]['SetValue']);
        return (view('budget.bu_index'));
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
