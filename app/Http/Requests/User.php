<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 
        'lastname', 
        'username', 
        'password', 
        'user_type',
        'middle_initial',
        'contact_number',
        'email',
        'sex',
        'address',
        'birthdate'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function doctor()
    {
        return $this->hasOne('App\Doctor');
    }

    public function manager()
    {  
        return $this->hasOne('App\PharmacyManager');
    }

    public function patient()
    {
         return $this->hasOne('App\Patient');
    }
     public function secretary()
    {
         return $this->hasOne('App\Secretary');
    }
     public function pharma()
    {
         return $this->hasOne('App\Pharma');
    }

    public function fullname()
    {
        return "{$this->firstname} {$this->middle_initial}. {$this->lastname}";
    }

    public function isAdmin()
    {
        return $this->user_type === 'ADMIN';
    }

    public function isDoctor()
    {
        return $this->user_type === 'DOCTOR';
    }
}
