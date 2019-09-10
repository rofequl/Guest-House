<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class credit extends Model
{
    public function income_source(){
        return $this->belongsTo(income_source::class);
    }

    public function payment(){
        return $this->belongsTo(payment::class);
    }
}
