<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChairmanRequest;
use App\Http\Requests\ChairmanUpdateRequest;
use App\Http\Services\ChairmanService;
use Illuminate\Http\Request;

class ChairmanController extends Controller
{
    use ChairmanService;
    public function createChairman(ChairmanRequest $request)
    {
        return $this->createChairmanService($request);
    }

    public function updateChairman(ChairmanUpdateRequest $request)
    {
        return $this->updateChairmanService($request);
    }

    public function getChairman(Request $request)
    {
        return $this->getChairmanService($request);
    }
    public function getChairmanById($id,Request $request)
    {

        return $this->getChairmanByIdService($id,$request);
    }

    public function searchChairman($term,Request $request)
    {
        return $this->searchChairmanService($term,$request);
    }
    public function deleteChairman($id,Request $request)
    {
        return $this->deleteChairmanService($id,$request);
    }
}
