<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NonHoldingCitizenRequest;
use App\Http\Services\NonHoldingCitizenService;
use Illuminate\Http\Request;

class NonHoldingCitizenController extends Controller
{
    use NonHoldingCitizenService;
    public function createCitizen(NonHoldingCitizenRequest $request)
    {
        return $this->createCitizenService($request);
    }

    public function updateCitizen(Request $request)
    {
        return $this->updateCitizenService($request);
    }

    public function getCitizen(Request $request)
    {
        return $this->getCitizenService($request);
    }
    public function getCitizenById($id,Request $request)
    {

        return $this->getCitizenByIdService($id,$request);
    }

    public function searchCitizen($term,Request $request)
    {
        return $this->searchCitizenService($term,$request);
    }
    public function deleteCitizen($id,Request $request)
    {
        return $this->deleteCitizenService($id,$request);
    }
}
