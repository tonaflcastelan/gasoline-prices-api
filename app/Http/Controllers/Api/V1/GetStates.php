<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StateCollection;
use App\State;

class GetStates extends Controller
{
    public function __invoke()
    {
        return new StateCollection(State::all());
    }
}
