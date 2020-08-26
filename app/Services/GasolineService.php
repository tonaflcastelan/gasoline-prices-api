<?php

namespace App\Services;

use App\GasolinePrice;
use App\Zipcode;
use Exception;
use Illuminate\Support\Facades\Http;

class GasolineService
{
    protected $url;

    protected $pageSize;

    public function __construct()
    {
        $this->url = config('constants.service_gas_price');
        $this->pageSize = config('constants.page_size');
    }

    /**
     * Compute process to get gasoline price
     */
    public function compute() : void
    {
        try {
            $totalPages = $this->totalPages();
            for ($i = 1; $i <= $totalPages; $i++) {
                $response = Http::get($this->url . '?pageSize=' . $this->pageSize . '&page=' . $i);
                $this->processData($response['results']);
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Get total results
     */
    private function totalPages(): float
    {
        $response = Http::get($this->url . '?pageSize=' . $this->pageSize);
        $countData = $response['pagination'];
        return ceil($countData['total'] / $countData['pageSize']);
    }

    /**
     * Get state and municipality for current price
     */
    private function processData(array $data): void
    {
        foreach ($data as $row) {
            $zip = $row['codigopostal'];
            if (strlen($zip) === 4) {
                $zip = 0 . $zip;
            }
            $zipCode = Zipcode::with('municipality', 'municipality.state')->where('zipcode', $zip)->first();
            if ($zipCode) {
                $this->saveGasolinePrice($row, $zipCode);
            }
        }
    }
    /**
     * Save in DB results
     */
    private function saveGasolinePrice(array $row, object $zipCode): void
    {
        $gasolinePrice = [];
        $gasolinePrice['state_id'] = $zipCode->municipality->state->id;
        $gasolinePrice['municipality_id'] = $zipCode->municipality_id;
        $gasolinePrice['uuid'] = $row['_id'];
        $gasolinePrice['street'] = $row['calle'];
        $gasolinePrice['rfc'] = $row['rfc'];
        $gasolinePrice['date_insert'] = $row['date_insert'];
        $gasolinePrice['regular'] = $row['regular'] ? $row['regular'] : 0.00;
        $gasolinePrice['colony'] = $row['colonia'];
        $gasolinePrice['permission_number'] = $row['numeropermiso'];
        $gasolinePrice['application_date'] = $row['fechaaplicacion'];
        $gasolinePrice['longitude'] = $row['longitude'];
        $gasolinePrice['latitude'] = $row['latitude'];
        $gasolinePrice['premium'] = $row['premium'] ? $row['premium'] : 0.00;
        $gasolinePrice['business_name'] = $row['razonsocial'];
        $gasolinePrice['zipcode'] = $row['codigopostal'];
        $gasolinePrice['dieasel'] = $row['dieasel'] ? $row['premium'] : null;

        print_r($gasolinePrice);
        print("Insertando datos para el servicio {$gasolinePrice['uuid']}");
        GasolinePrice::create($gasolinePrice);
    }
}