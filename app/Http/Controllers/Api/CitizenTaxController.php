<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CitizenTaxRequest;
use App\Http\Requests\CitizenTaxUpdateRequest;
use App\Http\Services\CitizenTaxService;
use Illuminate\Http\Request;

class CitizenTaxController extends Controller
{
    use CitizenTaxService;
    public function createCitizenTax(CitizenTaxRequest $request)
    {
        return $this->createCitizenTaxService($request);
    }

    public function updateCitizenTax(CitizenTaxUpdateRequest $request)
    {
        return $this->updateCitizenTaxService($request);
    }

    public function getCitizenTax(Request $request)
    {
        return $this->getCitizenTaxService($request);
    }
    public function getCitizenTaxById($id,Request $request)
    {

        return $this->getCitizenTaxByIdService($id,$request);
    }

    public function searchCitizenTax($term,Request $request)
    {
        return $this->searchCitizenTaxService($term,$request);
    }
    public function deleteCitizenTax($id,Request $request)
    {
        return $this->deleteCitizenTaxService($id,$request);
    }
}
