<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VariationTemplateRequest;
use App\Http\Requests\VariationTemplateUpdateRequest;
use App\Http\Services\VariationTemplateService;
use Illuminate\Http\Request;

class VariationTemplateController extends Controller
{
    use VariationTemplateService;
    public function createVariationTemplate(VariationTemplateRequest $request)
    {
        return $this->createVariationTemplateService($request);
    }

    public function updateVariationTemplate(VariationTemplateUpdateRequest $request)
    {
        return $this->updateVariationTemplateService($request);
    }

    public function getVariationTemplate(Request $request)
    {
        return $this->getVariationTemplateService($request);
    }
    public function getAllVariationTemplate(Request $request)
    {
        return $this->getAllVariationTemplateService($request);
    }

    public function getVariationTemplateById($id,Request $request)
    {

        return $this->getVariationTemplateByIdService($id,$request);
    }

    public function searchVariationTemplate($term,Request $request)
    {
        return $this->searchVariationTemplateService($term,$request);
    }
    public function deleteVariationTemplate($id,Request $request)
    {
        return $this->deleteVariationTemplateService($id,$request);
    }
}
