<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Shipment;

class Review extends Model
{
    protected $fillable = [
        'photo_url', 'pup_name', 'title', 'content', 
      'permission'
    ];

    public function shipment() {
        return $this->hasOne(Shipment::class, 'review_id', 'id');
    }
}
