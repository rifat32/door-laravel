<?php

namespace App\Http\Utils;

use App\Models\Shipping;
use Exception;
use Illuminate\Support\Facades\DB;

trait CalculateShipping
{
    // this function do all the task and returns transaction id or -1
    public function calculateShippingUtil($subTotal,  $country_id, $state_id)
    {
        $shipping = null;
        $shippingMain = Shipping::where([
            "state_id" => $state_id
        ])->first();
        $state_id = $shippingMain->state_id ?? null;

        if (empty($state_id)) {
            $state_id = NULL;
            goto a;
        }



        $shippingQuery =   Shipping::where([
            // "rate_name" => $shipping_name,
            "country_id" => $country_id,
            "state_id" => $state_id,
        ]);
        $shipping = $shippingQuery->where("minimum", "<=", $subTotal)
            ->where("maximum", ">=", $subTotal)
            ->orderByDesc(DB::raw("shippings.price+0"))
            ->first();
        if (!$shipping) {
            $shipping = Shipping::where([
                // "rate_name" => $shipping_name,
                "country_id" => $country_id,
                "state_id" => $state_id,
            ])
                ->where("minimum", "<=", $subTotal)
                ->where("maximum", NULL)
                ->orderByDesc(DB::raw("shippings.price+0"))
                ->first();
        }
        if (!$shipping) {
            $shipping =  Shipping::where([
                // "rate_name" => $shipping_name,
                "country_id" => $country_id,
                "state_id" => $state_id,
            ])
                ->where("minimum", "=", 0)
                ->where("maximum", ">=", $subTotal)
                ->orderByDesc(DB::raw("shippings.price+0"))
                ->first();
        }
        if (!$shipping) {
            $shipping =  Shipping::where([
                // "rate_name" => $shipping_name,
                "country_id" => $country_id,
                "state_id" => $state_id,
            ])
                ->where("minimum", "=", 0)
                ->where("maximum", NULL)
                ->orderByDesc(DB::raw("shippings.price+0"))
                ->first();
        }

        a:
        // if state is all then this query will run

        $shipping = $shipping != null ? $shipping : null;
        if (!$shipping) {
            $shippingQuery =   Shipping::where([
                // "rate_name" => $shipping_name,
                "country_id" => $country_id,
                "state_id" => NULL,
            ]);
            $shipping = Shipping::where([
                // "rate_name" => $shipping_name,
                "country_id" => $country_id,
                "state_id" => $state_id,
            ])
                ->where("minimum", "<=", $subTotal)
                ->where("maximum", ">=", $subTotal)
                ->orderByDesc(DB::raw("shippings.price+0"))
                ->first();
        }



        if (!$shipping) {
            $shipping =  Shipping::where([
                // "rate_name" => $shipping_name,
                "country_id" => $country_id,
                "state_id" => $state_id,
            ])
                ->where("minimum", "<=", $subTotal)
                ->where("maximum", NULL)
                ->orderByDesc(DB::raw("shippings.price+0"))
                ->first();
        }
        if (!$shipping) {
            $shipping = Shipping::where([
                // "rate_name" => $shipping_name,
                "country_id" => $country_id,
                "state_id" => $state_id,
            ])
                ->where("minimum", "=", 0)
                ->where("maximum", ">=", $subTotal)
                ->orderByDesc(DB::raw("shippings.price+0"))
                ->first();
        }


        if (!$shipping) {
            $shipping =  Shipping::where([
                // "rate_name" => $shipping_name,
                "country_id" => $country_id,
                "state_id" => $state_id,
            ])
                ->where("minimum", "=", 0)
                ->where("maximum", NULL)
                ->orderByDesc(DB::raw("shippings.price+0"))
                ->first();
        }

        if ($shipping) {

            $data["price"] = $shipping->price;
            $data["shipping_name"] = $shipping->rate_name;
        } else {

            $finalShipping =  Shipping::where([
                // "rate_name" => $shipping_name,
                "country_id" => $country_id,
                "state_id" => NULL,
            ])
                ->where("minimum", "=", 0)
                ->where("maximum", NULL)
                ->orderByDesc(DB::raw("shippings.price+0"))
                ->first();
            if ($finalShipping) {
                $data["price"] = $finalShipping->price;
                $data["shipping_name"] = $finalShipping->shipping_name;
            } else {
                $data["price"] = 0;
                $data["shipping_name"] = "";
            }
        }



        return $data;
    }
}
