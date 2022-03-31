<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\LabReportTemplate;
use App\Models\District;
use Exception;

trait DistrictService
{
    use ErrorUtil;
    public function createDistrictService($request)
    {

        try{

            $data['data'] =   District::create($request->all());
            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateDistrictService($request)
    {

        try{
            $data['data'] = tap(District::where(["id" =>  $request["id"]]))->update(
                $request->only(
                    "name"
                )
            )->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getDistrictsService($request)
    {

        try{
            $data['data'] =   District::paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getDistrictByIdService($id,$request)
    {

        try{
            $data['data'] =   District::where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchDistrictService($term,$request)
    {
        try{
            $data['data'] =   District::
        where("name","like","%".$term."%")
        ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function deleteDistrictService($id,$request)
    {
        try{
            District::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
