<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class budgetController extends Controller
{

    public function index(){
        return (view('budget.bu_index'));
    }

    public function createBudget(){

//        $input = [
//            'category' => $_GET['category'],
//            'frequency' => $_GET['frequency'],
//            'rollOverFlag' => $_GET['rollOverFlag'],
//            'Setamount' => $_GET['Setamount'],
//        ];
//        return json_encode($input);

        $data = '<h3>Create Operation Successful</h3>';
        return ($data);
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
