<?php

$_SESSION['test'] = 'This is the Test Session';
$productname = $price = $quantity = '';
$succespage = 'http://127.0.0.1:8000/checkout';
$cancelpage = 'http://127.0.0.1:8000/checkout';
/*if (isset($_GET['submit'])) {
    $productname = $_GET['productname'];
    $price = $_GET['price'];
    $quantity = $_GET['quantity'];
    $_SESSION['orderid'] = $_GET['orderid'];
    */
    /*   echo $_SESSION["orderid"]; */
   /* $stripe = new \Stripe\StripeClient('sk_test_51Kdy9JE00LZ83RrlSJYCuLu7imUQTeGTUbTgxfAx1lpsVhiPcxcYcegCSGyUW9UY0PdzukNxesWQyCTbK9EFHOWk000bHfgH9O');*/

    /* \Stripe\Stripe::setApiKey("sk_test_51Kdy9JE00LZ83RrlSJYCuLu7imUQTeGTUbTgxfAx1lpsVhiPcxcYcegCSGyUW9UY0PdzukNxesWQyCTbK9EFHOWk000bHfgH9O"); */

    /*    $coupon = $stripe->coupons->create([
        "amount_off" => 10 * 100,
        "name" => "10€ off coupon",
        "currency" => "eur",
    ]); */
    /* echo $coupon->id; */
    /*     echo $stripe->coupons->retrieve("4ZxfCouo"); */

    /*$session = $stripe->checkout->sessions->create([
        'line_items' => [
            [
                'price_data' => [
                    'currency' => 'eur',

                    'product_data' => [
                        'name' => $productname,
                        'description' => 'this is the ashford door',
                    ],
                    'unit_amount' => $price * 100,
                ],
                'quantity' => $quantity,
            ],
        ],
        'discounts' => [
            [
                'coupon' => '4ZxfCouo',
            ],
        ],
        'shipping_options' => [['shipping_rate_data' => ['display_name' => 'shipping name', 'type' => 'fixed_amount', 'fixed_amount' => ['amount' => 5 * 100, 'currency' => 'eur']]]],
        'mode' => 'payment',
        'success_url' => $succespage,
        'cancel_url' => $cancelpage,
        'metadata' => ['order_id' => 1],
        'client_reference_id' => 1234,
        'payment_method_types' => ['card', 'sofort', 'sepa_debit', 'p24', 'klarna', 'ideal', 'giropay', 'eps', 'bancontact'],
    ]);*/
    /*  echo "<pre>";
    print_r($session);
    echo "</pre>";
    */
   /* $url = $session['url'];*/
    /*   echo $url; */

    /*     echo "<script>window.location.href='$url'</script>"; */
#}

$stripe = new \Stripe\StripeClient('sk_test_51Kdy9JE00LZ83RrlSJYCuLu7imUQTeGTUbTgxfAx1lpsVhiPcxcYcegCSGyUW9UY0PdzukNxesWQyCTbK9EFHOWk000bHfgH9O');
$session = $stripe->checkout->sessions->create([
    'line_items' => [
        [
            'price_data' => [
                'currency' => 'eur',

                'product_data' => [
                    'name' => "Ashford Door",
                    'description' => 'this is the ashford door',
                ],
                'unit_amount' => 50 * 100,
            ],
            'quantity' => 1,
        ],
    ],
    'discounts' => [
        [
            'coupon' => '4ZxfCouo',
        ],
    ],
    'shipping_options' => [['shipping_rate_data' => ['display_name' => 'shipping name', 'type' => 'fixed_amount', 'fixed_amount' => ['amount' => 5 * 100, 'currency' => 'eur']]]],
    'mode' => 'payment',
    'success_url' => $succespage,
    'cancel_url' => $cancelpage,
    'metadata' => ['order_id' => 2],
    'client_reference_id' => 1234,
    'payment_method_types' => ['card', 'sofort', 'sepa_debit', 'p24', 'klarna', 'ideal', 'giropay', 'eps', 'bancontact'],
]);

$url = $session['url'];
sleep(1);
echo "<script>window.location.href='$url'</script>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <title>Checkout</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<button id="checkout-button" class="btn btn-primary btn-lg" type="button">Proceed to Checkout</button>

<body>
    <form>
        <br>
        <input type="text" name="productname" placeholder="Prodcut name"><br><br>
        <input type="text" name="price" placeholder="Enter the price"><br><br>
        <input type="number" name="quantity" placeholder="quantity"><br><br>
        <input type="hidden" name="orderid" value="<?php echo mt_rand(0, 100); ?>">
        <input type="submit" name="submit">

    </form>
</body>
<script>
    const stripe = Stripe(
        'pk_test_51Kdy9JE00LZ83RrliVhubgDL8Xo5QCAFAOUVNRMUvDjBcWjM6KGcfZzFLVjHK8PTlR0vXUiFBRKuft89GkazPCVb00pfF2yZeV'
        ) //Your Publishable key.
    const btn = document.getElementById('checkout-button');
    btn.addEventListener("click", function(e) {
        e.preventDefault();
        stripe.redirectToCheckout({
            sessionId: "<?php echo $session->id ?? null; ?> "
        })
    })
</script>

</html>
