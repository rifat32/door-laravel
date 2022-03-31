<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;


use App\Models\Upazila;
use Exception;

trait UpazilaService
{
    use ErrorUtil;
    public function createUpazilaService($request)
    {

        try{
            $data['data'] =   Upazila::create($request->all());
            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateUpazilaService($request)
    {

        try{
            $data['data'] = tap(Upazila::where(["id" =>  $request["id"]]))->update(
                $request->only(
       "name",
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
    public function getUpazilaService($request)
    {

        try{
            $data['data'] =   Upazila::with("union")->paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getUpazilaByIdService($id,$request)
    {

        try{
            $data['data'] =   Upazila::with("union")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchUpazilaService($term,$request)
    {
        try{
            $data['data'] =   Upazila::with("union")
        ->where("name","like","%".$term."%")
        ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function deleteUpazilaService($id,$request)
    {
        try{
            Upazila::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
