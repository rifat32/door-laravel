<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NonCitizenTaxRequest;
use App\Http\Requests\NonCitizenTaxUpdateRequest;
use App\Http\Services\NonCitizenTaxService;
use Illuminate\Http\Request;

class NonCitizenTaxController extends Controller
{
    use NonCitizenTaxService;
    public function createNonCitizenTax(NonCitizenTaxRequest $request)
    {
        return $this->createNonCitizenTaxService($request);
    }

    public function updateNonCitizenTax(NonCitizenTaxUpdateRequest $request)
    {
        return $this->updateNonCitizenTaxService($request);
    }

    public function getNonCitizenTax(Request $request)
    {
        return $this->getNonCitizenTaxService($request);
    }
    public function getNonCitizenTaxById($id,Request $request)
    {

        return $this->getNonCitizenTaxByIdService($id,$request);
    }

    public function searchNonCitizenTax($term,Request $request)
    {
        return $this->searchNonCitizenTaxService($term,$request);
    }
    public function deleteNonCitizenTax($id,Request $request)
    {
        return $this->deleteNonCitizenTaxService($id,$request);
    }
}
