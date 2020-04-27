<?php
require_once 'stripe/init.php';
require_once 'Pages/db.php';

$bdd = connectionDB();

if (!empty($_SESSION['stripeID']) && !empty($_SESSION['agencyClient']) && !empty($_GET['sub'])){

    \Stripe\Stripe::setApiKey('sk_test_WDZW3sWkIUI5asuWjU1FOR7Z00kDVsxULV');

    $customer = \Stripe\Customer::retrieve($user["stripeID"]);

    $sub = $bdd->query('SELECT * FROM subscription GROUP BY subscriptionID');
    $rows =  $sub->rowCount();
    $data = $sub->fetchAll();

    foreach ($customer["subscriptions"]["data"] as $d) {
        for ($j = 0; $j < $rows; $j ++) {
            if ($d["items"]["data"][0]["plan"]["id"] == $data[$j]["stripeID"] && $d["items"]["data"][0]["plan"]["active"] == true) {

                $id = $bdd->prepare('SELECT * FROM subscribes WHERE subscriptionID = :id AND clientID = :client AND agency = :agency');
                $id->execute(array(':id'=>$_GET['sub'],':client'=>$_SESSION['id'],$_SESSION['agencyClient']));
                $id = $id->fetch();

                $datetime1 = new DateTime(date("Y-m-d"));
                $datetime2 = new DateTime($id["endDate"]);
                $interval = $datetime1->diff($datetime2);
                $nbMonth = $interval->format('%m');

                $new_date =  date('Y-m-d',(strtotime(date('Y-m',(strtotime($datetime1.' + 1 MONTH')))))); // on laisse l'abo actif pour le mois courant


                $amount = ($data[$j]["value"] - ($data[$j]["value"] / 12)) * $nbMonth * 100;

                \Stripe\Refund::create([
                    'payment_intent' => $id['paymentStripeID'],
                    'amount' => $amount
                ]);

                $request = $db->prepare('UPDATE subscribes SET endDate = :newdate WHERE subscriptionID = :id AND clientID = :client AND agency = :agency');
                $request->execute([
                    'newdate' =>$new_date,
                    'id' => $data[$j]["subscriptionID"],
                    'client' => $_SESSION['id'],
                    'agency' => $_POST['lastName'],
                    'firstName' => $_POST['agencyClient']
                ]);
            }
        }
    }





}
?>