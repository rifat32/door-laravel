<?php

namespace App\Http\Services;

use App\Models\Account;
use App\Models\ChartOfAccount;

trait CharOfAccountServices
{
    public function createCharOfAccounServicet($request)
    {
        $data["charOfAccount"] = ChartOfAccount::create($request->toArray());
        return response()->json($data, 201);
    }
    public function getAccountsService($request)
    {
        $data["accounts"] = Account::with("types")->get();
        return response()->json($data, 200);
    }
    public function getChartOfAccountsService($request)
    {
        $data["chart_accounts"] = Account::with("ChartOfAccounts.AccountType")->get();
        return response()->json($data, 200);
    }
}
