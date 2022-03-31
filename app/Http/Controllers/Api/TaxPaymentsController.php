<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaxPaymentRequest;
use App\Http\Requests\TaxPaymentUpdateRequest;
use App\Http\Services\TaxPaymentService;
use Illuminate\Http\Request;

class TaxPaymentsController extends Controller
{
    use TaxPaymentService;
    public function createTaxPayment(TaxPaymentRequest $request)
    {
        return $this->createTaxPaymentService($request);
    }

    public function updateTaxPayment(TaxPaymentUpdateRequest $request)
    {
        return $this->updateTaxPaymentService($request);
    }

    public function getTaxPayment(Request $request)
    {
        return $this->getTaxPaymentService($request);
    }
    public function getTaxPaymentById($id,Request $request)
    {

        return $this->getTaxPaymentByIdService($id,$request);
    }

    public function searchTaxPayment($term,Request $request)
    {
        return $this->searchTaxPaymentService($term,$request);
    }
    public function deleteTaxPayment($id,Request $request)
    {
        return $this->deleteTaxPaymentService($id,$request);
    }
}
