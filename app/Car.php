<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Car extends Model
{

    protected $collection = 'car';


    public function spots() {
        return $this->hasMany(Spot::class);
    }
}
