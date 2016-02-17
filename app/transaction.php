<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    protected $casts =['id' => 'string'];

    public function account(){
        return $this -> belongsTo('App\bank_accounts');
    }
}
