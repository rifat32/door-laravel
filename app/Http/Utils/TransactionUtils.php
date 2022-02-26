<?php

namespace App\Http\Utils;

use App\Models\Balance;
use App\Models\Transaction;

trait TransactionUtils
{
    // this function do all the task and returns transaction id or -1
    public function updateBalanceAndTransaction($wing_id, $bank_id, $account_number, $amount, $type)
    {

        $balanceQuery = Balance::where([
            "wing_id" => $wing_id,
            "bank_id" => $bank_id
        ]);
        $balance = $balanceQuery->first();
        if (!$balance) {
            $balanceQuery->insert(
                [
                    "wing_id" => $wing_id,
                    "bank_id" => $bank_id,
                    "amount" =>  $amount
                ]
            );
        } else {
            $balanceQuery->update(
                [
                    "amount" => $balance->amount + $amount

                ]
            );
        }
        $transaction_id =    Transaction::insertGetId([
            "account_number" => $account_number,
            "amount" => $amount,
            "type" => $type
        ]);
        return $transaction_id;


        return -1;
    }
}
