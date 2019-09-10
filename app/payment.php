<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    public function credit(){
        return $this->hasMany(credit::class,'payment_id');
    }

    public function debit(){
        return $this->hasMany(debit::class,'payment_id');
    }
}
