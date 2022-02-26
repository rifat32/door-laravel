<?php

namespace App\Http\Services;

use App\Models\Balance;
use App\Models\Bank;
use App\Models\Parchase;
use App\Http\Utils\TransactionUtils;
use App\Models\Requisition;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait RequisitionService
{
    use TransactionUtils;
    public function createRequisitionService($request)
    {

        if (!$request->user()->can("create requisition")) {
            return response()->json([
                "message" => "you do not have permission"
            ], 403);
        }
        $request["user_id"]  = $request->user()->id;

        // if ($request["account_number"]) {
        //     $bank = Bank::where([
        //         "account_number" => $request["account_number"],
        //         "wing_id" => $request["wing_id"]
        //     ])->first();
        //     if (!$bank) {
        //         return response()->json(["message" => "bank account number not found"], 404);
        //     }
        //     $request["bank_id"]  = $bank->id;
        // }
        $requisition = Requisition::create($request->toArray());
        return response()->json(["requisition" => $requisition, "message" => "data saved"], 201);
    }
    public function updateRequisitionService($request)
    {


        if (!$request->user()->can("create requisition") ||  !$request->user()->can("approve requisition")) {
            return response()->json([
                "message" => "you do not have permission"
            ], 403);
        }


        $requisition = tap(Requisition::where(["id" =>  $request["id"]]))->update(
            $request->only(
                "supplier",
                "reference_no",
                "purchase_status",
                "product_id",
                "quantity",
                "wing_id",
            )
        )->with("wing", "product")->first();
        return response()->json(["requisition" => $requisition], 200);
    }
    public function deleteRequisitionService($request, $id)
    {

        if (!$request->user()->can("cancel requisition")) {
            return response()->json([
                "message" => "you do not have permission"
            ], 403);
        }
        Requisition::where(["id" => $id])->delete();
        return response()->json(["ok" => true], 200);
    }


    public function getRequisitionsService($request)
    {
        $createPermission = $request->user()->can("create requisition");
        $approvePermission = $request->user()->can("approve requisition");
        if (!($createPermission || $approvePermission)) {
            return response()->json([
                "message" => "you do not have permission"
            ], 403);
        }
        if ($approvePermission) {
            $requisitionsQuery =  Requisition::where(["is_active" => 0])->with("wing", "product");
        } else {
            $requisitionsQuery =  Requisition::where(["is_active" => 0, "user_id" => $request->user()->id])->with("wing", "product");
        }

        $requisitions = $requisitionsQuery->paginate(10);
        return response()->json([
            "requisitions" => $requisitions
        ], 200);
    }
    public function getRequisitionsReturnService($request)
    {
        $approvePermission = $request->user()->can("approve requisition");
        if (!($approvePermission)) {
            return response()->json([
                "message" => "you do not have permission"
            ], 403);
        }




        $requisitions = Requisition::where(["is_active" => 1])->with("wing", "product")->paginate(10);
        return response()->json([
            "requisitions" => $requisitions
        ], 200);
    }
    public function approveRequisitionServices($request)
    {
        if (!$request->user()->can("approve requisition")) {
            return response()->json([
                "message" => "you do not have permission"
            ], 403);
        }

        $requisition = tap(Requisition::where(["id" =>  $request["id"]]))->update(
            [
                "is_active" => 1
            ]
        )->with("wing", "product")->first();
        return response()->json(["requisition" => $requisition], 200);
    }
    public function getRequisitionsThisMonthReportService($request)
    {
        for ($i = 0; $i <= 30; $i++) {
            $data[$i]["amount"] =  Requisition::whereDate('created_at', Carbon::today()->subDay($i))->where(["is_active" => 1])->with("product:price,id")->get()->sum(function ($requisition) {
                return $requisition->quantity * $requisition->product->price;
            });;
            $data[$i]["date"] = Carbon::today()->subDay($i);
        }
        return response()->json($data, 200);
    }





    public function requisitionToParchaseService($request)
    {
        if (!$request->user()->can("approve requisition")) {
            return response()->json([
                "message" => "you do not have permission"
            ], 403);
        }


        $requisitionQuery =   Parchase::where(["id" => $request->id]);
        $requisition = $requisitionQuery->first();

        if ($requisition->status_type !== "parchase") {
            DB::transaction(function () use (&$requisitionQuery, &$requisition) {
                $requisitionQuery->update([
                    "status_type" => "parchase"
                ]);
                if ($requisition->bank_id) {
                    $amount = -$requisition->amount();
                    $transaction_id = $this->updateBalanceAndTransaction($requisition->wing_id, $requisition->bank_id, $requisition->account_number, $amount, "parchase");
                    if ($transaction_id !== -1) {
                        Parchase::where([
                            "id" => $requisition->id
                        ])->update([
                            "transaction_id" => $transaction_id
                        ]);
                    }
                }
            });
            return response()->json([
                "ok" => true
            ], 200);
        } else {
            return response()->json([
                "message" => "duplicate entry"
            ], 409);
        }
    }
}
