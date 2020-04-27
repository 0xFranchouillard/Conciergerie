<?php
require_once('Pages/db.php');
require_once 'stripe/init.php';
session_start();
echo $_SESSION['session'];

$mail = $_SESSION["email"];
header('content-type:application/json');
$bdd = connectionDB();
$user = $bdd->prepare('SELECT * FROM client WHERE email = :mail');
$user->execute(array(':mail'=>$mail));
$user = $user->fetch();

\Stripe\Stripe::setApiKey('sk_test_WDZW3sWkIUI5asuWjU1FOR7Z00kDVsxULV');

$customer = \Stripe\Customer::retrieve($user["stripeID"]);

$sub = $bdd->query('SELECT * FROM subscription GROUP BY subscriptionID');
$rows =  $sub->rowCount();
$data = $sub->fetchAll();

if ($customer["subscriptions"]["data"][sizeof($customer["subscriptions"]["data"])-1]["items"]["data"][0]["plan"]["id"] && $customer["subscriptions"]["data"][sizeof($customer["subscriptions"]["data"])-1]["items"]["data"][0]["plan"]["active"] == true) {
    echo $customer["subscriptions"]["data"][sizeof($customer["subscriptions"]["data"])-1]["items"]["data"][0]["plan"]["nickname"] . " VALIDE";
    //ajouter l'abonnement payé dans subscribes
    $invoice = \Stripe\Invoice::retrieve(
        $d["latest_invoice"]
    );

    echo $invoice->payment_intent;
    //ajouter l'abonnement payé dans subscribes + $invoice->payment_intent

}


?>