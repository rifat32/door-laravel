<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DistrictRequest;
use App\Http\Services\DistrictService;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    use DistrictService;
    public function createDistrict(DistrictRequest $request)
    {
        return $this->createDistrictService($request);
    }

    public function updateDistrict(Request $request)
    {
        return $this->updateDistrictService($request);
    }

    public function getDistrict(Request $request)
    {
        return $this->getDistrictsService($request);
    }
    public function getDistrictById($id,Request $request)
    {

        return $this->getDistrictByIdService($id,$request);
    }

    public function searchDistrict($term,Request $request)
    {
        return $this->searchDistrictService($term,$request);
    }
    public function deleteDistrict($id,Request $request)
    {
        return $this->deleteDistrictService($id,$request);
    }
}
