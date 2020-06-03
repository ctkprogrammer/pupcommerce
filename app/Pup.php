<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Shipment;
use App\Breed;

class Pup extends Model
{
    protected $fillable = [
        'pup_name', 'price', 'user_id', 'breed_id', 
        'shipment_id', 'photo_url', 'video_url', 'birth', 
        'gender', 'current_weight', 'adult_weight', 
        'neopar_vaccine', 'drumune_max', 'pyrantel_deworm',
        'vet_inspection', 'status'
    ];

    public function users() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function breeds() {
        return $this->belongsTo(Breed::class, 'breed_id', 'id');
    }

    public function shipment() {
        return $this->hasOne(Shipment::class, 'pup_id', 'id');
    }



}
