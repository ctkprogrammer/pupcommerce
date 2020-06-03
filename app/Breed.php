<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Pup;


class Breed extends Model
{
    /** 
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'breed_name', 'user_id'
    ];

    public function users() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pups() {
        return $this->hasMany(Pup::class, 'breed_id', 'id');
    }
}
