<?php

namespace App\Http\Utils;

use App\Models\Shipping;
use Exception;
use Illuminate\Support\Facades\DB;

trait CalculateShipping
{
    // this function do all the task and returns transaction id or -1
    public function calculateShippingUtil($subTotal, $shipping_name,$country_id, $state_id)
    {
        if (!$state_id) {
            $state_id = NULL;
            goto a;
        }
     $shippingQuery =   Shipping::where([
            "rate_name" => $shipping_name,
            "country_id" => $country_id,
            "state_id" => $state_id,
     ]);
        $shipping = $shippingQuery->where("minimum", "<=", $subTotal)
            ->where("maximum", ">=", $subTotal)
            ->orderByDesc(DB::raw("shippings.price+0"))
            ->first();
        if (!$shipping) {
            $shipping = $shippingQuery
                ->where("minimum", "<=", $subTotal)
                ->where("maximum", NULL)
                ->orderBy(DB::raw("shippings.price+0"))
                ->first();
        }
        if (!$shipping) {
            $shipping = $shippingQuery
                ->where("minimum", "=", 0)
                ->where("maximum", ">=", $subTotal)
                ->orderBy(DB::raw("shippings.price+0"))
                ->first();
        }
        if (!$shipping) {
            $shipping = $shippingQuery
                ->where("minimum", "=", 0)
                ->where("maximum", NULL)
                ->orderBy(DB::raw("shippings.price+0"))
                ->first();
        }
        a:
        // if state is all then this query will run
        $shippingQuery =   Shipping::where([
            "rate_name" => $shipping_name,
            "country_id" => $country_id,
            "state_id" => $state_id,
     ]);
            $shipping = $shippingQuery
                ->where("minimum", "<=", $subTotal)
                ->where("maximum", ">=", $subTotal)
                ->orderByDesc(DB::raw("shippings.price+0"))
                ->first();

        if (!$shipping) {
            $shipping = $shippingQuery
                ->where("minimum", "<=", $subTotal)
                ->where("maximum", NULL)
                ->orderBy(DB::raw("shippings.price+0"))
                ->first();
        }
        if (!$shipping) {
            $shipping = $shippingQuery
                ->where("minimum", "=", 0)
                ->where("maximum", ">=", $subTotal)
                ->orderBy(DB::raw("shippings.price+0"))
                ->first();
        }


        if (!$shipping) {
            $shipping = $shippingQuery
                ->where("minimum", "=", 0)
                ->where("maximum", NULL)
                ->orderBy(DB::raw("shippings.price+0"))
                ->first();
        }

        if ($shipping) {
            $price = $shipping->price;
        } else {
            $price = 0;
        }



return $price;






    }
}
