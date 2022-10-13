<?php

namespace App\Http\Services;

use App\Http\Utils\CalculateShipping;
use App\Http\Utils\ErrorUtil;
use App\Models\LabReportTemplate;
use App\Models\Shipping;
use App\Models\ShippingName;
use Exception;
use Illuminate\Support\Facades\DB;

trait ShippingService
{
    use ErrorUtil, CalculateShipping;
    public function createShippingService($request)
    {

        try {

            $insertableData = $request->validated();
            if(count($insertableData["states"])){
                foreach($insertableData["states"] as $state) {
                    if (!$insertableData["minimum"]) {
                        $insertableData["minimum"] = 0;
                    }
                    $insertableData["state_id"] = $state["id"];
                    Shipping::create($insertableData);
                }
            } else {
                Shipping::create($insertableData);
            }

            $data['ok'] = true;

            return response()->json($data, 201);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function updateShippingService($request)
    {

        try {
            $updatableData = $request->validated();
            if (!$updatableData["minimum"]) {
                $updatableData["minimum"] = 0;
            }
            $data['data'] = tap(Shipping::with("state", "country")->where(["id" =>  $request["id"]]))->update(
                $updatableData
            )->first();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function getShippingsService($request)
    {

        try {
            $data['data'] =   Shipping::with("state", "country")->paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function getAllShippingsService($request)
    {

        try {
            $data['data'] =   Shipping::with("state", "country")->all();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }

    public function getShippingByIdService($id, $request)
    {

        try {
            $data['data'] =   Shipping::with("state", "country")->where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function searchShippingService($term, $request)
    {
        try {

            $data['data'] =   Shipping::with("state", "country")->where(function ($query) use ($term) {
                $query->where("name", "like", "%" . $term . "%");
            })

                ->latest()
                ->paginate(10);

            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function searchExactShippingService($term, $request)
    {
        try {
            $data['data'] =   Shipping::with("state", "country")->where("name", "=", $term)
                ->first();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }


    public function deleteShippingService($id, $request)
    {
        try {
            Shipping::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }


    public function calculateShippingService($subTotal,$shipping_name, $country_id, $state_id, $request)
    {
        try {
            return response()->json([
                "price" => $this->calculateShippingUtil($subTotal,$shipping_name, $country_id, $state_id)["price"]
            ],200);

        } catch (Exception $e) {

            return $this->sendError($e, 500);
        }
    }

    public function createShippingNameService($request)
    {

        try {
            $insertableData = $request->validated();

            $data['data'] =   ShippingName::create($insertableData);
            return response()->json($data, 201);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function updateShippingNameService($request)
    {

        try {
            $updatableData = $request->validated();

            $data['data'] = tap(ShippingName::where(["id" =>  $request["id"]]))->update(
                $updatableData
            )->first();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function getShippingsNameService($request)
    {

        try {
            $data['data'] =   ShippingName::paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function getAllShippingsNameService($request)
    {

        try {
            $data['data'] =   ShippingName::all();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }

    public function getShippingNameByIdService($id, $request)
    {

        try {
            $data['data'] =   ShippingName::where(["id" => $id])->first();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function searchShippingNameService($term, $request)
    {
        try {

            $data['data'] =   ShippingName::where(function ($query) use ($term) {
                $query->where("name", "like", "%" . $term . "%");
            })

                ->latest()
                ->paginate(10);

            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
    public function searchExactShippingNameService($term, $request)
    {
        try {
            $data['data'] =   ShippingName::where("name", "=", $term)
                ->first();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }


    public function deleteShippingNameService($id, $request)
    {
        try {
            ShippingName::where(["id" => $id])->delete();
            return response()->json(["ok" => true], 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }

}
