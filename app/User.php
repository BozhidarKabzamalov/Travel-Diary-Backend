<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class User extends \Eloquent implements Authenticatable
{
    use AuthenticableTrait;

    public function markers(){
        return $this->hasMany('App\Marker');
    }

    public function visitedPlaces(){
        return $this->belongsToMany('App\Place', 'visited_places');
    }

    public function wishlistedPlaces(){
        return $this->belongsToMany('App\Place', 'wishlisted_places');
    }
}
