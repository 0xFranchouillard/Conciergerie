<?php
session_start();
require_once('../lang/'.$_SESSION['lang'].'.php');
require_once('../Pages/db.php');
$db = connectionDB();
$requestSubscription = $db->prepare('SELECT value FROM Subscription WHERE subscriptionID= :subscriptionID');
$requestVerifSubscribes = $db->prepare('SELECT * FROM Subscribes WHERE clientID= :clientID && agency= :agency && subscriptionID= :subscriptionID');
$requestSubscribes = $db->prepare('INSERT INTO Subscribes(subscriptionDate, endDate, recurrence, month, valueMonth, clientID, agency, subscriptionID) VALUES(:subscriptionDate, :endDate, :recurrence, :month, :valueMonth, :clientID, :agency, :subscriptionID)');

if(isset($_POST['subscriptionID']) && $_POST['subscriptionID'] != null) {
    $requestVerifSubscribes->execute([
       'clientID'=>$_SESSION['id'],
       'agency'=>$_SESSION['agencyClient'],
       'subscriptionID'=>$_POST['subscriptionID']
    ]);
    if($requestVerifSubscribes->rowCount() != 0) {
        echo _ALREADYSUBSCRIPTION;
    } else {
        $requestSubscription->execute([
           'subscriptionID'=>$_POST['subscriptionID']
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
            'subscriptionID'=>$_POST['subscriptionID']
        ]);
        echo "OK "._SUBSCRIPTIONBUY;
    }
}
?>