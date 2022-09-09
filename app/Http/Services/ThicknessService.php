<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\LabReportTemplate;
use App\Models\Thickness;
use Exception;

trait ThicknessService
{
    use ErrorUtil;
    public function createThicknessService($request)
    {

        try{
            $insertableData = $request->validated();
            $data['data'] =   Thickness::create($insertableData);
            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateThicknessService($request)
    {

        try{
            $updatableData = $request->validated();
            $data['data'] = tap(Thickness::where(["id" =>  $request["id"]]))->update(
                $updatableData
            )->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getThicknesssService($request)
    {

        try{
            $data['data'] =   Thickness::paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getAllThicknesssService($request)
    {

        try{
            $data['data'] =   Thickness::all();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function getThicknessByIdService($id,$request)
    {

        try{
            $data['data'] =   Thickness::where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchThicknessService($term,$request)
    {
        try {

            $data['data'] =   Thickness::where(function($query) use ($term){
        $query->where("thickness", "like", "%" . $term . "%");
    })

                ->latest()
                ->paginate(10);

            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function searchExactThicknessService($term,$request)
    {
        try{
            $data['data'] =   Thickness::
        where("thickness","=",$term)
        ->first();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }


    public function deleteThicknessService($id,$request)
    {
        try{
            Thickness::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
