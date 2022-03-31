<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VillageRequest;
use App\Http\Services\VillageService;
use Illuminate\Http\Request;

class VillageController extends Controller
{
    use VillageService;
    public function createVillage(VillageRequest $request)
    {
        return $this->createVillageService($request);
    }

    public function updateVillage(Request $request)
    {
        return $this->updateVillageService($request);
    }

    public function getVillage(Request $request)
    {
        return $this->getVillageService($request);
    }
    public function getVillageById($id,Request $request)
    {

        return $this->getVillageByIdService($id,$request);
    }

    public function searchVillage($term,Request $request)
    {
        return $this->searchVillageService($term,$request);
    }
    public function deleteVillage($id,Request $request)
    {
        return $this->deleteVillageService($id,$request);
    }
}
