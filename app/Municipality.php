<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    /**
     * Get the state that owns the municipality.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the municipalities for the state.
     */
    public function zipcodes()
    {
        return $this->hasMany(Zipcode::class);
    }
}
