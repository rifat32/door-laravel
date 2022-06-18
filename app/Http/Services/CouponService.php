<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\LabReportTemplate;
use App\Models\Coupon;
use Illuminate\Support\Str;

use Exception;

trait CouponService
{
    use ErrorUtil;
    public function createCouponService($request)
    {

        try{
            $insertableData = $request->validated();
            $insertableData["code"] = str_replace(' ', '', strtolower($insertableData["name"]));
            $insertedData =   Coupon::create($insertableData);
            if(!$insertableData["is_all_category_product"]) {

                foreach($insertableData["products"] as $product){
                    $insertedData->cproducts()->create([
                        "product_id" => $product["id"],
                        "coupon_id" => $insertedData->id
                    ]);
                }

            }
            $data['data'] = $insertedData;
            return response()->json($data, 201);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function updateCouponService($request)
    {

        try{

            $updatableData = $request->validated();

            $data['data'] = tap(Coupon::where(["id" =>  $request["id"]]))->update(
                $updatableData
            )
            ->with("category")
            ->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }


    }
    public function getCouponsService($request)
    {

        try{
            $data['data'] =   Coupon::with("category")->paginate(10);
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }
    public function getAllCouponsService($request)
    {

        try{
            $data['data'] =   Coupon::all();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function getCouponByIdService($id,$request)
    {

        try{
            $data['data'] =   Coupon::where(["id" => $id])->with("category")->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchCouponService($term,$request)
    {
        try{
            $data['data'] =   Coupon::
        where("name","like","%".$term."%")
        ->orWhere("code","like","%".$term."%")
        ->orWhere("discount","like","%".$term."%")
        ->get();
        return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }

    public function deleteCouponService($id,$request)
    {
        try{
            Coupon::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }

    }




}
