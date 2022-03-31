<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComplainRequest;
use App\Http\Requests\ComplainUpdateRequest;
use App\Http\Services\ComplainService;
use Illuminate\Http\Request;

class ComplainController extends Controller
{
    use ComplainService;
    public function createComplain(ComplainRequest $request)
    {
        return $this->createComplainService($request);
    }

    public function updateComplain(ComplainUpdateRequest $request)
    {
        return $this->updateComplainService($request);
    }

    public function getComplain(Request $request)
    {
        return $this->getComplainService($request);
    }
    public function getComplainById($id,Request $request)
    {

        return $this->getComplainByIdService($id,$request);
    }

    public function searchComplain($term,Request $request)
    {
        return $this->searchComplainService($term,$request);
    }
    public function deleteComplain($id,Request $request)
    {
        return $this->deleteComplainService($id,$request);
    }
}
