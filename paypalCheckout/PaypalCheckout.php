<?php

namespace App\Http\Controllers;

use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Http\Request;

class PaypalCheckout extends Controller
{
    function paypalcheckout()
    {
        /* $config = [
            "mode" => "sandbox",
            "sandbox" => [
                "client_id" => "AYq8IiucXWsdVoZdCxLvE0BDj3WY0RInSpXfAGDUWCG73PpRGXp-I0RHAGEs0THKmQcFferapChj5H6G",
                "client_secret" => "EKjCyhgJOFdUtlQpKUbwA8f3ZiencutdLwcxd1hPt-T9KNyGpltXyqAWguwnaVH8Dys6gmRHnub525UL",
            ],
            "payment_action" => "sale",
            "currency" => "USD",
            "locale" => "en_US",
            "notify_url" => "https://example.com",
            "validate_ssl" => true,
        ]; */
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
                "cancel_url" => "http://localhost:8000/cancel",
                "return_url" => "http://localhost:8000/success",
                "brand_name" => "Doors And Cabinet",

            ],
        ]);
        /*    echo "<pre>";
        print_r($order);
        echo "</pre>"; */

        return redirect($order["links"][1]["href"]);
    }
    function success(Request $request)
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
    function cancel(Request $request)
    {
        /* $provider = new PayPalClient([]);
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request["token"]); */
        dd($request->all());
    }
    function test()
    {
        $provider = new PayPalClient([]);
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);
        /*     $test = $provider->listPlans(); */
        /*     $fields = ["id", "product_id", "name", "description"]; */
        /*   $test = $provider->listPlans(); */
        dd($provider->listPlans());
    }
}
