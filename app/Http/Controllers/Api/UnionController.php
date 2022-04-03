<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnionRequest;
use App\Http\Services\UnionService;
use Illuminate\Http\Request;

class UnionController extends Controller
{
    use UnionService;
    public function createUnion(UnionRequest $request)
    {
        return $this->createUnionService($request);
    }

    public function updateUnion(Request $request)
    {
        return $this->updateUnionService($request);
    }

    public function getUnion(Request $request)
    {
        return $this->getUnionsService($request);
    }
    public function getAllUnion(Request $request)
    {
        return $this->getAllUnionsService($request);
    }

    public function getUnionById($id,Request $request)
    {

        return $this->getUnionByIdService($id,$request);
    }

    public function searchUnion($term,Request $request)
    {
        return $this->searchUnionService($term,$request);
    }
    public function deleteUnion($id,Request $request)
    {
        return $this->deleteUnionService($id,$request);
    }
}
