<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\WardRequest;
use App\Http\Services\WardService;
use Illuminate\Http\Request;

class WardController extends Controller
{
    use WardService;
    public function createWard(WardRequest $request)
    {
        return $this->createWardService($request);
    }

    public function updateWard(Request $request)
    {
        return $this->updateWardService($request);
    }

    public function getWard(Request $request)
    {
        return $this->getWardService($request);
    }
    public function getWardByUnion(Request $request,$unionId)
    {
        return $this->getWardByUnionService($request,$unionId);
    }

    public function getWardById($id,Request $request)
    {

        return $this->getWardByIdService($id,$request);
    }

    public function searchWard($term,Request $request)
    {
        return $this->searchWardService($term,$request);
    }
    public function deleteWard($id,Request $request)
    {
        return $this->deleteWardService($id,$request);
    }
}
