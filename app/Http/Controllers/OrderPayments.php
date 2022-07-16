<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;
use App\Models\Order;

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
            "data" => false,
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

        foreach ($order->order_details as $key => $order_detail) {
            $product_price = 0;
            if ($order_detail->product->type == "variation") {
                $product_price = $order_detail->variation->price;
                echo "if block product price " . $product_price;
            } else {
                $product_price = $order_detail->product->variations[0]->price;
                echo "else block product price " . $product_price;
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
        echo "<pre>";
        print_r($array["Product_info"]);
        echo "</pre>";
        $url = null;
        if ($array["data"]) {
            $session = $stripe->checkout->sessions->create(
                [
                    "success_url" => "http://localhost:3000/other/order-completed",
                    "cancel_url" => "http://localhost:3000/other/not-found",
                    'mode' => 'payment',
                    $array["shipping_data"],
                    'line_items' => [$array["Product_info"]],
                    'discounts' => [$array["coupon_data"]["discounts"]],

                ]
            );
        }
        $url = $session["url"] ?? null;
        sleep(1);
        echo "<script>window.location.href='$url'</script>";
    }
}
