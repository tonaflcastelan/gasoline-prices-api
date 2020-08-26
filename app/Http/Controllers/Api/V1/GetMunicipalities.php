<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MunicipalityCollection;
use App\Municipality;
use App\State;

class GetMunicipalities extends Controller
{
    public function __invoke(State $state)
    {
        return new MunicipalityCollection(Municipality::where('state_id', $state->id)->get());
    }
}
