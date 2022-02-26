<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RevenueRequest;
use App\Models\Balance;
use App\Models\Revenue;
use Illuminate\Http\Request;
use App\Http\Services\RevenueServices;

class RevenueController extends Controller
{
    use RevenueServices;
    public function createRevenue(RevenueRequest $request)
    {
        return $this->createRevenueService($request);
    }
    public function updateRevenue(RevenueRequest $request)
    {
        return $this->updateRevenueService($request);
    }


    public function getRevenues(Request $request)
    {
        return $this->getRevenuesService(($request));
    }
    public function deleteRevenue(Request $request, $id)
    {
        return $this->deleteRevenueService($request, $id);
    }
    public function approveRevenue(Request $request)
    {
        return $this->approveRevenueService($request);
    }
    public function getIncomeThisMonthReport(Request $request)
    {
        return $this->getIncomeThisMonthReportService($request);
    }
}
