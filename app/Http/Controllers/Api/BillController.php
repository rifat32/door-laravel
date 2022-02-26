<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BillRequest;
use App\Models\Bill;
use Illuminate\Http\Request;
use App\Http\Services\BillServices;

class BillController extends Controller
{
    use BillServices;

    public function createBill(BillRequest $request)
    {
        return $this->createBillService($request);
    }
    public function getBills(Request $request)
    {

        return $this->getBillsService($request);
    }
    public function getBillsByWing(Request $request, $wingId)
    {

        return $this->getBillsByWingService($request, $wingId);
    }
}
