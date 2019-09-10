<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class booking extends Model
{
    public function room_type(){
        return $this->belongsTo(room_type::class);
    }

    public function room_info(){
        return $this->belongsTo(room_info::class);
    }
}
