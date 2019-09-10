<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class room_info extends Model
{
    public function room_type(){
        return $this->belongsTo(room_type::class);
    }

    public function booking(){
        return $this->hasMany(booking::class,'room_type_id','room_type_id');
    }
}
