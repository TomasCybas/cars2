<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Spot extends Model
{
    //
    protected $collection = 'spot';

    public function cars() {
        return $this->belongsToMany(Car::class);
    }
}
