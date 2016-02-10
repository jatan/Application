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
        $salt = 'whatever';
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
}
