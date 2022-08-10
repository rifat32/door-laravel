<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class orderconfirmationmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = Order::where("id", 35)->first();
        $i = 0;
        $orderarray = [];
        foreach ($order->order_details as $order_detail) {
            if ($order_detail->product->type && $order_detail->product->type == "variable") {
                $product_price = $order_detail->variation->price;
                $type = "variable";
                // echo "if block product price " . $product_price . "<br>";
            } else {
                $product_price = $order_detail->product->variations[0]->price;
                // echo "else block product price " . $product_price . "<br>";
                $type = "single";
            }
            $orderarray[$i] = ["product_name" => $order_detail->product->name, "product_quantity" => $order_detail->qty, "product_price" => $product_price, "type" => $type, "image" => asset($order_detail->product->image), "order_details" => [$order_detail->toArray()]];
            $i++;
        }
        return $this->subject("Order confirmation")->markdown('emails.orderconfimition')->with("orders", $orderarray);
    }
}
