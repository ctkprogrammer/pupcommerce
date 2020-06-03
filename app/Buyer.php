<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    /** 
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'user_name', 'email', 'phone_number', 'permission', 'city', 'state', 'country', 'address', 'zipcode'
    ];

    public function shipment() {
        return $this->hasMany(Shipment::class, 'buyer_id', 'id');
    }
}
