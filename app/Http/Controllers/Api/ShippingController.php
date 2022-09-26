<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingRequest;
use App\Http\Requests\ShippingUpdateRequest;
use App\Http\Services\ShippingService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    use ShippingService;
    public function createShipping(ShippingRequest $request)
    {
        return $this->createShippingService($request);
    }

    public function updateShipping(ShippingUpdateRequest $request)
    {
        return $this->updateShippingService($request);
    }

    public function getShipping(Request $request)
    {
        return $this->getShippingsService($request);
    }
    public function getAllShipping(Request $request)
    {
        return $this->getAllShippingsService($request);
    }

    public function getShippingById($id,Request $request)
    {

        return $this->getShippingByIdService($id,$request);
    }

    public function searchShipping($term,Request $request)
    {
        return $this->searchShippingService($term,$request);
    }
    public function searchExactShipping($term,Request $request)
    {
        return $this->searchExactShippingService($term,$request);
    }

    public function deleteShipping($id,Request $request)
    {
        return $this->deleteShippingService($id,$request);
    }
    public function calculateShipping($subTotal,$country_id,$state_id,Request $request) {
        return $this->calculateShippingService($subTotal,$country_id,$state_id,$request);
    }
}
