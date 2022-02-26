<?php

namespace App\Http\Services;

use App\Models\Payment;
use App\Http\Utils\TransactionUtils;
use App\Models\Bank;
use App\Models\DebitNote;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait PaymentServices
{
    use TransactionUtils;
    public function createPaymentService($request)
    {
        $bank = Bank::where([
            "account_number" => $request["account_number"],
            "wing_id" => $request["wing_id"]
        ])->first();
        if (!$bank) {
            return response()->json(["message" => "bank account number not found"], 404);
        }
        $request["bank_id"]  = $bank->id;
        $payment =  Payment::create($request->toArray());
        return response()->json(["payment" => $payment], 201);
    }
    public function updatePaymentService($request)
    {
        $bank = Bank::where([
            "account_number" => $request["account_number"],
            "wing_id" => $request["wing_id"]
        ])->first();
        if (!$bank) {
            return response()->json(["message" => "bank account number not found"], 404);
        }
        $request["bank_id"]  = $bank->id;

        $data["payment"] =   tap(Payment::where(["id" => $request->id]))->update($request->only(
            "date",
            "amount",
            "account_number",
            "description",
            "category",
            "reference",
            "wing_id",
        ))->with("wing")->first();
        return response()->json($data, 200);
    }
    public function deletePaymentService($request, $id)
    {
        Payment::where(["id" => $id])->delete();
        return response()->json(["ok" => true], 200);
    }
    public function getPaymentService($request)
    {
        $payments =   Payment::with("wing")->paginate(10);
        return response()->json([
            "payments" => $payments
        ], 200);
    }
    public function approvePaymentService($request)
    {
        $paymentQuery =  Payment::where(["id" => $request->id]);
        $payment = $paymentQuery->first();

        if ($payment->status === 0 || $payment->status === false) {

            DB::transaction(function () use (&$paymentQuery, &$payment) {
                $paymentQuery->update([
                    "status" => 1
                ]);

                $transaction_id = $this->updateBalanceAndTransaction($payment->wing_id, $payment->bank_id, $payment->account_number, -$payment->amount, "creditNote");

                if ($transaction_id !== -1) {
                    Payment::where([
                        "id" => $payment->id
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
    public function getExpenseThisMonthReportService($request)
    {
        for ($i = 0; $i <= 30; $i++) {
            $paymentTotalAmount = Payment::whereDate('created_at', Carbon::today()->subDay($i))->where(["status" => 1])->sum("amount");
            $debitNoteTotalAmount = DebitNote::whereDate('created_at', Carbon::today()->subDay($i))->where(["status" => 1])->sum("amount");
            $data[$i]["amount"] = $paymentTotalAmount + $debitNoteTotalAmount;
            $data[$i]["date"] = Carbon::today()->subDay($i);
        }
        return response()->json($data, 200);
    }
}
