<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class orderdeliveredmail extends Mailable
{
    private $orderid;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderid = null)
    {
        //
        $this->orderid = $orderid ?? 32;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = Order::where("id", $this->orderid)->first();
        $orderarray = [];
        $orderarray["customerdetail"] = ["ordre_id" => $order->id, "fname" => $order->fname, "lname" => $order->lname, "email" => $order->email, "address1" => $order->billing_address, "address2" => $order->billing_address2, "city" => $order->city, "zipcode" => $order->zipcode, "country" => null, "state" => null, "orderdate" => $order->created_at->timestamp];
        return $this->markdown('emails.orderdelivered')->with("orders", $orderarray);;
    }
}
