<?php

namespace App\Http\Controllers;

use App\Mail\orderconfirmationmail;
use App\Models\Color;
use App\Models\Option;
use App\Models\OptionValue;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use App\Models\Order;
use App\Models\OrderDetailOption;
use App\Models\OrderPayment;
use App\Models\ProductColor;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class OrderPayments extends Controller
{
    /*
    Stripe Payment Start
     */
    function stripepayments(Request $request)
    {
        $stripeprivatekey = env('STRIPE_PRIVATE_KEY', '');
        $stripe = new StripeClient($stripeprivatekey);

        $domain = env("DOMAIN", "https://shop.woodcroftdoorsandcabinets.co.uk");
        if (!isset($request->order_id)) {
            return "invalid request";
        }
        $orderid  = $request->order_id;

        $order = Order::where("id", $orderid)->first();
        if (empty($order)) {
            return "invalid request";
        }

        $checkorderexist = OrderPayment::where("order_id", $orderid)->first();

        if (!empty($checkorderexist)) {
            $session = $stripe->checkout->sessions->retrieve($checkorderexist->checkout_session_id);
            if ($session["status"] == "complete") {
                echo "<script>window.location.href='{$domain}/order-completed?status=Order Completed'</script>";
                return;
            } elseif ($session["status"] == "expired") {
                echo "<script>window.location.href='{$domain}/order-canceled?status=Payment Cancelled'</script>";
                return;
            } else {
                goto a;
            }
        }
/* Creating a customer */

$customername=sprintf("%s %s",$order->fname,$order->lname);
$phonenumber=$order->phone;
$country=$order->country->iso2;
$state=$order->state->name;
$city=$order->city;
$billingaddress1=$order->billing_address;
$billingaddress2=$order->billing_address2;
$zipcode=$order->zipcode;
$email=$order->email;
$customerid=$order->customer_id;
$address=["city"=>$city,"country"=>$country,"line1"=>$billingaddress1,"line2"=>$billingaddress2,"postal_code"=>$zipcode,"state"=>$state];
$iscustomerexists=$stripe->customers->all(["email"=>$email])["data"];
$value=null;
if(!empty($iscustomerexists)){
    foreach($iscustomerexists as $customers){
        if(count($value=array_diff($customers->shipping->address->toarray(),$address)) == 0)
        {
            $customer=$customers;
                 break;
        }else{
            $customer=$customers;
        }
}
}else{
    $customer=$stripe->customers->create([
        "address"=>$address,
        "name"=>$customername,
        "phone"=>$phonenumber,
        "email"=>$email,
        "metadata"=>["customer_id"=>$customerid],
        "shipping"=>["address"=>$address,"name"=>$customername]

    ]);
}
if(!empty($value))
{

    $customer=$stripe->customers->update(
        $customer->id,
        ["shipping"=>["address"=>$address,"name"=>$customername]]);
}
/* creating a customer end */
        $coupon = $order->coupon;
        $shipping_name = "Standard";
        $shipping_price = $order->shipping;
        $orderdetails = [];
        $array = [
            "data" => true,
            "Product_info" => [],
            "coupon_data" => [
                'discounts' => [],
            ],
            "shipping_data" => ['shipping_options' => [['shipping_rate_data' => ['display_name' => 'Shipping Cost', 'type' => 'fixed_amount','tax_behavior' => 'exclusive','tax_code' => 'txcd_92010001', 'fixed_amount' => ['amount' => 0 * 100, 'currency' => 'gbp']]]],],
            "vat" => [],
            "order_id" => ['metadata' => ['order_id' => $orderid]],
            "payment_method" => ['payment_method_types' => ['card'],],
        ];
        $array["shipping_data"]["shipping_options"][0]["shipping_rate_data"]["display_name"] = $shipping_price == 0 ? "Free Shipping" : $shipping_name;
        $array["shipping_data"]["shipping_options"][0]["shipping_rate_data"]["fixed_amount"]["amount"] = ($shipping_price * 100);


        $coupon_discount_price = 0;
        $i = 0;
        $productdescription = null;
        $hingeholesarray = [1 => "Left Hanging Door", 2 => "Right Hanging Door", 3 => "Drill Both Sides", 4 => "Top Hanging Door", 5 => "Bottom Hanging Door"];
        $extraholearray = [1 => "From Top", 2 => "From Bottom"];
        $optionarray = [];
        foreach ($order->order_details as $key => $order_detail) {
            $orderdetailarray = $order_detail->toarray();
            $type = $order_detail->product->type;
            /////////////////////////
            $ordervaritiondata = Order::with("order_details.options", "order_details.product", "order_details.product_variation", "order_details.variation", "order_details.color", "order_details.options.option", "order_details.options.option_value", "coupon", "payment")
                ->where([
                    "id" => $orderid
                ])

                ->first();
            $ordervaritiondata = $ordervaritiondata->toarray();

            $height = $ordervaritiondata['order_details'][$i]['product_variation'];
            $width = $ordervaritiondata['order_details'][$i]['variation'];

            /////////////////////////
            //get the product color image start
            if ($order_detail->selectedProductColor != null) {
                $colormain = Color::where("code", $order_detail->selectedProductColor)->first();
                $colorname = $colormain->name;
                $colorid = $colormain->id;
                $color = ProductColor::where("color_id", $colorid)->where("product_id", $order_detail->product->id)->first()->toArray();
                $color["colorname"] = $colorname;
                $image = asset($color["color_image"]);
            } else {
                $image = asset($order_detail->product->image);
            }
            //get the product color image end
            // get the product option start
            $orderdetailoption = OrderDetailOption::where("order_detail_id", $order_detail->id)->get();
            foreach ($orderdetailoption as $optionkey => $optionvalue) {
                $options = Option::where("id", $optionvalue["option_id"])->first();
                $optionvaluedb = OptionValue::where("id", $optionvalue["option_value_id"])->where("option_id", $optionvalue["option_id"])->first();
                $optionarray[$optionkey] = ["option_name" => $options->name, "value_name" => $optionvaluedb['name']];
            }
            // get the product option end
            //make description for the product start
            if (!empty($height) && empty($orderdetailarray['is_custom_size'])) {
                $heightdes = "Height: {$height['name']} MM, ";
                $productdescription .= $heightdes;
            }
            if (!empty($width) && empty($orderdetailarray['is_custom_size'])) {
                $widthdes = "Width: {$width['name']} MM, ";
                $productdescription .= $widthdes;
            }
            if (!empty($orderdetailarray['is_custom_size'])) {
                $customheighdes = "Custom Height: {$orderdetailarray['custom_height']} MM, ";
                $customwidthdes = "Custom Width: {$orderdetailarray['custom_width']} MM, ";
                $productdescription .= $customheighdes . $customwidthdes;
            }
            if (!empty($orderdetailarray["selectedProductColor"])) {
                $colordes = "Colour: {$colorname}, ";
                $productdescription .= $colordes;
            }
            if (!empty($orderdetailarray["is_hinge_holes"])) {
                $hingeholesdes = "Hinge Holes Orientation: {$hingeholesarray[$orderdetailarray['orientation_id']]}, Hinge Holes From Top: {$orderdetailarray['hinge_holes_from_top']} MM, Hinge Holes From Bottom: {$orderdetailarray['hinge_holes_from_bottom']} MM, ";
                $productdescription .= $hingeholesdes;
            }
            if (!empty($orderdetailarray["is_extra_holes"])) {
                $extraholesdes = "Extra Hole Direction: {$extraholearray[$orderdetailarray['extra_holes_direction_id']]} Extra Hole Value:{$orderdetailarray['extra_holes_value']} MM, ";
                $productdescription .= $extraholesdes;
            }
            if (!empty($orderdetailarray["selected_length"])) {
                $lenghtdes = "Length: {$orderdetailarray["selected_length"]} MM, ";
                $productdescription .= $lenghtdes;
            }
            if (!empty($orderdetailarray["selected_height"])) {
                $lenghtdes = "Length: {$orderdetailarray["selected_height"]} MM, ";
                $productdescription .= $lenghtdes;
            }
            if (!empty($orderdetailarray["selected_width"])) {
                $lenghtdes = "Length: {$orderdetailarray["selected_width"]} MM, ";
                $productdescription .= $lenghtdes;
            }
            if (!empty($orderdetailarray["selected_panel_thickness"])) {
                $panelthicknessdes = "Panel Thickness: {$orderdetailarray["selected_panel_thickness"]}, ";
                $productdescription .= $panelthicknessdes;
            }
            if (!empty($orderdetailarray["selected_panel_length"])) {
                $panellengthdes = "Panel Length: {$orderdetailarray["selected_panel_length"]} MM, ";
                $productdescription .= $panellengthdes;
            }
            if (!empty($orderdetailarray["selected_panel_depth"])) {
                $paneldepthdes = "Panel Depth: {$orderdetailarray["selected_panel_depth"]} MM, ";
                $productdescription .= $paneldepthdes;
            }

            foreach ($optionarray as $key => $value) {
                $optiondes = "{$value['option_name']}: {$value['value_name']}";
                $productdescription .= $optiondes;
            }
            $productdescription = rtrim($productdescription, ", ");
            //make description for the product end


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
                    'tax_behavior' => 'exclusive',
                    'product_data' => [
                        'name' => $order_detail->product->name,
                        /* "images" => [$image], */
                        /* "description" => $productdescription, */


                    ],
                    'unit_amount' => $product_price * 100,
                ],
                'quantity' => $order_detail->qty,

            ];
            if (!empty($productdescription)) {
                $array["Product_info"][$i]['price_data']['product_data']["description"] = $productdescription;
            }
            $i++;
            $productdescription = null;
        }
        if (!empty($coupon) && $coupon_discount_price != 0 && $order_detail->coupon_discount_type == "percentage") {


            $couponstripe = $stripe->coupons->create([
                "name" => $coupon->name,
                "percent_off" => $order_detail->coupon_discount_amount,
                "currency" => "gbp",
                "duration" => "once",
                /* "max_redemptions" => 1, */
            ]);

            array_push($array["coupon_data"]["discounts"], ["coupon" => $couponstripe->id ?? "coupon"]);
        }elseif(!empty($coupon) && $coupon_discount_price != 0 ){
            $couponstripe = $stripe->coupons->create([
                "name" => $coupon->name,
                "amount_off" => $order_detail->coupon_discount_amount * 100,
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
                    "success_url" => "https://shop.woodcroftdoorsandcabinets.co.uk/order-completed",
                    "cancel_url" => "https://shop.woodcroftdoorsandcabinets.co.uk/order-canceled",
                    'mode' => 'payment',
                    'line_items' => [$array["Product_info"]],
                    "metadata" => $array["order_id"]["metadata"],
                    "payment_method_types" => $array["payment_method"]["payment_method_types"],
                    "discounts" => $array["coupon_data"]["discounts"],
                    "shipping_options" => $array["shipping_data"]["shipping_options"],
                    'automatic_tax' => ['enabled' => true],
                    "customer"=>$customer->id,

                ]
            );
        }


        /// create record in database start
        try {
            $_order_id = $session["metadata"]["order_id"];
            $_paymentinent = $session["payment_intent"];
            $_checkout_session_id = $session["id"];
            $_currency = $session["currency"];
            $_amount = $session["amount_total"];
            $_status = $session["payment_status"];
            $_receipt_url = null;
            $_payer_email = null;
            OrderPayment::create([
                "checkout_session_id" => $_checkout_session_id,
                "amount" => $_amount / 100,
                "payer_email" =>  $_payer_email,
                "currency" => $_currency,
                "order_id" => $_order_id,
                "receipt_url" => $_receipt_url,
                "payment_intent" => $_paymentinent,
                "status" =>  $_status,
                "payment_gateway_name" => "stripe",
            ]);
        } catch (\Exception $e) {
            report($e);
            error_log($e->getMessage());
        }


        /// create record in database end
        a:
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
