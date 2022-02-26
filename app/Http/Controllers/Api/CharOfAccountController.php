<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CharOfAccountRequest;
use App\Http\Services\CharOfAccountServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CharOfAccountController extends Controller
{
    use CharOfAccountServices;
    public function getAccounts(Request $request)
    {
        return $this->getAccountsService($request);
    }
    public function createCharOfAccount(CharOfAccountRequest $request)
    {
        return $this->createCharOfAccounServicet($request);
    }
    public function getChartOfAccounts(Request $request)
    {
        return $this->getChartOfAccountsService($request);
    }
}
