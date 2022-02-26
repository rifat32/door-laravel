<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferBalanceRequest;
use App\Models\Balance;
use Illuminate\Http\Request;
use App\Http\Services\BalanceServices;

class BalanceController extends Controller
{
    use BalanceServices;
    public function getTotalBalance(Request $request)
    {

        return $this->getTotalBalanceService($request);
    }
    public function getBalanceByWingAndBank(Request $request, $wing_id, $bank_id)
    {

        return $this->getBalanceByWingAndBankService($request, $wing_id, $bank_id);
    }
    public function getBalanceByWing(Request $request, $wing_id)
    {

        return $this->getBalanceByWingService($request, $wing_id);
    }
    public function transferBalance(TransferBalanceRequest $request)
    {
        return $this->transferBalanceService($request);
    }
    public function getTransfers(Request $request)
    {
        return $this->getTransfersService($request);
    }
    public function getTransfersByAccountNumber(Request $request, $account_number)
    {
        return $this->getTransfersByAccountNumberService($request, $account_number);
    }
}
