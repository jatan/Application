<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class transactionController extends Controller
{
    //

    public function index(){
        return (view('transaction.tr_index'));
    }

    public function createTransaction(){
        $data = '<h3>Create Operation Successful</h3>';
        return ($data);
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
