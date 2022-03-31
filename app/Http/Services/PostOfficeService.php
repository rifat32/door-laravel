<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\PostOffice;
use Exception;

trait PostOfficeService
{
    use ErrorUtil;
    public function createPostOfficeService($request)
    {

        try{
            $data['data'] =   PostOffice::create($request->all());
            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updatePostOfficeService($request)
    {

        try{
            $data['data'] = tap(PostOffice::where(["id" =>  $request["id"]]))->update(
                $request->only(
                    "name",
                    "ward_id",
                    "union_id"
                )
            )
            ->with("union","ward")
            ->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getPostOfficeService($request)
    {

        try{
            $data['data'] =   PostOffice::with("union","ward")->paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getPostOfficeByIdService($id,$request)
    {

        try{
            $data['data'] =   PostOffice::with("union","ward")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchPostOfficeService($term,$request)
    {
        try{
            $data['data'] =   PostOffice::with("union","ward")
        ->where("name","like","%".$term."%")
        ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function deletePostOfficeService($id,$request)
    {
        try{
            PostOffice::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
