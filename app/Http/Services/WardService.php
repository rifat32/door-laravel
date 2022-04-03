<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;


use App\Models\Ward;
use Exception;

trait WardService
{
    use ErrorUtil;
    public function createWardService($request)
    {

        try{
            $data['data'] =   Ward::create($request->all());
            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateWardService($request)
    {

        try{
            $data['data'] = tap(Ward::where(["id" =>  $request["id"]]))->update(
                $request->only(
       "ward_no",
       "union_id"
                )
            )
            ->with("union")
            ->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getWardService($request)
    {

        try{
            $data['data'] =   Ward::with("union")->paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getWardByUnionService($request,$unionId)
    {

        try{
            $data['data'] =   Ward::with("union")->where(["union_id" => $unionId])->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }


    public function getWardByIdService($id,$request)
    {

        try{
            $data['data'] =   Ward::with("union")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchWardService($term,$request)
    {
        try{
            $data['data'] =   Ward::with("union")
        ->where("ward_no","like","%".$term."%")
        ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function deleteWardService($id,$request)
    {
        try{
            Ward::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
