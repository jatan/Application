<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bank_accounts extends Model
{
    //this line allows Primary Key(id) to be string
    protected $casts = ['id'=>'string'];

    /**
     * @return mixed
     */

    public function user(){
        return $this->belongsTo('App\user');
    }

    public function transaction(){
        return $this->hasMany('App\transaction');
    }
}
