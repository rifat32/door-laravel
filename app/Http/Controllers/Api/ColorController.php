<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ColorRequest;
use App\Http\Requests\ColorUpdateRequest;
use App\Http\Services\ColorService;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    use ColorService;
    public function createColor(ColorRequest $request)
    {
        return $this->createColorService($request);
    }

    public function updateColor(ColorUpdateRequest $request)
    {
        return $this->updateColorService($request);
    }

    public function getColor(Request $request)
    {
        return $this->getColorService($request);
    }
    public function getAllColor(Request $request)
    {
        return $this->getAllColorService($request);
    }

    public function getColorById($id,Request $request)
    {

        return $this->getColorByIdService($id,$request);
    }

    public function searchColor($term,Request $request)
    {
        return $this->searchColorService($term,$request);
    }
    public function deleteColor($id,Request $request)
    {
        return $this->deleteColorService($id,$request);
    }
}
