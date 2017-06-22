<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Validator;
use Tymon\JWTAuth\Contracts\JWTSubject as AuthenticatableUserContract;


class User extends Authenticatable implements AuthenticatableUserContract
{
    use Notifiable;

    protected  $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'DOB', 'phone', 'gender', 'country', 'city',
    ];



    public $sortFields = ['name','email', 'DOB', 'phone', 'gender', 'country', 'city','created_at'];



    protected $hidden = [
        'password', 'remember_token',
    ];

    public $rulesForCreate = [
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users|max:255',
        'password' => 'min:6|required|confirmed|max:255'
    ];


    public $rulesForUpdate = [
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users|max:255',
        'DOB'  => 'date',
        'gender' => 'in:male,female'//'regex:/(male)?(female)?/'
    ];

    public function validateCustom($data, $rules)
    {
        $validator = Validator::make($data, $this->{$rules});

        if ($validator->fails())
        {
            $this->errors = $validator->errors();
            return false;
        }

        return true;

        //rulesForUpdate
        // rulesForCreate
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
}

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function errors()
    {
        return $this->errors;
    }

}
