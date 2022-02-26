<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Balance;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Services\PaymentServices;

class PaymentController extends Controller
{
    use PaymentServices;
    public function createPayment(PaymentRequest $request)
    {
        return $this->createPaymentService($request);
    }
    public function updatePayment(PaymentRequest $request)
    {
        return $this->updatePaymentService($request);
    }
    public function getPayment(Request $request)
    {
        return $this->getPaymentService($request);
    }
    public function approvePayment(Request $request)
    {
        return $this->approvePaymentService($request);
    }
    public function deletePayment(Request $request, $id)
    {
        return $this->deletePaymentService($request, $id);
    }
    public function getExpenseThisMonthReport(Request $request)
    {
        return $this->getExpenseThisMonthReportService($request);
    }
}
