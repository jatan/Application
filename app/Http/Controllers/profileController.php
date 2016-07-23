<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class profileController extends Controller
{
    public function index(){
        return (view('profile.pr_index'));
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
