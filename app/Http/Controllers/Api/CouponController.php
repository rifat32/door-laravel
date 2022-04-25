<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Http\Requests\CouponUpdateRequest;
use App\Http\Services\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    use CouponService;
    public function createCoupon(CouponRequest $request)
    {
        return $this->createCouponService($request);
    }

    public function updateCoupon(CouponUpdateRequest $request)
    {
        return $this->updateCouponService($request);
    }

    public function getCoupon(Request $request)
    {
        return $this->getCouponsService($request);
    }
    public function getAllCoupon(Request $request)
    {
        return $this->getAllCouponsService($request);
    }

    public function getCouponById($id,Request $request)
    {

        return $this->getCouponByIdService($id,$request);
    }

    public function searchCoupon($term,Request $request)
    {
        return $this->searchCouponService($term,$request);
    }
    public function deleteCoupon($id,Request $request)
    {
        return $this->deleteCouponService($id,$request);
    }
}
