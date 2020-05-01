<?php
require_once 'stripe/init.php';
require_once 'Pages/db.php';
session_start();
require_once('lang/'.$_SESSION['lang'].'.php');
echo ini_set('display_errors',1);
$bdd = connectionDB();

if (!empty($_SESSION['stripeID']) && !empty($_SESSION['agencyClient']) && !empty($_POST['sub'])){

    \Stripe\Stripe::setApiKey('sk_test_WDZW3sWkIUI5asuWjU1FOR7Z00kDVsxULV');

    $customer = \Stripe\Customer::retrieve($_SESSION["stripeID"]);
    $sub = $bdd->query('SELECT * FROM subscription GROUP BY subscriptionID');
    $rows =  $sub->rowCount();
    $data = $sub->fetchAll();

    foreach ($customer["subscriptions"]["data"] as $d) {
        for ($j = 0; $j < $rows; $j ++) {
            if ($d["items"]["data"][0]["plan"]["id"] == $data[$j]["stripeID"] && $d["items"]["data"][0]["plan"]["active"] == true) {

                $id = $bdd->prepare('SELECT * FROM subscribes WHERE subscriptionID = :id AND clientID = :client AND agency = :agency');
                $id->execute(array(':id'=>$_POST['sub'],':client'=>$_SESSION['id'],':agency'=>$_SESSION['agencyClient']));
                $id = $id->fetch();

                $datetime1 = new DateTime(date("Y-m-d",(strtotime("now".'+ 1 MONTH'))));
                $datetime2 = new DateTime($id["endDate"]);
                $interval = $datetime1->diff($datetime2);
                $nbMonth = $interval->format('%m');

                $new_date =  date("Y-m-d",strtotime(date("Y-m",(strtotime("now".'+ 1 MONTH'))))); // on laisse l'abo actif pour le mois courant

                $amount = ($data[$j]["pricePerYear"] / 12) * $nbMonth;

                $amount = round($amount,0);
                if($amount < 1) {
                    echo _REFUNDKO;
                    exit();
                }

                $refund = \Stripe\Refund::create([
                    'payment_intent' => $id['paymentStripeID'],
                    'amount' => $amount*100
                ]);

                $request = $bdd->prepare('UPDATE subscribes SET endDate = :newdate WHERE subscriptionID = :id AND clientID = :client AND agency = :agency');
                $request->execute([
                    'newdate' =>$new_date,
                    'id' => $_POST['sub'],
                    'client' => $_SESSION['id'],
                    'agency' => $_SESSION['agencyClient']
                ]);
                echo "OK "._REFUNDOK;
                exit();
            }
        }
    }
}
echo _REFUNDKO;
?>