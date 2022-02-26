<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\RequisitionRequest;
use App\Models\Parchase;
use Illuminate\Http\Request;
use App\Http\Services\RequisitionService;

class RequisitionController extends Controller
{
    use RequisitionService;
    public function createRequisition(RequisitionRequest $request)
    {
        return $this->createRequisitionService($request);
    }
    public function updateRequisition(RequisitionRequest $request)
    {
        return $this->updateRequisitionService($request);
    }
    public function deleteRequisition(Request $request, $id)
    {
        return $this->deleteRequisitionService($request, $id);
    }
    public function getRequisitions(Request $request)
    {
        return $this->getRequisitionsService($request);
    }
    public function getRequisitionsReturn(Request $request)
    {
        return $this->getRequisitionsReturnService($request);
    }

    public function approveRequisition(Request $request)
    {
        return $this->approveRequisitionServices($request);
    }
    public function getRequisitionsThisMonthReport(Request $request)
    {
        return $this->getRequisitionsThisMonthReportService($request);
    }

    public function requisitionToParchase(Request $request)
    {
        return $this->requisitionToParchaseService($request);
    }
}
