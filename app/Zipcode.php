<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zipcode extends Model
{
    /**
     * Get the state that owns the municipality.
     */
    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }
}
