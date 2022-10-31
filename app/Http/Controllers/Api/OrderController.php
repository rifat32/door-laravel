<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderRequest2;
use App\Http\Utils\CalculateShipping;
use App\Http\Utils\ErrorUtil;
use App\Mail\orderconfirmationmail;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderDetailOption;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\User;
use App\Models\Variation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use function PHPUnit\Framework\isEmpty;

class OrderController extends Controller
{
    use ErrorUtil, CalculateShipping;
    public function create(OrderRequest $request)
    {
        return DB::transaction(function () use (&$request) {

            $coupon = null;
            $insertableData = $request->validated();
            if (!empty($request["order_coupon"]["id"])) {
                $coupon =  Coupon::where([
                    "id" => $request["order_coupon"]["id"],
                    "is_active" => true
                ])
                    ->where(
                        "expire_date",
                        ">",
                        today()
                    )
                    ->first();
            }

            $customer = Customer::where([
                "email" => $request['email']
            ])
                ->first();

            if ($customer) {
                $customer->total_order += 1;
                $customer->fname = $request["fname"];
                $customer->lname = $request["lname"];
                $customer->cname = $request["cname"];
                $customer->phone = $request["phone"];
                $customer->billing_address = $request["billing_address"];
                $customer->billing_address2 = $request["billing_address2"];
                $customer->city = $request["city"];
                $customer->zipcode = $request["zipcode"];
                if ($request['create_account'] == 1) {
                    $customer->type = "registered customer";
                }

                $customer->save();
            } else {
                $customer = Customer::create(
                    [
                        "fname" =>  $request["fname"],
                        "lname" =>  $request["lname"],
                        "cname" =>  $request["cname"],
                        "phone" =>  $request["phone"],
                        "email" =>  $request["email"],
                        "type" =>  $request['create_account'] == 1 ? "registered customer" : "guest",
                        "billing_address" =>  $request["billing_address"],
                        "billing_address2" =>  $request["billing_address2"],
                        "city" =>  $request["city"],
                        "zipcode" =>  $request["zipcode"],
                    ]
                );
            }





            $request["customer_id"] = $customer->id;
            // return response()->json($coupon,500);


            if ($request['create_account'] == 1) {
                $request['first_name'] =   $request['fname'];
                $request['last_name'] =   $request['fname'];
                $request['password'] = Hash::make($request['password']);
                $request['remember_token'] = Str::random(10);
                $user =  User::create($request->toArray());
                $user->assignRole("customer");

                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $data["user"] = $user;
                $data["permissions"]  = $user->getAllPermissions()->pluck('name');
                $data["roles"] = $user->roles->pluck('name');
                $insertableData["is_default"] = 1;
                $insertableData["user_id"] = $user->id;
                Address::create($insertableData);
            }


            if ($coupon) {
                $request["coupon_id"] = $coupon->id;
            }


            $order = Order::create($request->toArray());
            $sub_total = 0;
            $error_message = "";
            foreach ($request['cart'] as $cart) {

                $product = Product::where([
                    "id" => $cart["id"]
                ])
                    ->first();

                $cart["order_id"] = $order->id;
                $cart["product_id"] = $cart["id"];


                if ($cart["type"] == "variable" || $cart["type"] == "cabinet") {
                    $cart["price"] = Variation::where([
                        "id" => $cart["selectedWidth"]
                    ])
                        ->first()
                        ->price;
                } else if ($cart["type"] == "panel") {
                    // $product_price = $order_detail->price;

                    $panel =  collect(json_decode(Product::where([
                        "id" => $cart["id"]
                    ])
                        ->first()
                        ->panels, true))
                        ->first(function ($value, $key) use ($cart) {
                            return $value["thickness"] == $cart["selected_panel_thickness"];
                        });

                    if (($panel["len_maximum"] + 0) < ($cart["selected_panel_length"] + 0)) {
                        $error_message = "Maximum Length Allowed " .  $panel["len_maximum"];
                        break;
                    }
                    if (($panel["len_minimum"] + 0) > ($cart["selected_panel_length"] + 0)) {
                        $error_message = "Minimum Length Allowed " .  $panel["len_minimum"];
                        break;
                    }
                    if (($panel["depth_maximum"] + 0) < ($cart["selected_panel_depth"] + 0)) {
                        $error_message = "Maximum Depth Allowed " .  $panel["depth_maximum"];
                        break;
                    }
                    if (($panel["depth_minimum"] + 0) > ($cart["selected_panel_depth"] + 0)) {
                        $error_message = "Minimum Depth Allowed " .  $panel["depth_minimum"];
                        break;
                    }








                    $panel_price = $panel["price"] * $cart["selected_panel_length"] * $cart["selected_panel_depth"];






                    $cart["price"] = (($panel_price > $panel["default_minimum_price"]) ? $panel_price : $panel["default_minimum_price"]);
                } else {
                    $cart["price"]  = Product::where([
                        "id" => $cart["id"]
                    ])
                        ->first()
                        ->variations[0]
                        ->price;
                }
                $sub_total += $cart["price"];












                if ($coupon) {
                    if (!$coupon->category_id) {
                        $cart["coupon_discount_type"] = $coupon->discount_type;
                        $cart["coupon_discount_amount"] = $coupon->discount_amount;
                    } else {

                        if ($product->category_id == $coupon->category_id) {
                            if ($coupon->is_all_category_product) {
                                $cart["coupon_discount_type"] = $coupon->discount_type;
                                $cart["coupon_discount_amount"] = $coupon->discount_amount;
                            } else {
                                $found = false;
                                foreach ($coupon->cproducts as $cproduct) {
                                    if ($cproduct->product_id == $product->id) {
                                        $found = true;
                                    }
                                }
                                if ($found) {
                                    $cart["coupon_discount_type"] = $coupon->discount_type;
                                    $cart["coupon_discount_amount"] = $coupon->discount_amount;
                                }
                            }
                        }
                    }
                }





                $order_details =  OrderDetail::create($cart);

                foreach (json_decode($cart["options"], true) as $option) {
                    if (!empty($option["selectedValue"])) {
                        OrderDetailOption::create([
                            "option_id" => $option["option_id"],
                            "option_value_id" => $option["selectedValue"],
                            "order_detail_id" => $order_details->id
                        ]);
                    }
                }
            }
            if ($error_message) {
                return response()->json([
                    "success" => false,
                    "message" => $error_message
                ], 422);
            }
            /* return response()->json([
                "success" => false,
                "message" => $error_message
            ], 422);*/

            $shipping = $this->calculateShippingUtil(
                $sub_total,
                $request->rate_name,
                $request->country_id,
                $request->state_id
            );

            $order->shipping =  $shipping["price"];
            $order->tax =  (($shipping["price"] + $sub_total) * 20) / 100;
            $order->shipping_name =  $shipping["shipping_name"];
            $order->save();
            ///email sending : i am doint this cause i need the order id.

            $mail = $order->email;
            Mail::to($mail)->send(new orderconfirmationmail($order->id));

            //echo json_encode(["type" => "success", "message" => "Your mail send successfully from post method to this $mail"]);
            //end email sending;
            return response()->json([
                "success" => true,
                "order" => $order
            ]);
        });
    }
    public function create2(OrderRequest2 $request)
    {
        return DB::transaction(function () use (&$request) {

            $coupon = null;


            if (!empty($request["order_coupon"]["id"])) {
                $coupon =  Coupon::where([
                    "id" => $request["order_coupon"]["id"],
                    "is_active" => true
                ])
                    ->where(
                        "expire_date",
                        ">",
                        today()
                    )
                    ->first();
            }

            $customer = Customer::where([
                "email" => $request['email']
            ])
                ->first();

            if ($customer) {
                $customer->total_order += 1;
                $customer->fname = $request["fname"];
                $customer->lname = $request["lname"];
                $customer->cname = $request["cname"];
                $customer->phone = $request["phone"];
                $customer->billing_address = $request["billing_address"];
                $customer->billing_address2 = $request["billing_address2"];
                $customer->city = $request["city"];
                $customer->zipcode = $request["zipcode"];
                if ($request['create_account'] == 1) {
                    $customer->type = "registered customer";
                }

                $customer->save();
            } else {
                $customer = Customer::create(
                    [
                        "fname" =>  $request["fname"],
                        "lname" =>  $request["lname"],
                        "cname" =>  $request["cname"],
                        "phone" =>  $request["phone"],
                        "email" =>  $request["email"],
                        "type" =>  $request['create_account'] == 1 ? "registered customer" : "guest",
                        "billing_address" =>  $request["billing_address"],
                        "billing_address2" =>  $request["billing_address2"],
                        "city" =>  $request["city"],
                        "zipcode" =>  $request["zipcode"],
                    ]
                );
            }





            $request["customer_id"] = $customer->id;
            // return response()->json($coupon,500);




            if ($coupon) {
                $request["coupon_id"] = $coupon->id;
            }


            $order = Order::create($request->toArray());

            $sub_total = 0;
            $error_message = "";
            foreach ($request['cart'] as $cart) {

                $product = Product::where([
                    "id" => $cart["id"]
                ])
                    ->first();

                $cart["order_id"] = $order->id;
                $cart["product_id"] = $cart["id"];



                if ($cart["type"] == "variable" || $cart["type"] == "cabinet") {
                    $cart["price"] = Variation::where([
                        "id" => $cart["selectedWidth"]
                    ])
                        ->first()
                        ->price;
                } else if ($cart["type"] == "panel") {

                    $panel =  collect(json_decode(Product::where([
                        "id" => $cart["id"]
                    ])
                        ->first()
                        ->panels, true))
                        ->first(function ($value, $key) use ($cart) {
                            return $value["thickness"] == $cart["selected_panel_thickness"];
                        });
                    if (!is_numeric($cart["selected_panel_length"]) || isEmpty($cart["selected_panel_length"])) {
                        $error_message = "Please Select A Length";
                        break;
                    }
                    if (($panel["len_maximum"] + 0) < ($cart["selected_panel_length"] + 0)) {
                        $error_message = "Maximum Length Allowed " .  $panel["len_maximum"];
                        break;
                    }
                    if (($panel["len_minimum"] + 0) > ($cart["selected_panel_length"] + 0)) {
                        $error_message = "Minimum Length Allowed " .  $panel["len_minimum"];
                        break;
                    }
                    if (!is_numeric($cart["selected_panel_depth"]) || isEmpty($cart["selected_panel_depth"])) {
                        $error_message = "Please Select A Depth";
                        break;
                    }
                    if (($panel["depth_maximum"] + 0) < ($cart["selected_panel_depth"] + 0)) {
                        $error_message = "Maximum Depth Allowed " .  $panel["depth_maximum"];
                        break;
                    }
                    if (($panel["depth_minimum"] + 0) > ($cart["selected_panel_depth"] + 0)) {
                        $error_message = "Minimum Depth Allowed " .  $panel["depth_minimum"];
                        break;
                    }
                    $panel_price = $panel["price"] * $cart["selected_panel_length"] * $cart["selected_panel_depth"];






                    $cart["price"] = (($panel_price > $panel["default_minimum_price"]) ? $panel_price : $panel["default_minimum_price"]);
                } else {
                    $cart["price"]  = Product::where([
                        "id" => $cart["id"]
                    ])
                        ->first()
                        ->variations[0]
                        ->price;
                }


                $sub_total += $cart["price"];




                if ($coupon) {
                    if (!$coupon->category_id) {
                        $cart["coupon_discount_type"] = $coupon->discount_type;
                        $cart["coupon_discount_amount"] = $coupon->discount_amount;
                    } else {

                        if ($product->category_id == $coupon->category_id) {
                            if ($coupon->is_all_category_product) {
                                $cart["coupon_discount_type"] = $coupon->discount_type;
                                $cart["coupon_discount_amount"] = $coupon->discount_amount;
                            } else {
                                $found = false;
                                foreach ($coupon->cproducts as $cproduct) {
                                    if ($cproduct->product_id == $product->id) {
                                        $found = true;
                                    }
                                }
                                if ($found) {
                                    $cart["coupon_discount_type"] = $coupon->discount_type;
                                    $cart["coupon_discount_amount"] = $coupon->discount_amount;
                                }
                            }
                        }
                    }
                }





                $order_details =  OrderDetail::create($cart);

                foreach (json_decode($cart["options"], true) as $option) {
                    if (!empty($option["selectedValue"])) {
                        OrderDetailOption::create([
                            "option_id" => $option["option_id"],
                            "option_value_id" => $option["selectedValue"],
                            "order_detail_id" => $order_details->id
                        ]);
                    }
                }
            }
            if ($error_message) {
                return response()->json([
                    "success" => false,
                    "message" => $error_message
                ], 423);
            }
            /*            return response()->json([
                "success" => false,
                "message" => $error_message
            ], 422);*/
            $shipping = $this->calculateShippingUtil(
                $sub_total,
                $request->shipping_name,
                $request->country_id,
                $request->state_id
            );

            $order->shipping =  $shipping["price"];
            $order->tax =  (($shipping["price"] + $sub_total) * 20) / 100;
            $order->shipping_name =  $shipping["shipping_name"];
            $order->save();
            ///email sending : i am doint this cause i need the order id.

            $mail = $order->email;
            Mail::to($mail)->send(new orderconfirmationmail($order->id));

            // echo json_encode(["type" => "success", "message" => "Your mail send successfully from post method to this $mail"]);
            //end email sending;
            return response()->json([
                "success" => true,
                "order" => $order
            ]);
        });
    }



    public function showOrder($id, Request $request)
    {
        $data["data"] = Order::with("order_details.options", "order_details.product", "order_details.product_variation", "order_details.variation", "order_details.color", "order_details.options.option", "order_details.options.option_value", "coupon", "payment")
            ->where([
                "id" => $id
            ])

            ->first();
        foreach ($data["data"]->order_details as $key => $order_details) {
            $color = NULL;
            if (!empty($order_details->color)) :
                $color = ProductColor::where([
                    "color_id" => $order_details->color->id,
                    "product_id" => $order_details->product->id,
                ])
                    ->first();
            endif;
            if ($color) {
                $data["data"]->order_details[$key]->color_image = $color->color_image;
            }
        }


        return response()->json($data, 200);
    }
    public function showCustomer($id, Request $request)
    {
        $data["data"] = Customer::where([
            "id" => $id
        ])

            ->first();
        $data["data"]["last_order"] =   Order::where([
            "customer_id" => $id
        ])
            ->orderByDesc("id")
            ->first();

        $data["data"]["total_spent"] = 0;

        foreach (Order::where([
            "customer_id" => $id
        ])
            ->get() as $order) {
            foreach ($order->order_details as $order_detail) {
                $data["data"]["total_spent"] +=   $order_detail->price * $order_detail->qty;
            }
        }

        return response()->json($data, 200);
    }

    public function changeStatus($id, Request $request)
    {
        $data["data"] = Order::where([
            "id" => $id
        ])

            ->update([
                "status" => $request->status
            ]);
        return response()->json($data, 200);
    }
    public function cancelOrder($id, Request $request)
    {
        $data["data"] = Order::where([
            "id" => $id,
            "status" => "pending"
        ])

            ->update([
                "status" => "cancel"
            ]);
        return response()->json($data, 200);
    }

    public function getCustomers(Request $request)
    {
        $data["data"] = Customer::paginate(10);
        return response()->json($data, 200);
    }
}
