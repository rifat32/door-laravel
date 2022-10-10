<?php

namespace App\Http\Controllers;

use App\Mail\orderconfirmationmail;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class OrderPayments extends Controller
{
    /*
    Stripe Payment Start
     */
    function stripepayments(Request $request)
    {

        $orderid  = $request->order_id;
        $order = Order::where("id", $orderid)->first();
        $coupon = $order->coupon;
        $shipping_name = "Standard";
        $shipping_price = $order->shipping;



        // return response()->json($order);
        $stripeprivatekey = env('STRIPE_PRIVATE_KEY', '');

        $stripe = new StripeClient($stripeprivatekey);

        // $array=[
        // [] - Product Info
        // []- coupon info
        // [] - shipping
        // [] - vat
        // ];
        /*
        ** if coupon exist then i have to made coupon first then i have to pass this in array
        ** To save data in database i have to made one webhook url because when data submit this url will be called
        */

        $array = [
            "data" => true,
            "Product_info" => [],
            "coupon_data" => [
                'discounts' => [],
            ],
            "shipping_data" => ['shipping_options' => [['shipping_rate_data' => ['display_name' => 'Shipping Cost', 'type' => 'fixed_amount', 'fixed_amount' => ['amount' => 0 * 100, 'currency' => 'gbp']]]],],
            "vat" => [],
            "order_id" => ['metadata' => ['order_id' => $orderid]],
            "payment_method" => ['payment_method_types' => ['card', 'klarna'],],
        ];
        $array["shipping_data"]["shipping_options"][0]["shipping_rate_data"]["display_name"] = $shipping_price == 0 ? "Free Shipping" : $shipping_name;
        $array["shipping_data"]["shipping_options"][0]["shipping_rate_data"]["fixed_amount"]["amount"] = ($shipping_price * 100);


        $coupon_discount_price = 0;
        $i = 0;
        foreach ($order->order_details as $key => $order_detail) {
            $product_price = 0;
            $product_price = $order_detail->price;
            // if ($order_detail->product->type == "variable") {
            //     // $product_price = $order_detail->variation->price;
            //     // echo "if block product price " . $product_price . "<br>";
            //     $product_price = $order_detail->price;
            // }
            // else if($order_detail->product->type == "panel") {
            //     $product_price = $order_detail->price;

            // }
            // else {
            //     // $product_price = $order_detail->product->variations[0]->price;
            //     // echo "else block product price " . $product_price . "<br>";
            //     $product_price = $order_detail->price;
            // }
            if ($order_detail->coupon_discount_type == "percentage") {
                $coupon_discount_price += (($order_detail->coupon_discount_amount * $product_price) / 100);
            } else {
                $coupon_discount_price +=   $order_detail->coupon_discount_amount;
            }


            /*   echo $order_detail->product->name . " " . $order_detail->product->variations[0]->price . " " . $order_detail->qty . "<br>"; */
            $array["Product_info"][$i] = [
                'price_data' => [
                    'currency' => 'gbp',

                    'product_data' => [
                        'name' => $order_detail->product->name,

                    ],
                    'unit_amount' => $product_price * 100,
                ],
                'quantity' => $order_detail->qty,
            ];
            $i++;
        }
        if (!empty($coupon) && $coupon_discount_price != 0) {


            $couponstripe = $stripe->coupons->create([
                "name" => $coupon->name,
                "amount_off" => $coupon_discount_price * 100,
                "currency" => "gbp",
                "duration" => "once",
                /* "max_redemptions" => 1, */
            ]);

            array_push($array["coupon_data"]["discounts"], ["coupon" => $couponstripe->id ?? "coupon"]);
        }
        /*  echo "<pre>";
        print_r($couponstripe);
        print_r($array); */

        /*  echo $coupon->name . "<br>";
        echo $coupon->discount_type . "<br>";
        echo $coupon->discount_amount . "<br>"; */

        $url = null;
        if ($array["data"]) {
            /*
 shipping data availabe object and coupon data availabe object
 $session = $stripe->checkout->sessions->create(
                [
                    "success_url" => "https://door-next.vercel.app/other/order-completed",
                    "cancel_url" => "https://door-next.vercel.app/other/not-found",
                    'mode' => 'payment',
                    $array["shipping_data"],
                    'line_items' => $array["Product_info"],
                      "discounts" => $array["coupon_data"]["discounts"],
                ]
            ); */
            $session = $stripe->checkout->sessions->create(
                [
                    "success_url" => "https://shop.woodcroftdoorsandcabinets.co.uk/other/order-completed",
                    "cancel_url" => "https://shop.woodcroftdoorsandcabinets.co.uk/other/not-found",
                    'mode' => 'payment',
                    'line_items' => $array["Product_info"],
                    "metadata" => $array["order_id"]["metadata"],
                    "payment_method_types" => $array["payment_method"]["payment_method_types"],
                    "discounts" => $array["coupon_data"]["discounts"],
                    "shipping_options" => $array["shipping_data"]["shipping_options"],

                ]
            );
        }
        $url = $session["url"] ?? null;
        if ($url != null) {
            sleep(1);
            echo "<script>window.location.href='$url'</script>";
        } else {
            echo "<script>window.location.href='127.0.0.1'</script>";
        }
    }

    /*
    /////////////////////////////////////////////////////////////////////////
    Stripe Payment end
    */
    function paypalpayment()
    {
        $provider = new PayPalClient([]);
        $token = $provider->getAccessToken();
        $tokendetails = $provider->setAccessToken($token);
        $order = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "USD",
                    "value" => 31,
                ],
            ],],
            "application_context" => [
                "shipping_preference" => "NO_SHIPPING",
                "cancel_url" => "http://localhost:8000/paypalcancel",
                "return_url" => "http://localhost:8000/payaplsuccess",
                "brand_name" => "Doors And Cabinet",

            ],
        ]);
        /*    echo "<pre>";
        print_r($order);
        echo "</pre>"; */

        return redirect($order["links"][1]["href"]);
    }
    function payaplsuccess(Request $request)
    {
        $provider = new PayPalClient([]);

        $provider->getAccessToken();
        $provider->authorizePaymentOrder($request["token"]);
        $response = $provider->capturePaymentOrder($request["token"]);
        echo $request["PayerID"];
        echo $request["token"];
        dd($response);
        // echo $response["status"] ?? "error";
    }
    function paypalcancel(Request $request)
    {
        dd($request->all());
    }
}
