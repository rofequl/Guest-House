<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class expenditure extends Model
{
    public function debit(){
        return $this->hasMany(debit::class,'expenditure_id');
    }
}
