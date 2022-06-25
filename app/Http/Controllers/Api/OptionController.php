<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OptionTemplateRequest;
use App\Http\Requests\OptionTemplateUpdateRequest;
use App\Http\Services\OptionTemplateService;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    use OptionTemplateService;
    public function createOptionTemplate(OptionTemplateRequest $request)
    {
        return $this->createOptionTemplateService($request);
    }

    public function updateOptionTemplate(OptionTemplateUpdateRequest $request)
    {
        return $this->updateOptionTemplateService($request);
    }

    public function getOptionTemplate(Request $request)
    {
        return $this->getOptionTemplateService($request);
    }
    public function getAllOptionTemplate(Request $request)
    {
        return $this->getAllOptionTemplateService($request);
    }

    public function getOptionTemplateById($id,Request $request)
    {

        return $this->getOptionTemplateByIdService($id,$request);
    }

    public function searchOptionTemplate($term,Request $request)
    {
        return $this->searchOptionTemplateService($term,$request);
    }
    public function deleteOptionTemplate($id,Request $request)
    {
        return $this->deleteOptionTemplateService($id,$request);
    }
}
