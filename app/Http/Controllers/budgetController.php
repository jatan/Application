<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class budgetController extends Controller
{
    //
    public function store(){

        $input = [
            'category' => $_GET['category'],
            'frequency' => $_GET['frequency'],
            'rollOverFlag' => $_GET['rollOverFlag'],
            'Setamount' => $_GET['Setamount'],
        ];
        return json_encode($input);
    }
}
