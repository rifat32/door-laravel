<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NonCitizenTaxPaymentRequest;
use App\Http\Requests\NonCitizenTaxPaymentUpdateRequest;
use App\Http\Services\NonCitizenTaxPaymentService;
use Illuminate\Http\Request;

class NonCitizenTaxPaymentController extends Controller
{
    use NonCitizenTaxPaymentService;
    public function createNonCitizenTaxPayment(NonCitizenTaxPaymentRequest $request)
    {
        return $this->createNonCitizenTaxPaymentService($request);
    }

    public function updateNonCitizenTaxPayment(NonCitizenTaxPaymentUpdateRequest $request)
    {
        return $this->updateNonCitizenTaxPaymentService($request);
    }

    public function getNonCitizenTaxPayment(Request $request)
    {
        return $this->getNonCitizenTaxPaymentService($request);
    }
    public function getNonCitizenTaxPaymentById($id,Request $request)
    {

        return $this->getNonCitizenTaxPaymentByIdService($id,$request);
    }

    public function searchNonCitizenTaxPayment($term,Request $request)
    {
        return $this->searchNonCitizenTaxPaymentService($term,$request);
    }
    public function deleteNonCitizenTaxPayment($id,Request $request)
    {
        return $this->deleteNonCitizenTaxPaymentService($id,$request);
    }
}
