<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\LabReportTemplate;
use App\Models\Style;
use Exception;

trait StyleService
{
    use ErrorUtil;
    public function createStyleService($request)
    {

        try{
            $insertableData = $request->validated();
            $data['data'] =   Style::create($insertableData);
            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateStyleService($request)
    {

        try{
            $updatableData = $request->validated();
            $data['data'] = tap(Style::where(["id" =>  $request["id"]]))->update(
                $updatableData
            )->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getStylesService($request)
    {

        try{
            $data['data'] =   Style::paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getAllStylesService($request)
    {

        try{
            $data['data'] =   Style::all();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function getStyleByIdService($id,$request)
    {

        try{
            $data['data'] =   Style::where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchStyleService($term,$request)
    {
        try{
            $data['data'] =   Style::
        where("name","like","%".$term."%")
        ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function deleteStyleService($id,$request)
    {
        try{
            Style::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
