<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabReportRequest;
use App\Http\Services\LabReportService;
use Illuminate\Http\Request;

class LabReportController extends Controller
{
    use LabReportService;
    public function createLabReport(LabReportRequest $request)
    {
        return $this->createLabReportService($request);
    }
    public function deleteLabReport(LabReportRequest $request)
    {
        return $this->deleteLabReportService($request);
    }
    public function updateLabReport(Request $request)
    {
        return $this->updateLabReportService($request);
    }

    public function getLabReports(Request $request)
    {

        return $this->getLabReportsService($request);
    }

}
