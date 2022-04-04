<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\Village;
use Exception;

trait VillageService
{
    use ErrorUtil;
    public function createVillageService($request)
    {

        try{
            $data['data'] =   Village::create($request->all());
            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateVillageService($request)
    {

        try{
            $data['data'] = tap(Village::where(["id" =>  $request["id"]]))->update(
                $request->only(
                    "name",
                    "ward_id",
                    "union_id"
                )
            )
            ->with("ward.union")
            ->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getVillageService($request)
    {

        try{
            $data['data'] =   Village::with("ward.union")->paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getVillageByIdService($id,$request)
    {

        try{
            $data['data'] =   Village::with("union","ward")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchVillageService($term,$request)
    {
        try{
            $data['data'] =   Village::with("union","ward")
        ->where("name","like","%".$term."%")
        ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function deleteVillageService($id,$request)
    {
        try{
            Village::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
