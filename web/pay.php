<script src="https://js.stripe.com/v3"></script>
<?php
ini_set('display_errors',1);
session_start();
$lang = strtolower($_SESSION['lang']);
$mail = $_SESSION["email"];

require_once('Pages/db.php');
require_once 'stripe/init.php';

$bdd = connectionDB();
$user = $bdd->prepare('SELECT * FROM client WHERE email = :mail');
$user->execute(array(':mail'=>$mail));
$user = $user->fetch();

if ($user['stripeID'] == null){
    \Stripe\Stripe::setApiKey('sk_test_WDZW3sWkIUI5asuWjU1FOR7Z00kDVsxULV');
    $intent = \Stripe\Customer::create([
        'email' =>  $_SESSION['email'],
        'address' => ["line1" => $_SESSION["address"]],
        'phone' => $user['phoneNumber'],
        'name' => $user["lastName"].' '.$user["firstName"],
    ]);

    $insert = $bdd->prepare('UPDATE client SET stripeID = ? WHERE email = ?');
    $insert->execute([$intent->id,$_SESSION['email']]);
    $_SESSION['stripeID'] = $intent->id;
    $user = $bdd->prepare('SELECT * FROM client WHERE email = :mail');
    $user->execute(array(':mail'=>$mail));
    $user = $user->fetch();
}

if($_GET['sub'] != null ) {
    $subID = $bdd->prepare('SELECT * FROM subscription WHERE subscriptionID = :id GROUP BY subscriptionID');
    $subID->execute(array(':id'=>$_GET['sub']));
    $subID = $subID->fetch();

    \Stripe\Stripe::setApiKey('sk_test_WDZW3sWkIUI5asuWjU1FOR7Z00kDVsxULV');

    $session = \Stripe\Checkout\Session::create([
        'customer' => $user["stripeID"],
        'payment_method_types' => ['card'],
        'subscription_data' => [
            'items' => [[
                'plan' => $subID["stripeID"],
            ]],
        ],
        'success_url' => 'http://localhost/Conciergerie/web/retrieve?sub='.$_GET['sub'],
        'cancel_url' => 'http://localhost/Conciergerie/web/subscription?error=canceled',
    ]);

    if ($session->id != null){
        echo "
    <script type=\"text/javascript\">
        (function() {
            var stripe = Stripe('pk_test_U2iCSSR4bBx2jS0pYX8tG5Of00Uy4HuV8w');
            stripe.redirectToCheckout({
                    sessionId : '$session->id'
                })
                    .then(function (result) {
                if (result.error) {
                    var displayError = document.getElementById('error-message');
                    displayError.textContent = result.error.message;
                }
            });
        })();
    </script>";
    }
}
?>
