<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportTemplateRequest;
use App\Http\Services\ReportTemplateService;
use Illuminate\Http\Request;

class LabReportTemplateController extends Controller
{
    use ReportTemplateService;
    public function createTemplate(ReportTemplateRequest $request)
    {
        return $this->createReportTemplateService($request);
    }
    public function deleteTemplate(ReportTemplateRequest $request)
    {
        return $this->deleteReportTemplateService($request);
    }
    public function updateTemplate(Request $request)
    {
        return $this->updateReportTemplateService($request);
    }

    public function getTemplates(Request $request)
    {

        return $this->getReportTemplatesService($request);
    }
    public function getAllTemplates(Request $request)
    {

        return $this->getAllReportTemplatesService($request);
    }
}
