<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankRequest;
use App\Models\Bank;
use Illuminate\Http\Request;
use App\Http\Services\BankServices;

class BankController extends Controller
{
    use BankServices;
    public function createBank(BankRequest $request)
    {

        return $this->createBankService($request);
    }
    public function updateBank(BankRequest $request)
    {

        return $this->UpdateBankService($request);
    }
    public function deleteBank(Request $request)
    {
        return $this->deleteBankService($request);
    }
    public function getBanks(Request $request)
    {
        return $this->getBanksService($request);
    }
    public function getBanksByWing(Request $request, $wing_id)
    {

        return $this->getBanksByWingService($request, $wing_id);
    }
}
