<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class billController extends Controller
{
    public function index(){
        return (view('bill.bi_index'));
    }

    public function createBill(){
        $data = '<h3>Create Operation Successful</h3>';
        return ($data);
    }

    public function getBill_byId($id){
        $data = '<h3>Read Operation Successful for ID: '.$id.'</h3>';
        return ($data);
    }

    public function getBills(){
        $data = '<h3>Read All Operation Successful</h3>';
        return ($data);
    }

    public function updateBill_byId($id){
        $data = '<h3>Update Operation Successful for ID: '.$id.'</h3>';
        return ($data);
    }

    public function deleteBill_byId($id){
        $data = '<h3>Delete Operation Successful for ID: '.$id.'</h3>';
        return ($data);
    }
}
