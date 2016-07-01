<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class bank_accounts extends Model
{
    //this line allows Primary Key(id) to be string
    protected $casts = ['id'=>'string'];
    protected $fillable = ['hidden_flag'];

    /**
     * @return mixed
     */
    public function getAllAccounts()
    {
        $user = Auth::user();
        $accounts = $user->visible_accounts();
        $i = 0;
        foreach ($accounts as $account) {
            $accDisplay[$i]['name'] = $account->name;
            $accDisplay[$i]['balance'] = $account->current_balance;
            $accDisplay[$i]['bank_name'] = $account->bank_name;
            $i++;
        }
        return $accDisplay;
    }

    public function user(){
        return $this->belongsTo('App\user');
    }

    public function transaction(){
        return $this->hasMany('App\transaction');
    }
}
