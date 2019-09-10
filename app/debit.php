<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class debit extends Model
{
    public function expenditure(){
        return $this->belongsTo(expenditure::class);
    }

    public function payment(){
        return $this->belongsTo(payment::class);
    }
}
