<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


use App\Pup;
use App\Breed;
use App\Shipment;

class User extends Authenticatable
{

    use Notifiable;

    /** 
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'user_name', 'role', 'email', 'phone_number', 'photo_url', 'permission', 'email_verify_token', 'api_token', 'city', 'state', 'country', 'address'
    ];

    /** 
     * The attributes that should be hidden for arrays.
     * 
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    public function pups() {
        return $this->hasMany(Pup::class, 'user_id', 'id');
    }

    public function shipment() {
        return $this->hasMany(Shipment::class, 'user_id', 'id');
    }
}
