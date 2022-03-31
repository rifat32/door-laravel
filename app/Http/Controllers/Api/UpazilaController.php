<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpazilaRequest;
use App\Http\Services\UpazilaService;
use Illuminate\Http\Request;

class UpazilaController extends Controller
{
    use UpazilaService;
    public function createUpazila(UpazilaRequest $request)
    {
        return $this->createUpazilaService($request);
    }

    public function updateUpazila(Request $request)
    {
        return $this->updateUpazilaService($request);
    }

    public function getUpazila(Request $request)
    {
        return $this->getUpazilaService($request);
    }
    public function getUpazilaById($id,Request $request)
    {

        return $this->getUpazilaByIdService($id,$request);
    }

    public function searchUpazila($term,Request $request)
    {
        return $this->searchUpazilaService($term,$request);
    }
    public function deleteUpazila($id,Request $request)
    {
        return $this->deleteUpazilaService($id,$request);
    }
}
