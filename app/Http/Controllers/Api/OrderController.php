<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderDetailOption;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrderController extends Controller
{
  public function create  (OrderRequest $request){
    DB::transaction(function () use(&$request) {
        $coupon =  Coupon::where([
            "id" => $request["order_coupon"]["id"],
            "is_active" => true
          ])
          ->where(
            "expire_date", ">", today()
          )
          ->first();

            // return response()->json($coupon,500);


            if($request['create_account'] == 1) {
                $request['name'] =   $request['fname'];
                $request['password'] = Hash::make($request['password']);
                $request['remember_token'] = Str::random(10);
                $user =  User::create($request->toArray());
                $user->assignRole("customer");

                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $data["user"] = $user;
                $data["permissions"]  = $user->getAllPermissions()->pluck('name');
                $data["roles"] = $user->roles->pluck('name');

            }


            if($coupon) {
        $request["coupon_id"] = $coupon->id;
              }


               $order = Order::create($request->toArray());
                foreach($request['cart'] as $cart) {

                    $product = Product::where([
                        "id" => $cart["id"]
                    ])
                    ->first();

                    $cart["order_id"] = $order->id;
                    $cart["product_id"] = $cart["id"];


                    if($coupon) {
                      if(!$coupon->category_id) {
                        $cart["coupon_discount_type"] = $coupon->discount_type;
                        $cart["coupon_discount_amount"] = $coupon->discount_amount;
                      } else {

                            if($product->category_id == $coupon->category_id){
                                if($coupon->is_all_category_product) {
                                    $cart["coupon_discount_type"] = $coupon->discount_type;
                                    $cart["coupon_discount_amount"] = $coupon->discount_amount;
                                } else {
                                    $found = false;
                                   foreach($coupon->cproducts as $cproduct) {
                                     if($cproduct->product_id == $product->id) {
                                        $found = true;
                                      }
                                   }
                                   if($found){
                                    $cart["coupon_discount_type"] = $coupon->discount_type;
                                    $cart["coupon_discount_amount"] = $coupon->discount_amount;
                                   }
                                }
                            }



                      }

                    }





               $order_details =  OrderDetail::create($cart);

        foreach($cart["options"] as $option) {
            if(!empty($option["selectedValue"])) {
                OrderDetailOption::create([
                    "option_id" => $option["option_id"],
                    "option_value_id" => $option["selectedValue"],
                    "order_detail_id" => $order_details->id
                ]);
            }


        }


                }

    });


        return response()->json([
            "success" => true
        ]);
 }




 public function showOrder($id,Request $request) {
    $data["data"] = Order::
    with("order_details.options","order_details.product","order_details.product_variation", "order_details.variation","order_details.color", "order_details.options.option","order_details.options.option_value","coupon"  )
    ->where([
        "id" => $id
    ])

    ->first()
    ;
    return response()->json($data,200);

 }
 public function changeStatus($id,Request $request) {
    $data["data"] = Order::where([
        "id" => $id
    ])

    ->update([
        "status" => $request->status
    ])
    ;
    return response()->json($data,200);

 }











}

