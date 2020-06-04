<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Shipment;

class Review extends Model
{
    protected $fillable = [
        'photo_url', 'pup_name', 'title', 'content', 
      'permission', 'shipment_id'
    ];

    public function shipment() {
        return $this->belongsTo(Shipment::class, 'shipment_id', 'id');
    }
}
