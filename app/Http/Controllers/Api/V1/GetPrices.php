<?php

namespace App\Http\Controllers\Api\V1;

use App\GasolinePrice;
use App\Http\Controllers\Controller;
use App\Http\Resources\GasolinePriceCollection;
use Illuminate\Http\Request;

class GetPrices extends Controller
{
    public function __invoke(Request $request)
    {
        $state = $request->input('state');
        $municipality = $request->input('municipality');
        $sort = $request->input('sort');

        $prices = GasolinePrice::where('state_id', $state);

        if ($request->has('municipality')) {
            $prices->where('municipality_id', $municipality);
        }

        $data = $prices->get();
        $sorted = $data->sortBy('regular');
        if ($sort === 'desc') {
            $sorted = $data->sortByDesc('regular');
        }
        
        return new GasolinePriceCollection($sorted->values()->all());
    }
}
