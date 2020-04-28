<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification du paiement...</title>
    <style type="text/css">
        body{
            margin: 0;
            padding: 0;
        }
        #content{
            display: flex;
            position: absolute;
            height: 100%;
            width: 100%;
        }
        #content img{
            margin: auto;
            width: 250px;
        }
    </style>
</head>
<body>
<div id="content">
    <img src="Pictures/loader.gif" alt="Loader">
</div>
</body>
</html>

<?php
require_once('Pages/db.php');
require_once 'stripe/init.php';
session_start();
require_once('lang/'.$_SESSION['lang'].'.php');

if ($_GET['sub'] == null){
    exit();
}

$mail = $_SESSION["email"];
$bdd = connectionDB();
$user = $bdd->prepare('SELECT * FROM client WHERE email = :mail');
$user->execute(array(':mail'=>$mail));
$user = $user->fetch();

\Stripe\Stripe::setApiKey('sk_test_WDZW3sWkIUI5asuWjU1FOR7Z00kDVsxULV');

$customer = \Stripe\Customer::retrieve($user["stripeID"]);

$sub = $bdd->prepare('SELECT * FROM subscription WHERE subscriptionID = ? GROUP BY subscriptionID');
$rows =  $sub->execute(array($_GET['sub']));
$data = $sub->fetch();

    if ($customer["subscriptions"]["data"][0]["items"]["data"][0]["plan"]["id"] == $data["stripeID"] && $customer["subscriptions"]["data"][0]["items"]["data"][0]["plan"]["active"] == true && $customer["subscriptions"]["data"][0]["items"]["data"][0]["plan"]["created"] < strtotime('+5 minutes')) {
        //echo $customer["subscriptions"]["data"][0]["items"]["data"][0]["plan"]["nickname"] . " VALIDE";

        $invoice = \Stripe\Invoice::retrieve(
            $customer["subscriptions"]["data"][0]["latest_invoice"]
        );

        $requestSubscription = $bdd->prepare('SELECT value FROM Subscription WHERE subscriptionID= :subscriptionID');
        $requestVerifSubscribes = $bdd->prepare('SELECT * FROM Subscribes WHERE clientID= :clientID && agency= :agency && subscriptionID= :subscriptionID');
        $requestSubscribes = $bdd->prepare('INSERT INTO Subscribes(subscriptionDate, endDate, recurrence, month, valueMonth, clientID, agency, subscriptionID, paymentStripeID) VALUES(:subscriptionDate, :endDate, :recurrence, :month, :valueMonth, :clientID, :agency, :subscriptionID, :paymentStripeID)');

            $requestVerifSubscribes->execute([
                'clientID'=>$_SESSION['id'],
                'agency'=>$_SESSION['agencyClient'],
                'subscriptionID'=>$data['subscriptionID']
            ]);
            if($requestVerifSubscribes->rowCount() != 0) {
                $txt = _ALREADYSUBSCRIPTION;
            } else {
                $requestSubscription->execute([
                    'subscriptionID'=>$data['subscriptionID']
                ]);
                $resultSubscription = $requestSubscription->fetch();
                $requestSubscribes->execute([
                    'subscriptionDate'=>date('Y-m-d'),
                    ':endDate'=>date("Y-m-d", strtotime("+1 year")),
                    'recurrence'=>0,
                    'month'=>date('m'),
                    'valueMonth'=>$resultSubscription['value'],
                    'clientID'=>$_SESSION['id'],
                    'agency'=>$_SESSION['agencyClient'],
                    'subscriptionID'=>$data['subscriptionID'],
                    'paymentStripeID'=>$invoice->payment_intent
                ]);
                $txt = "OK "._SUBSCRIPTIONBUY;
            }
        echo "<script>window.location='subscription'</script>";
    }
    echo "<script>window.location='subscription?error=nopayment'</script>";
?>