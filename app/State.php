<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    /**
     * Get the municipalities for the state.
     */
    public function municipalities()
    {
        return $this->hasMany(Municipality::class);
    }
}
