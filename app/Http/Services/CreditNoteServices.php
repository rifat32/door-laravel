<?php

namespace App\Http\Services;

use App\Models\Bank;
use App\Models\CreditNote;
use Illuminate\Support\Facades\DB;
use App\Http\Utils\TransactionUtils;

trait CreditNoteServices
{
    use TransactionUtils;
    public function createCreditNoteService($request)
    {
        $bank = Bank::where([
            "account_number" => $request["account_number"],
            "wing_id" => $request["wing_id"]
        ])->first();
        if (!$bank) {
            return response()->json(["message" => "bank account number not found"], 404);
        }

        $request["bank_id"]  = $bank->id;
        $creditNote =  CreditNote::create($request->toArray());
        return response()->json(["creditNote" => $creditNote], 201);
    }
    public function updateCreditNoteService($request)
    {
        $bank = Bank::where([
            "account_number" => $request["account_number"],
            "wing_id" => $request["wing_id"]
        ])->first();
        if (!$bank) {
            return response()->json(["message" => "bank account number not found"], 404);
        }

        $request["bank_id"]  = $bank->id;
        $data["creditNote"] =   tap(CreditNote::where(["id" => $request->id]))->update($request->only(
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
    public function getCreditNotesService($request)
    {
        $creditNotes =   CreditNote::with("wing")->orderByDesc("id")->paginate(10);
        return response()->json([
            "creditNotes" => $creditNotes
        ], 200);
    }
    public function approveCreditNoteService($request)
    {
        $creditNoteQuery =  CreditNote::where(["id" => $request->id]);
        $creditNote = $creditNoteQuery->first();

        if ($creditNote->status === 0 || $creditNote->status === false) {
            DB::transaction(function () use (&$creditNoteQuery, &$creditNote) {
                $creditNoteQuery->update([
                    "status" => 1
                ]);

                $transaction_id = $this->updateBalanceAndTransaction($creditNote->wing_id, $creditNote->bank_id, $creditNote->account_number, $creditNote->amount, "creditNote");
                if ($transaction_id !== -1) {
                    CreditNote::where([
                        "id" => $creditNote->id
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
    public function deleteCreditNoteService($request, $id)
    {
        CreditNote::where(["id" => $id])->delete();
        return response()->json(["ok" => true], 200);
    }
}
