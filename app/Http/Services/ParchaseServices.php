<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Http\Utils\TransactionUtils;
use App\Models\Bank;
use App\Models\Parchase;
use Illuminate\Support\Carbon;

trait ParchaseServices
{
    use TransactionUtils;
    public function createParchaseService($request)
    {
        if ($request["account_number"]) {
            $bank = Bank::where([
                "account_number" => $request["account_number"],
                "wing_id" => $request["wing_id"]
            ])->first();
            if (!$bank) {
                return response()->json(["message" => "bank account number not found"], 404);
            }
            $request["bank_id"]  = $bank->id;
        }
        $request["user_id"]  = $request->user()->id;
        $parchase = Parchase::create($request->toArray());
        return response()->json(["parchase" => $parchase], 201);
        // return    DB::transaction(function () use (&$request) {
        //     $request["status_type"]  = "parchase";
        //     $parchase = Parchase::create($request->toArray());

        //     $amount = -$parchase->amount();
        //     if ($parchase->bank_id) {
        //         $transaction_id = $this->updateBalanceAndTransaction($parchase->wing_id, $parchase->bank_id, $parchase->account_number, $amount, "parchase");
        //         if ($transaction_id !== -1) {
        //             Parchase::where([
        //                 "id" => $parchase->id
        //             ])->update([
        //                 "transaction_id" => $transaction_id
        //             ]);
        //         }
        //     }

        //     return response()->json(["parchase" => $parchase], 201);
        // });
    }
    public function updateParchaseService($request)
    {

        // if (!$request->user()->can("create requisition") ||  !$request->user()->can("approve requisition")) {
        //     return response()->json([
        //         "message" => "you do not have permission"
        //     ], 403);
        // }
        if ($request["account_number"]) {
            $bank = Bank::where([
                "account_number" => $request["account_number"],
                "wing_id" => $request["wing_id"]
            ])->first();
            if (!$bank) {
                return response()->json(["message" => "bank account number not found"], 404);
            }
            $request["bank_id"]  = $bank->id;
        }
        $purchase = tap(Parchase::where(["id" =>  $request["id"]]))->update(
            $request->only(
                "supplier",
                "reference_no",
                "purchase_status",
                "product_id",
                "payment_method",
                "quantity",
                "wing_id",
                "account_number",
                "bank_id"
            )
        )->with("wing", "product")->first();
        return response()->json(["purchase" => $purchase], 200);
    }
    public function deleteParchaseService($request, $id)
    {
        Parchase::where(["id" => $id])->delete();
        return response()->json(["ok" => true], 200);
    }
    public function approvePurchaseService($request)
    {

        $purchase = tap(Parchase::where(["id" =>  $request["id"]]))->update(
            [
                "is_active" => 1
            ]
        )->with("wing", "product")->first();
        return response()->json(["purchase" => $purchase], 200);
    }
    public function getParchasesService($request)
    {


        $createPermission = $request->user()->can("create purchase");
        $returnPermission = $request->user()->can("purchase return");
        if (!($createPermission || $returnPermission)) {
            return response()->json([
                "message" => "you do not have permission"
            ], 403);
        }
        if ($returnPermission) {
            $purchasesQuery =  Parchase::where(["is_active" => 0])->with("wing", "product");
        } else {
            $purchasesQuery =  Parchase::where(["is_active" => 0, "user_id" => $request->user()->id])->with("wing", "product");
        }

        $purchases = $purchasesQuery->paginate(10);
        return response()->json([
            "purchases" => $purchases
        ], 200);
    }
    public function getParchasesReturnService($request)
    {


        $returnPermission = $request->user()->can("purchase return");
        if (!$returnPermission) {
            return response()->json([
                "message" => "you do not have permission"
            ], 403);
        }


        $purchases = Parchase::where(["is_active" => 1])->with("wing", "product")->paginate(10);
        return response()->json([
            "purchases" => $purchases
        ], 200);
    }
    public function getPurchaseThisMonthReportService()
    {
        for ($i = 0; $i <= 30; $i++) {
            $data[$i]["amount"] =  Parchase::whereDate('created_at', Carbon::today()->subDay($i))->where(["is_active" => 1])->with("product:price,id")->get()->sum(function ($requisition) {
                return $requisition->quantity * $requisition->product->price;
            });;
            $data[$i]["date"] = Carbon::today()->subDay($i);
        }
        return response()->json($data, 200);
    }
}
