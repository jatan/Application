<?php

namespace App;
use Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        //$salt = 'whatever';
        $this->attributes['password'] = Hash::make($value);
    }
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','confirmation_code'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts(){
        return $this->hasMany('App\bank_accounts');
    }
    public function visible_accounts(){
        return $this ->accounts()->get()->where('hidden_flag',0);
    }

	public function access_tokens(){
		return $this->accounts()->distinct()->select('access_token')->groupBy('access_token')->get();
	}

    public function budgets(){
        return $this->hasMany('App\Budget');
    }
}
