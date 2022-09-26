<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\LabReportTemplate;
use App\Models\Shipping;
use Exception;
use Illuminate\Support\Facades\DB;

trait ShippingService
{
    use ErrorUtil;
    public function createShippingService($request)
    {

        try{
            $insertableData = $request->validated();
            if(!$insertableData["minimum"]) {
                $insertableData["minimum"] = 0;
            }
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
            if(!$updatableData["minimum"]) {
                $updatableData["minimum"] = 0;
            }
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


    public function calculateShippingService($subTotal,$country_id,$state_id,$request) {
        try{
            if(!$state_id) {
                $state_id = NULL;
            }

            $shipping = Shipping::where([
                "country_id" => $country_id,
                "state_id" => $state_id,
            ])
            ->where("minimum","<=",$subTotal)
            ->where("maximum","<=",$subTotal)
            ->orderBy(DB::raw("shippings.price+0"))
            ->first();

            if(!$shipping) {
                $shipping = Shipping::where([
                    "country_id" => $country_id,
                    "state_id" => $state_id,
                ])
                ->where("minimum","<=",$subTotal)
                ->where("maximum",NULL)
                ->orderBy(DB::raw("shippings.price+0"))
                ->first();
            }
            if($shipping){
                $price = $shipping->price;
            } else {
                $price = 0;
            }








return response()->json([
    "price" => $price
]);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
