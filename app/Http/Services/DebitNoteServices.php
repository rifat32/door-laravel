<?php

namespace App\Http\Services;

use App\Models\Bank;
use App\Models\DebitNote;
use Illuminate\Support\Facades\DB;
use App\Http\Utils\TransactionUtils;

trait DebitNoteServices
{
    use TransactionUtils;
    public function createDebitNoteService($request)
    {
        $bank = Bank::where([
            "account_number" => $request["account_number"],
            "wing_id" => $request["wing_id"]
        ])->first();
        if (!$bank) {
            return response()->json(["message" => "bank account number not found"], 404);
        }
        $request["bank_id"]  = $bank->id;
        $payment =  DebitNote::create($request->toArray());
        return response()->json(["payment" => $payment], 201);
    }
    public function getDebitNotesService($request)
    {
        $debitNotes =   DebitNote::with("wing", "bill")->paginate(10);
        return response()->json([
            "debitNotes" => $debitNotes
        ], 200);
    }
    public function approveDebitNoteService($request)
    {
        $debitNoteQuery =  DebitNote::where(["id" => $request->id]);
        $debitNote = $debitNoteQuery->first();

        if ($debitNote->status === 0 || $debitNote->status === false) {

            DB::transaction(function () use (&$debitNoteQuery, &$debitNote) {
                $debitNoteQuery->update([
                    "status" => 1
                ]);

                $transaction_id = $this->updateBalanceAndTransaction($debitNote->wing_id, $debitNote->bank_id, $debitNote->account_number, -$debitNote->amount, "creditNote");

                if ($transaction_id !== -1) {
                    DebitNote::where([
                        "id" => $debitNote->id
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
}
