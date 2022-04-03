<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\LabReportTemplate;
use App\Models\Union;
use Exception;

trait UnionService
{
    use ErrorUtil;
    public function createUnionService($request)
    {

        try{
            $data['data'] =   Union::create($request->all());
            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateUnionService($request)
    {

        try{
            $data['data'] = tap(Union::where(["id" =>  $request["id"]]))->update(
                $request->only(
                    "name"
                )
            )->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getUnionsService($request)
    {

        try{
            $data['data'] =   Union::paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getAllUnionsService($request)
    {

        try{
            $data['data'] =   Union::all();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function getUnionByIdService($id,$request)
    {

        try{
            $data['data'] =   Union::where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchUnionService($term,$request)
    {
        try{
            $data['data'] =   Union::
        where("name","like","%".$term."%")
        ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function deleteUnionService($id,$request)
    {
        try{
            Union::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
