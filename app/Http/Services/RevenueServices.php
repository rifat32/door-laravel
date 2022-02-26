<?php

namespace App\Http\Services;

use App\Models\Bank;
use App\Models\Revenue;
use Illuminate\Support\Facades\DB;
use App\Http\Utils\TransactionUtils;
use App\Models\CreditNote;
use Illuminate\Support\Carbon;

trait RevenueServices
{
    use TransactionUtils;
    public function createRevenueService($request)
    {
        $bank = Bank::where([
            "account_number" => $request["account_number"],
            "wing_id" => $request["wing_id"]
        ])->first();
        if (!$bank) {
            return response()->json(["message" => "bank account number not found"], 404);
        }

        $request["bank_id"]  = $bank->id;

        $revenue =  Revenue::create($request->toArray());
        return response()->json(["revenue" => $revenue], 201);
    }
    public function updateRevenueService($request)
    {
        $bank = Bank::where([
            "account_number" => $request["account_number"],
            "wing_id" => $request["wing_id"]
        ])->first();
        if (!$bank) {
            return response()->json(["message" => "bank account number not found"], 404);
        }

        $request["bank_id"]  = $bank->id;
        $data["revenue"] =   tap(Revenue::where(["id" => $request->id]))->update($request->only(
            "date",
            "amount",
            "account_number",
            "customer",
            "description",
            "category",
            "reference",
            "wing_id"

        ))->with("wing")->first();
        return response()->json($data, 200);
    }
    public function getRevenuesService($request)
    {
        $revenues =   Revenue::with("wing")->orderByDesc("id")->paginate(10);
        return response()->json([
            "revenues" => $revenues
        ], 200);
    }
    public function deleteRevenueService($request, $id)
    {
        Revenue::where(["id" => $id])->delete();
        return response()->json(["ok" => true], 200);
    }
    public function approveRevenueService($request)
    {
        $revenueQuery =  Revenue::where(["id" => $request->id]);
        $revenue = $revenueQuery->first();

        if ($revenue->status === 0 || $revenue->status === false) {
            DB::transaction(function () use (&$revenueQuery, &$revenue) {
                $revenueQuery->update([
                    "status" => 1
                ]);

                $transaction_id = $this->updateBalanceAndTransaction($revenue->wing_id, $revenue->bank_id, $revenue->account_number, $revenue->amount, "revenue");
                if ($transaction_id !== -1) {
                    Revenue::where([
                        "id" => $revenue->id
                    ])->update([
                        "transaction_id" => $transaction_id
                    ]);
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
    public function getIncomeThisMonthReportService($request)
    {
        for ($i = 0; $i <= 30; $i++) {
            $revenueTotalAmount = Revenue::whereDate('created_at', Carbon::today()->subDay($i))->where(["status" => 1])->sum("amount");
            $creditNoteTotalAmount = CreditNote::whereDate('created_at', Carbon::today()->subDay($i))->where(["status" => 1])->sum("amount");
            $data[$i]["amount"] = $revenueTotalAmount + $creditNoteTotalAmount;
            $data[$i]["date"] = Carbon::today()->subDay($i);
        }
        return response()->json($data, 200);
    }
}
