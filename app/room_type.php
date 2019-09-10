<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class room_type extends Model
{
    public function room_info(){
        return $this->hasMany(room_info::class,'room_type_id');
    }

    public function booking(){
        return $this->hasMany(booking::class,'room_type_id');
    }
}
