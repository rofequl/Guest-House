<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class income_source extends Model
{
    public function credit(){
        return $this->hasMany(credit::class,'income_source_id');
    }
}
