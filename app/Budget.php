<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    //
	public function user(){
		return $this -> belongsTo('App\user');
	}
}
