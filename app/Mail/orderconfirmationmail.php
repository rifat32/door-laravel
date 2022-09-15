<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\Color;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\OrderDetailOption;
use App\Models\ProductColor;

class orderconfirmationmail extends Mailable
{
    private $order_id;
    use Queueable, SerializesModels;

    /**     
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order_id = null)
    {
        $this->order_id = $order_id ?? 32;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /////////////////////////
        $ordervaritiondata = Order::with("order_details.options", "order_details.product", "order_details.product_variation", "order_details.variation", "order_details.color", "order_details.options.option", "order_details.options.option_value", "coupon", "payment")
            ->where([
                "id" => $this->order_id
            ])

            ->first();
        $ordervaritiondata = $ordervaritiondata->toarray();
        /////////////////////////
        $order = Order::where("id", $this->order_id)->first();
        if (empty($order)) {
            die("Invalid Order id");
        }
        $coupon = $order->coupon;
        $couponname = !empty($coupon) ? $coupon->name : null;
        $color = null;

        $i = 0;
        $orderarray = [];
        $coupon_discount_price = 0;
        $optionarray = [];

        foreach ($order->order_details as $order_detail) {
            ///order detail option start

            $orderdetailoption = OrderDetailOption::where("order_detail_id", $order_detail->id)->get();
            foreach ($orderdetailoption as $optionkey => $optionvalue) {
                $options = Option::where("id", $optionvalue["option_id"])->first();
                $optionvaluedb = OptionValue::where("id", $optionvalue["option_value_id"])->where("option_id", $optionvalue["option_id"])->first();
                $optionarray[$optionkey] = ["option_name" => $options->name, "value_name" => $optionvaluedb['name']];
            }
            ///order detail option end


            if ($order_detail->selectedProductColor != null) {
                $colormain = color::where("code", $order_detail->selectedProductColor)->first();
                $colorname = $colormain->name;
                $colorid = $colormain->id;
                $color = ProductColor::where("color_id", $colorid)->where("product_id", $order_detail->product->id)->first()->toArray();
                $color["colorname"] = $colorname;
            }
            if ($order_detail->product->type && $order_detail->product->type == "variable") {
                $product_price = $order_detail->variation->price;
                $type = "variable";
                // echo "if block product price " . $product_price . "<br>";
            } elseif ($order_detail->product->type && $order_detail->product->type == "single") {
                $product_price = $order_detail->product->variations[0]->price;
                // echo "else block product price " . $product_price . "<br>";
                $type = "single";
            } elseif ($order_detail->product->type && $order_detail->product->type == "panel") {
                $product_price = $order_detail->price;
                $type = "panel";
            }
            if (!empty($coupon)) {
                if ($order_detail->coupon_discount_type == "percentage") {
                    $coupon_discount_price += (($order_detail->coupon_discount_amount * $product_price) / 100);
                } else {
                    $coupon_discount_price +=   $order_detail->coupon_discount_amount;
                }
            }

            $orderarray[$i] = ["product_name" => $order_detail->product->name, "product_quantity" => $order_detail->qty, "product_price" => $product_price, "type" => $type, "image" => asset($order_detail->product->image), "color" => $color, "option" => $optionarray, "height" => $ordervaritiondata['order_details'][$i]['product_variation'], "width" => $ordervaritiondata['order_details'][$i]['variation'], "order_details" => [$order_detail->toArray()]];

            $i++;
        }
        $orderarray[0]["coupon"] = ["name" => $couponname, "discount_amount" => $coupon_discount_price];
        $orderarray[0]["customerdetail"] = ["ordre_id" => $order->id, "fname" => $order->fname, "lname" => $order->lname, "email" => $order->email, "address1" => $order->billing_address, "address2" => $order->billing_address2, "city" => $order->city, "zipcode" => $order->zipcode, "country" => null, "state" => null, "orderdate" => $order->created_at->timestamp];
        return $this->subject("Order confirmation")->markdown('emails.orderconfimition')->with("orders", $orderarray);
    }
}
