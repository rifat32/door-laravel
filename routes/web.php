<?php

use App\Mail\orderconfirmationmail;
use App\Mail\orderdeliveredmail;
use App\Mail\WelcomeMail;
use App\Mail\paymentconfirmationmail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SetUpController;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stripe\StripeClient;
use App\Http\Controllers\OrderPayments;
use Stripe\Webhook;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/setup', [SetUpController::class, "setUp"]);


Route::post('webhook', function (Request $request) {
    $stripeprivatekey = env('STRIPE_PRIVATE_KEY', '');
    $stripe = new StripeClient(
        $stripeprivatekey
    );
    if ($request->type === "charge.succeeded") {
        $paymentintent = $request->data["object"]["payment_intent"];
        /*         $checkout = $stripe->checkout->sessions->all(["payment_intent" => $paymentintent]); */
        $_payer_email = $request->data["object"]["billing_details"]["email"];
        $_receipt_url = $request->data["object"]["receipt_url"];

        try {

            OrderPayment::where("payment_intent", $paymentintent)->update(["payer_email" => $_payer_email, "receipt_url" => $_receipt_url]);
            echo "Charge Succeeded Run successfully";
        } catch (\Exception $e) {
            report($e);
            error_log($e->getMessage());
        }
    }
    if ($request->type === "checkout.session.completed") {
        $paymentintent = $request->data["object"]["payment_intent"];
        /*         $checkout = $stripe->checkout->sessions->all(["payment_intent" => $paymentintent]); */
        $_status = $request->data["object"]["payment_status"];

        try {

            OrderPayment::where("payment_intent", $paymentintent)->update(["status" => $_status]);
            echo "Checkout session Run successfully";
        } catch (\Exception $e) {
            report($e);
            error_log($e->getMessage());
        }
    }
    if ($request->type === "payment_intent.canceled") {
        $_paymentinent = $request->data["object"]["id"];
        $_status = $request->data["object"]["status"];

        try {
            OrderPayment::where("payment_intent", $_paymentinent)->update(["status" => $_status]);
            echo "Payment Intent Canceled Run Successfully";
        } catch (\Exception $e) {
            report($e);
            error_log($e->getMessage());
        }
    }
});
/* Route::get('/checkout', function () {
    return view("checkout");
}); */
Route::get('/checkout', function (Request $request) {
    return view("checkout", [
        "order_id" => $request->order_id
    ]);
});
Route::get("/payment", [OrderPayments::class, "stripepayments"]);
Route::get("/paypalpayment", [OrderPayments::class, "paypalpayment"]);
Route::get("/payaplsuccess", [OrderPayments::class, "payaplsuccess"]);
Route::get("/paypalcancel", [OrderPayments::class, "paypalcancel"]);
//route for order delivery mail
Route::get("/orderdeleveredmail", function (Request $request) {
    $order_id = $request["order_id"];
    $mail = $data['email'] ?? "sami.maxzionit@gmail.com";
    Mail::to($mail)->send(new orderdeliveredmail($order_id));
    return new orderdeliveredmail($order_id);
});

//Routes for order confirmation mail
Route::get("/orderconfirmition", function (Request $request) {
    $id = $request['order_id'];
    /*  $data = $request;
    $mail = $data['email'] ?? "sami.maxzionit@gmail.com";
    Mail::to($mail)->send(new orderconfirmationmail($id)); */
    /* return json_encode(["type" => "success", "message" => "Your mail send successfully from post method to this $mail"]); */
    return new orderconfirmationmail($id);
});

//Routes for  for Welcome Mail
Route::get("/email", function (Request $request) {
    $data = $request->json()->all();
    $email = $data["email"] ?? "sami.maxzionit@gmail.com";
    $mail = Mail::to($email)->send(new WelcomeMail());
    /*  return json_encode(["type" => "success", "message" => "Your mail send successfully from post method to this $email"]); */
    return new WelcomeMail();
});
//Routes for  for Welcome Mail
Route::get("/paymentmail", function (Request $request) {
    $data = $request->json()->all();
    $email = $data["email"] ?? "sami.maxzionit@gmail.com";
    $order_id = $data["order_id"] ?? 10;
    $mail = Mail::to($email)->send(new paymentconfirmationmail($order_id));
    /*  return json_encode(["type" => "success", "message" => "Your mail send successfully from post method to this $email"]); */
    return new paymentconfirmationmail($order_id);
});
Route::get("/striperetrive", function () {
    $stripeprivatekey = env('STRIPE_PRIVATE_KEY', '');

    $stripe = new StripeClient($stripeprivatekey);
    $stripe->checkout->sessions->expire("cs_test_b1kbgueoxe2wDjWwro6IVB90lDGtREe0drQDVtEOx5pMIWF6zXKtdSpKOh");
    /*  $checkoutsesssion = $stripe->checkout->sessions->retrieve("cs_test_a1lmrscl25mjuPyYDwhK1a7lly2IJcRXlaDZAK12Wq1Rr4KWgC08mEc8T0");
    dd($checkoutsesssion->toArray()); */
});
