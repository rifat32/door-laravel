<?php

use App\Mail\orderconfirmationmail;
use App\Mail\orderdeliveredmail;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SetUpController;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stripe\StripeClient;
use App\Http\Controllers\OrderPayments;

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
    $stripe = new StripeClient(
        'sk_test_51JUtILKKsz00TiWASOtYqpG6WOTlBKLh8bp5dfQUsnuT8PPQu1Nu1FzqzEvlbbrHvas62E3xS4GBJpfcxYVn4eJc00MJ1hMQa6'
    );
    if ($request->type === "charge.succeeded") {
        $payementintent = $request->data["object"]["payment_intent"];
        $checkout = $stripe->checkout->sessions->all(["payment_intent" => $payementintent]);

        /* echo $checkout; */
        foreach ($checkout as $char) {
            $order_id = $char->metadata->order_id . "\n";
            $status = $char->payment_status;
        }
        $status = $status ?? "null";

        try {
            OrderPayment::create([
                "payement_id" => $request->data["object"]["id"],
                "amount" => $request->data["object"]["amount"] / 100,
                "payer_email" => $request->data["object"]["billing_details"]["email"],
                "currency" => $request->data["object"]["currency"],
                "order_id" => $order_id,
                "receipt_url" => $request->data["object"]["receipt_url"],
                "payment_intent" => $request->data["object"]["payment_intent"],
                "status" =>  /* $request->data["object"]["paid"] */ $status,
            ]);
        } catch (\Exception $e) {
            $e->getMessage();
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
    return new orderdeliveredmail($order_id);
});

//Routes for order confirmation mail
Route::get("/orderconfirmition", function (Request $request) {
    $id = $request['id'];
    /* $data = $request;
    $mail = $data['email'] ?? "test@test.com";
    Mail::to($mail)->send(new orderconfirmationmail($id));
    return json_encode(["type" => "success", "message" => "Your mail send successfully from post method to this $mail"]); */
    return new orderconfirmationmail($id);
});

//Routes for  for Welcome Mail
Route::get("/email", function (Request $request) {
    /*     $data = $request->json()->all();
    $email = $data["email"] ?? "test@test.com";
    $mail = Mail::to($email)->send(new WelcomeMail());
    return json_encode(["type" => "success", "message" => "Your mail send successfully from post method to this $email"]); */
    return new WelcomeMail();
});
