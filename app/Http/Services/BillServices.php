<?php

namespace App\Http\Services;

use App\Models\Bill;

trait BillServices
{
    public function createBillService($request)
    {

        $bill =   Bill::create($request->toArray());
        return response()->json(["bill" => $bill], 201);
    }
    public function getBillsService($request)
    {
        $bills =   Bill::with("wing")->orderByDesc("id")->paginate(10);
        return response()->json([
            "bills" => $bills
        ], 200);
    }
    public function getBillsByWingService($request, $wingId)
    {
        $bills =   Bill::with("wing")->where([
            "wing_id" => $wingId
        ])->get();
        return response()->json([
            "bills" => $bills
        ], 200);
    }
}
