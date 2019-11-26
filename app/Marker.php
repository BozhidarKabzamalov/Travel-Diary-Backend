<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    public function user(){
        return $this->belongsToMany('App\User');
    }
}
