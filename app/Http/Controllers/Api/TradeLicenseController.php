<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TradeLicenseRequest;
use App\Http\Requests\TradeLicenseUpdateRequest;
use App\Http\Services\TradeLicenseService;
use Illuminate\Http\Request;

class TradeLicenseController extends Controller
{
    use TradeLicenseService;
    public function createTradeLicense(TradeLicenseRequest $request)
    {
        return $this->createTradeLicenseService($request);
    }

    public function updateTradeLicense(TradeLicenseUpdateRequest $request)
    {
        return $this->updateTradeLicenseService($request);
    }

    public function getTradeLicense(Request $request)
    {
        return $this->getTradeLicenseService($request);
    }
    public function getTradeLicenseById($id,Request $request)
    {

        return $this->getTradeLicenseByIdService($id,$request);
    }

    public function searchTradeLicense($term,Request $request)
    {
        return $this->searchTradeLicenseService($term,$request);
    }
    public function deleteTradeLicense($id,Request $request)
    {
        return $this->deleteTradeLicenseService($id,$request);
    }
}
