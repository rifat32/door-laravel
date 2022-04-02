<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CitizenRequest;
use App\Http\Requests\CitizenUpdateRequest;
use App\Http\Services\CitizenService;
use Illuminate\Http\Request;

class CitizenController extends Controller
{
    use CitizenService;
    public function createCitizen(CitizenRequest $request)
    {
        return $this->createCitizenService($request);
    }

    public function updateCitizen(CitizenUpdateRequest $request)
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
