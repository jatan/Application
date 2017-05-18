<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class profileController extends Controller
{
    public function index(){

    	$user = Auth::user();
    	$finalResponse = array();
    	$finalResponse['Fname'] = $user['FirstName'];
    	$finalResponse['Lname'] = $user['LastName'];

        return (view('profile.pr_index')->with('finalResponse', $finalResponse));
    }

    public function createProfile(){
        $data = '<h3>Create Operation Successful</h3>';
        return ($data);
    }

    public function getProfile_byId($id){
        $data = '<h3>Read Operation Successful for ID: '.$id.'</h3>';
        return ($data);
    }

    public function getProfiles(){
        $data = '<h3>Read All Operation Successful</h3>';
        return ($data);
    }

    public function updateProfile_byId($id){
        $data = '<h3>Update Operation Successful for ID: '.$id.'</h3>';
        return ($data);
    }

    public function deleteProfile_byId($id){
        $data = '<h3>Delete Operation Successful for ID: '.$id.'</h3>';
        return ($data);
    }
}
