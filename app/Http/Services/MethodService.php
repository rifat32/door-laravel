<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\LabReportTemplate;
use App\Models\Method;
use Exception;

trait MethodService
{
    use ErrorUtil;
    public function createMethodService($request)
    {

        try{

            $data['data'] =   Method::create($request->all());
            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateMethodService($request)
    {

        try{
            $data['data'] = tap(Method::where(["id" =>  $request["id"]]))->update(
                $request->only(
                    "name"
                )
            )->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getMethodsService($request)
    {

        try{
            $data['data'] =   Method::paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getMethodByIdService($id,$request)
    {

        try{
            $data['data'] =   Method::where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchMethodService($term,$request)
    {
        try{
            $data['data'] =   Method::
        where("name","like","%".$term."%")
        ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function deleteMethodService($id,$request)
    {
        try{
            Method::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
