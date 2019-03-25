<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
