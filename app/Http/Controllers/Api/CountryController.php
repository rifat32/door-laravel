<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Http\Requests\CountryUpdateRequest;
use App\Http\Services\CountryService;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    use CountryService;
    public function createCountry(CountryRequest $request)
    {
        return $this->createCountryService($request);
    }

    public function updateCountry(CountryUpdateRequest $request)
    {
        return $this->updateCountryService($request);
    }

    public function getCountry(Request $request)
    {
        return $this->getCountrysService($request);
    }
    public function getAllCountry(Request $request)
    {
        return $this->getAllCountrysService($request);
    }

    public function getCountryById($id,Request $request)
    {

        return $this->getCountryByIdService($id,$request);
    }

    public function searchCountry($term,Request $request)
    {
        return $this->searchCountryService($term,$request);
    }
    public function searchExactCountry($term,Request $request)
    {
        return $this->searchExactCountryService($term,$request);
    }

    public function deleteCountry($id,Request $request)
    {
        return $this->deleteCountryService($id,$request);
    }
}
