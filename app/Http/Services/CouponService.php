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

            $coupon = tap(Coupon::where(["id" =>  $request["id"]]))->update(
                collect($updatableData)->only([
                    "name",
                    "category_id",
                    "code",
                    "is_all_category_product",
                    "discount_amount",
                    "discount_type",
                     "expire_date",
                    "is_active",
                ])
                    ->toArray()
            )
            ->with("category")
            ->first();
            $coupon->cproducts()->delete();
            if(!$updatableData["is_all_category_product"]) {
                    foreach($updatableData["products"] as $product){
                    $coupon->cproducts()->create([
                        "product_id" => $product["id"],
                        "coupon_id" => $coupon->id
                    ]);
                }

            }
            $data["data"] = $coupon;
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
            $data['data'] =   Coupon::where(["id" => $id])->with("category","cproducts")->first();
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
    }
    public function searchCouponService($term,$request)
    {
        try {

            $data['data'] =   Coupon::with("category")
           
            ->where(function($query) use ($term){
        $query->where("coupons.name", "like", "%" . $term . "%");
        $query->orWhere("coupons.code", "like", "%" . $term . "%");

    })

->orderByDesc("coupons.id")
                ->paginate(10);

            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
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
