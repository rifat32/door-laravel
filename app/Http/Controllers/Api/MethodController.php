<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MethodRequest;
use App\Http\Services\MethodService;
use Illuminate\Http\Request;

class MethodController extends Controller
{
    use MethodService;
    public function createMethod(MethodRequest $request)
    {
        return $this->createMethodService($request);
    }

    public function updateMethod(Request $request)
    {
        return $this->updateMethodService($request);
    }

    public function getMethod(Request $request)
    {
        return $this->getMethodsService($request);
    }
    public function getMethodById($id,Request $request)
    {

        return $this->getMethodByIdService($id,$request);
    }

    public function searchMethod($term,Request $request)
    {
        return $this->searchMethodService($term,$request);
    }
    public function deleteMethod($id,Request $request)
    {
        return $this->deleteMethodService($id,$request);
    }
}
