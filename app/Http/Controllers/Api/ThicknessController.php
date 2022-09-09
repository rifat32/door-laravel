<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThicknessRequest;
use App\Http\Requests\ThicknessUpdateRequest;
use App\Http\Services\ThicknessService;
use Illuminate\Http\Request;

class ThicknessController extends Controller
{
    use ThicknessService;
    public function createThickness(ThicknessRequest $request)
    {
        return $this->createThicknessService($request);
    }

    public function updateThickness(ThicknessUpdateRequest $request)
    {
        return $this->updateThicknessService($request);
    }

    public function getThickness(Request $request)
    {
        return $this->getThicknesssService($request);
    }
    public function getAllThickness(Request $request)
    {
        return $this->getAllThicknesssService($request);
    }

    public function getThicknessById($id,Request $request)
    {

        return $this->getThicknessByIdService($id,$request);
    }

    public function searchThickness($term,Request $request)
    {
        return $this->searchThicknessService($term,$request);
    }
    public function searchExactThickness($term,Request $request)
    {
        return $this->searchExactThicknessService($term,$request);
    }

    public function deleteThickness($id,Request $request)
    {
        return $this->deleteThicknessService($id,$request);
    }
}
