<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class MapView extends Controller
{
    public function __invoke()
    {
        return view('map');
    }
}
