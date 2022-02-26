<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParchaseRequest;
use App\Models\Parchase;
use Illuminate\Http\Request;
use App\Http\Services\ParchaseServices;

class ParchaseController extends Controller
{
    use ParchaseServices;
    public function createParchase(ParchaseRequest $request)
    {
        return $this->createParchaseService($request);
    }

    public function getParchases(Request $request)
    {
        return $this->getParchasesService($request);
    }
    public function getPurchasesReturn(Request $request)
    {
        return $this->getParchasesReturnService($request);
    }

    public function updatePurchase(ParchaseRequest $request)
    {
        return $this->updateParchaseService($request);
    }
    public function deletePurchase(Request $request, $id)
    {
        return $this->deleteParchaseService($request, $id);
    }
    public function approvePurchase(Request $request)
    {
        return $this->approvePurchaseService($request);
    }
    public function getPurchaseThisMonthReport(Request $request)
    {
        return $this->getPurchaseThisMonthReportService($request);
    }
}
