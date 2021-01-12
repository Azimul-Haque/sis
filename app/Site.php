<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    public function categories(){
        return $this->hasMany('App\Category');
    }
}
