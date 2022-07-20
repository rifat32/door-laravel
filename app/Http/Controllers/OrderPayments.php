<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;
use App\Models\Order;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class OrderPayments extends Controller
{
    function stripepayments(Request $request)
    {

        $orderid  = $request->order_id;
        $order = Order::where("id", $orderid)->first();
        $coupon = $order->coupon;
        // return response()->json($order);

        $stripe = new StripeClient('sk_test_51Kdy9JE00LZ83RrlSJYCuLu7imUQTeGTUbTgxfAx1lpsVhiPcxcYcegCSGyUW9UY0PdzukNxesWQyCTbK9EFHOWk000bHfgH9O');

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
                'discounts' => [
                    [
                        'coupon' => '4ZxfCouo',
                    ],
                ],
            ],
            "shipping_data" => ['shipping_options' => [['shipping_rate_data' => ['display_name' => 'Shipping Cost', 'type' => 'fixed_amount', 'fixed_amount' => ['amount' => 5 * 100, 'currency' => 'eur']]]],],
            "vat" => [],
            "order_id" => ['metadata' => ['order_id' => 2]],
            "payment_method" => ['payment_method_types' => ['card', 'sofort', 'sepa_debit', 'p24', 'klarna', 'ideal', 'giropay', 'eps', 'bancontact'],],
        ];

        $coupon_discount = 0;
        foreach ($order->order_details as $key => $order_detail) {
            $product_price = 0;
            if ($order_detail->product->type == "variable") {
                $product_price = $order_detail->variation->price;
                echo "if block product price " . $product_price;
            } else {
                $product_price = $order_detail->product->variations[0]->price;
                echo "else block product price " . $product_price;
            }
            if ($order_detail->coupon_discount_type == "percentage") {
                $coupon_discount += (($order_detail->coupon_discount_amount * $product_price) / 100);
            } else {
                $coupon_discount +=   $order_detail->coupon_discount_amount;
            }




            echo $order_detail->product->name . " " . $order_detail->product->variations[0]->price . " " . $order_detail->qty . "<br>";
            $array["Product_info"][$key] = [
                'price_data' => [
                    'currency' => 'eur',

                    'product_data' => [
                        'name' => $order_detail->product->name,

                    ],
                    'unit_amount' => $order_detail->product->variations[0]->price * 100,
                ],
                'quantity' => $order_detail->qty,
            ];
        }
        $url = null;
        if ($array["data"]) {
            $session = $stripe->checkout->sessions->create(
                [
                    "success_url" => "https://door-next.vercel.app/other/order-completed",
                    "cancel_url" => "https://door-next.vercel.app/other/not-found",
                    'mode' => 'payment',
                    $array["shipping_data"],
                    'line_items' => [
                        'price_data' => [
                            'currency' => 'eur',

                            'product_data' => [
                                'name' => "Ashford",

                            ],
                            'unit_amount' => 35 * 100,
                        ],
                        'quantity' => 1,
                    ],

                ]
            );
        }
        $url = $session["url"] ?? null;
        sleep(1);
        echo "<script>window.location.href='$url'</script>";
    }
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
