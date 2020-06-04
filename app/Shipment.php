<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Buyer;
use App\Pup;
use App\Review;

class Shipment extends Model
{
    protected $fillable = [
        'price', 'discount', 'final_price', 'estimated_delivery_time', 
        'actual_delivery_time', 'delivery_city', 'delivery_state', 'delivery_country', 
        'delivery_address', 'delivery_zipcode', 'delivery_phone', 
        'user_id', 'buyer_id', 'pup_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pup() {
        return $this->belongsTo(Pup::class, 'pup_id', 'id');
    }

    public function buyer() {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id');
    }

    public function review() {
        return $this->hasOne(Review::class, 'shipment_id', 'id');
    }
}
