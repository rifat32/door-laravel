<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\LabReportTemplate;
use App\Models\Shipping;
use Exception;

trait ShippingService
{
    use ErrorUtil;
    public function createShippingService($request)
    {

        try{
            $insertableData = $request->validated();
            $data['data'] =   Shipping::create($insertableData);
            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateShippingService($request)
    {

        try{
            $updatableData = $request->validated();
            $data['data'] = tap(Shipping::with("state","country")->where(["id" =>  $request["id"]]))->update(
                $updatableData
            )->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getShippingsService($request)
    {

        try{
        $data['data'] =   Shipping::with("state","country")->paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getAllShippingsService($request)
    {

        try{
            $data['data'] =   Shipping::all();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function getShippingByIdService($id,$request)
    {

        try{
            $data['data'] =   Shipping::where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchShippingService($term,$request)
    {
        try {

            $data['data'] =   Shipping::where(function($query) use ($term){
        $query->where("name", "like", "%" . $term . "%");
    })

                ->latest()
                ->paginate(10);

            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function searchExactShippingService($term,$request)
    {
        try{
            $data['data'] =   Shipping::
        where("name","=",$term)
        ->first();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }


    public function deleteShippingService($id,$request)
    {
        try{
            Shipping::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
