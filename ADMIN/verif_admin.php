<?php

use Stripe\Plan;
use Stripe\Stripe;

ini_set('display_errors',1);
include('config.php');
$bdd = connectionDB();

if(isset($_POST['nom_']) && !empty($_POST['nom_']) &&
    isset($_POST['agency']) && !empty($_POST['agency']) &&
    isset($_GET['clientID']) || isset($_GET['providerID'])) {

    if (!empty($_GET['clientID'])) {
        $update_nom = $bdd->prepare('UPDATE client SET lastName = ? WHERE clientID = ? AND agency = ? ');
        $id = $_GET['clientID'];
    }else{
        $update_nom = $bdd->prepare('UPDATE serviceprovider SET lastName = ? WHERE providerID = ? AND agency = ? ');
        $id = $_GET['providerID'];
    }

    $update_nom->execute(array(
        $_POST['nom_'],
        $id,
        $_POST['agency']
    ));
}

if(isset($_POST['newagency']) && !empty($_POST['newagency']) &&
    isset($_POST['agency']) && !empty($_POST['agency']) &&
    isset($_GET['clientID']) || isset($_GET['providerID'])) {

    if (!empty($_GET['clientID'])) {
        $update_nom = $bdd->prepare('UPDATE intervention,client 
SET intervention.agency = ?, client.agency = ?
WHERE intervention.clientID = ? AND intervention.agency = ?
');
        $id = $_GET['providerID'];
        $update_nom->execute([$_POST['newagency'],$_POST['newagency'],$id,$_POST['agency']]);

    }else{

        $update_nom = $bdd->prepare('UPDATE serviceprovider SET agency = ? WHERE providerID = ? AND agency = ? ');
        $id = $_GET['providerID'];
    }

    $update_nom->execute(array(
        $_POST['newagency'],
        $id,
        $_POST['agency']
    ));
}

if(isset($_POST['prenom_']) && !empty($_POST['prenom_']) &&
    isset($_POST['agency']) && !empty($_POST['agency']) &&
    isset($_GET['clientID']) || isset($_GET['providerID'])) {

    if (!empty($_GET['clientID'])) {
        $update_prenom = $bdd->prepare('UPDATE client SET firstName = ? WHERE clientID = ? AND agency = ? ');
        $id = $_GET['clientID'];
    }else{
        $update_prenom = $bdd->prepare('UPDATE serviceprovider SET firstName = ? WHERE providerID = ? AND agency = ?');
        $id = $_GET['providerID'];
    }

    $update_prenom->execute(array(
        $_POST['prenom_'],
        $id,
        $_POST['agency']
    ));
}

if(isset($_POST['priceTypeService']) && !empty($_POST['priceTypeService']) &&
    isset($_GET['id']) && !empty($_GET['id'])) {

    if($_POST['priceTypeService']=="Facturation à la tâche") $priceTypeEn = "Billing by task"; elseif ($_POST['priceTypeService']=="Facturation à l'heure") $priceTypeEn = "Billing by the hour"; else $priceTypeEn = "Mixed billing";

    $update_priceTypeService = $bdd->prepare('UPDATE service SET priceTypeService = ? WHERE serviceID = ? AND language = ?');

    $update_priceTypeService->execute(array(
        $_POST['priceTypeService'],
        $_GET['id'],
        "Fr"
    ));
    $update_priceTypeService->execute(array(
        $priceTypeEn,
        $_GET['id'],
        "En"
    ));
}
if(isset($_POST['nameServiceFr']) && !empty($_POST['nameServiceFr']) &&
    isset($_GET['id']) && !empty($_GET['id'])) {

    $update_nameService = $bdd->prepare('UPDATE service SET nameService = ? WHERE serviceID = ? AND language = ?');

    $update_nameService->execute(array(
        $_POST['nameServiceFr'],
        $_GET['id'],
        "Fr"
    ));
}
if(isset($_POST['nameServiceEn']) && !empty($_POST['nameServiceEn']) &&
    isset($_GET['id']) && !empty($_GET['id'])) {

    $update_nameService = $bdd->prepare('UPDATE service SET nameService = ? WHERE serviceID = ? AND language = ?');

    $update_nameService->execute(array(
        $_POST['nameServiceEn'],
        $_GET['id'],
        "En"
    ));
}
if(isset($_POST['descriptionEn']) && !empty($_POST['descriptionEn']) &&
    isset($_GET['id']) && !empty($_GET['id'])) {

    $update_nameService = $bdd->prepare('UPDATE service SET description = ? WHERE serviceID = ? AND language = ?');

    $update_nameService->execute(array(
        $_POST['descriptionEn'],
        $_GET['id'],
        "En"
    ));
}
if(isset($_POST['descriptionFr']) && !empty($_POST['descriptionFr']) &&
    isset($_GET['id']) && !empty($_GET['id'])) {

    $update_nameService = $bdd->prepare('UPDATE service SET description = ? WHERE serviceID = ? AND language = ?');

    $update_nameService->execute(array(
        $_POST['descriptionFr'],
        $_GET['id'],
        "Fr"
    ));
}

if(isset($_POST['priceService']) && !empty($_POST['priceService']) &&
    isset($_GET['id']) && !empty($_GET['id'])) {


    $update_priceTypeService = $bdd->prepare('UPDATE service SET priceService = ? WHERE serviceID = ? AND language = ?');

    $update_priceTypeService->execute(array(
        $_POST['priceService'],
        $_GET['id'],
        "Fr"
    ));
    $update_priceTypeService->execute(array(
        $_POST['priceService'],
        $_GET['id'],
        "En"
    ));
}

if(isset($_POST['priceRecurrentService']) && !empty($_POST['priceRecurrentService']) &&
    isset($_GET['id']) && !empty($_GET['id'])) {


    $update_priceTypeService = $bdd->prepare('UPDATE service SET priceRecurrentService = ? WHERE serviceID = ? AND language = ?');

    $update_priceTypeService->execute(array(
        $_POST['priceRecurrentService'],
        $_GET['id'],
        "Fr"
    ));
    $update_priceTypeService->execute(array(
        $_POST['priceRecurrentService'],
        $_GET['id'],
        "En"
    ));
}

if(isset($_POST['minimumType']) && !empty($_POST['minimumType']) &&
    isset($_GET['id']) && !empty($_GET['id'])) {


    $update_priceTypeService = $bdd->prepare('UPDATE service SET minimumType = ? WHERE serviceID = ? AND language = ?');

    $update_priceTypeService->execute(array(
        $_POST['minimumType'],
        $_GET['id'],
        "Fr"
    ));
    $update_priceTypeService->execute(array(
        $_POST['minimumType'],
        $_GET['id'],
        "En"
    ));
}

if(isset($_POST['nameSubscriptionFr']) && !empty($_POST['nameSubscriptionFr']) &&
    isset($_GET['id_sub']) && !empty($_GET['id_sub'])) {

    $update_priceTypeService = $bdd->prepare('UPDATE subscription SET nameSubscription = ? WHERE subscriptionID = ? AND language = ?');

    $update_priceTypeService->execute(array(
        $_POST['nameSubscriptionFr'],
        $_GET['id_sub'],
        "Fr"
    ));
}
if(isset($_POST['nameSubscriptionEn']) && !empty($_POST['nameSubscriptionEn']) &&
    isset($_GET['id_sub']) && !empty($_GET['id_sub'])) {

    $update_priceTypeService = $bdd->prepare('UPDATE subscription SET nameSubscription = ? WHERE subscriptionID = ? AND language = ?');

    $update_priceTypeService->execute(array(
        $_POST['nameSubscriptionEn'],
        $_GET['id_sub'],
        "En"
    ));
}
if(isset($_POST['nbDays']) && !empty($_POST['nbDays']) &&
    isset($_GET['id_sub']) && !empty($_GET['id_sub'])) {

    $update_priceTypeService = $bdd->prepare('UPDATE subscription SET nbDays = ? WHERE subscriptionID = ? AND language = ?');

    $update_priceTypeService->execute(array(
        $_POST['nbDays'],
        $_GET['id_sub'],
        "Fr"
    ));
    $update_priceTypeService->execute(array(
        $_POST['nbDays'],
        $_GET['id_sub'],
        "En"
    ));
}
if(isset($_GET['validity1']) && !empty($_GET['validity1'])) {

    $update_priceTypeService = $bdd->prepare('UPDATE subscription SET validity = ? WHERE subscriptionID = ?');

    $update_priceTypeService->execute(array(
        1,
        $_GET['validity1']
    ));
}
if(isset($_GET['validity0']) && !empty($_GET['validity0'])) {

    $update_priceTypeService = $bdd->prepare('UPDATE subscription SET validity = ? WHERE subscriptionID = ?');

    $update_priceTypeService->execute(array(
        0,
        $_GET['validity0']
    ));
}

if(isset($_POST['startTime']) && !empty($_POST['startTime']) &&
    isset($_GET['id_sub']) && !empty($_GET['id_sub'])) {

    $update_priceTypeService = $bdd->prepare('UPDATE subscription SET startTime = ? WHERE subscriptionID = ? AND language = ?');

    $update_priceTypeService->execute(array(
        $_POST['startTime'],
        $_GET['id_sub'],
        "Fr"
    ));
    $update_priceTypeService->execute(array(
        $_POST['startTime'],
        $_GET['id_sub'],
        "En"
    ));
}
if(isset($_POST['endTime']) && !empty($_POST['endTime']) &&
    isset($_GET['id_sub']) && !empty($_GET['id_sub'])) {

    $update_priceTypeService = $bdd->prepare('UPDATE subscription SET endTime = ? WHERE subscriptionID = ? AND language = ?');

    $update_priceTypeService->execute(array(
        $_POST['endTime'],
        $_GET['id_sub'],
        "Fr"
    ));
    $update_priceTypeService->execute(array(
        $_POST['endTime'],
        $_GET['id_sub'],
        "En"
    ));
}
if(isset($_POST['value']) && !empty($_POST['value']) &&
    isset($_GET['id_sub']) && !empty($_GET['id_sub'])) {

    $update_priceTypeService = $bdd->prepare('UPDATE subscription SET value = ? WHERE subscriptionID = ? AND language = ?');

    $update_priceTypeService->execute(array(
        $_POST['value'],
        $_GET['id_sub'],
        "Fr"
    ));
    $update_priceTypeService->execute(array(
        $_POST['value'],
        $_GET['id_sub'],
        "En"
    ));
}
if(isset($_POST['pricePerYear']) && !empty($_POST['pricePerYear']) &&
    isset($_GET['id_sub']) && !empty($_GET['id_sub'])) {
    require_once '../web/stripe/init.php';
    echo $_POST['stripeID'];
    \Stripe\Stripe::setApiKey('sk_test_WDZW3sWkIUI5asuWjU1FOR7Z00kDVsxULV');
    $plan = \Stripe\Plan::retrieve(
        $_POST['stripeID']
    );
    $plan->delete();

    $sub = $bdd->prepare('SELECT * FROM subscription WHERE stripeID = :stripeID GROUP BY subscriptionID');
    $sub->execute(array(':stripeID'=>$_POST['stripeID']));
    $sub = $sub->fetch();

    $intent = \Stripe\Plan::create([
        'id' => $_POST['stripeID'],
        'amount' => $_POST['pricePerYear']*100,
        'currency' => 'eur',
        'interval' => 'year',
        'product' => 'prod_H946I3MSCrr63d',
        "nickname" => $sub['nameSubscription'],
    ]);

    $update_priceTypeService = $bdd->prepare('UPDATE subscription SET pricePerYear = ?, stripeID = ? WHERE subscriptionID = ? AND language = ?');

    $update_priceTypeService->execute(array(
        $_POST['pricePerYear'],
        $intent->id,
        $_GET['id_sub'],
        "Fr"
    ));
    $update_priceTypeService->execute(array(
        $_POST['pricePerYear'],
        $intent->id,
        $_GET['id_sub'],
        "En"
    ));
}

if(isset($_GET['addSubscription']) && !empty($_GET['addSubscription']) &&
isset($_POST['nameSubscriptionFr']) && !empty($_POST['nameSubscriptionFr']) &&
isset($_POST['nameSubscriptionEn']) && !empty($_POST['nameSubscriptionEn']) &&
isset($_POST['nbDays']) && !empty($_POST['nbDays']) &&
isset($_POST['startTime']) && !empty($_POST['startTime']) &&
isset($_POST['endTime']) && !empty($_POST['endTime']) &&
isset($_POST['pricePerYear']) && !empty($_POST['pricePerYear']) &&
isset($_POST['value']) && !empty($_POST['value'])) {
    require_once '../web/stripe/init.php';

    \Stripe\Stripe::setApiKey('sk_test_WDZW3sWkIUI5asuWjU1FOR7Z00kDVsxULV');

    $intent = \Stripe\Plan::create([
        'amount' => $_POST['pricePerYear']*100,
        'currency' => 'eur',
        'interval' => 'year',
        'product' => 'prod_H946I3MSCrr63d',
        "nickname" => $_POST['nameSubscriptionFr'],
    ]);

    $request = $bdd->prepare('SELECT subscriptionID FROM subscription WHERE subscriptionID= :id');
    $find = false;
    $id = 1;
    while (!$find) {
        $request->execute([
            'id' => $id
        ]);
        $n_id = $request->rowCount();
        if ($n_id != 0) {
            $id ++;
        } else {
            $find = true;
        }
    }
    $request = $bdd->prepare('INSERT INTO subscription VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

    $request->execute([$id,"Fr",htmlspecialchars($_POST['nameSubscriptionFr']),htmlspecialchars($_POST['nbDays']),htmlspecialchars($_POST['startTime']), htmlspecialchars($_POST['endTime']), htmlspecialchars($_POST['pricePerYear']), htmlspecialchars($_POST['value']),1,$intent->id]);
    $request->execute([$id,"En",htmlspecialchars($_POST['nameSubscriptionEn']),htmlspecialchars($_POST['nbDays']),htmlspecialchars($_POST['startTime']), htmlspecialchars($_POST['endTime']), htmlspecialchars($_POST['pricePerYear']), htmlspecialchars($_POST['value']),1,$intent->id]);
}

if(isset($_GET['addService']) && !empty($_GET['addService']) &&
    isset($_POST['nameServiceFr']) && !empty($_POST['nameServiceFr']) &&
    isset($_POST['nameServiceEn']) && !empty($_POST['nameServiceEn']) &&
    isset($_POST['descriptionFr']) && !empty($_POST['descriptionEn']) &&
    isset($_POST['descriptionFr']) && !empty($_POST['descriptionFr']) &&
    isset($_POST['priceService']) && !empty($_POST['priceService']) &&
    isset($_POST['priceRecurrentService']) && !empty($_POST['priceRecurrentService']) &&
    isset($_POST['priceTypeService']) && !empty($_POST['priceTypeService']) &&
    isset($_POST['minimumType']) && !empty($_POST['minimumType'])) {


    $request = $bdd->prepare('SELECT serviceID FROM service WHERE serviceID= :id');
    $find = false;
    $id = 1;
    while (!$find) {
        $request->execute([
            'id' => $id
        ]);
        $n_id = $request->rowCount();
        if ($n_id != 0) {
            $id ++;
        } else {
            $find = true;
        }
    }
    if($_POST['priceTypeService']=="Facturation à la tâche") $priceTypeEn = "Billing by task"; elseif ($_POST['priceTypeService']=="Facturation à l'heure") $priceTypeEn = "Billing by the hour"; else $priceTypeEn = "Mixed billing";

    $request = $bdd->prepare('INSERT INTO service(serviceID, language, nameService, description, priceService, priceRecurrentService, priceTypeService, minimumType ) VALUES(:serviceID, :language, :nameService, :description, :priceService, :priceRecurrentService, :priceTypeService, :minimumType)');

    $request->execute([
        'serviceID' => $id,
        'language' => "Fr",
        'nameService' => htmlspecialchars($_POST['nameServiceFr']),
        'description' => htmlspecialchars($_POST['descriptionFr']),
        'priceService' => htmlspecialchars($_POST['priceService']),
        'priceRecurrentService' => htmlspecialchars($_POST['priceRecurrentService']),
        'priceTypeService' => htmlspecialchars($_POST['priceTypeService']),
        'minimumType' => htmlspecialchars($_POST['minimumType'])
    ]);

    $request->execute([
        'serviceID' => $id,
        'language' => "En",
        'nameService' => htmlspecialchars($_POST['nameServiceEn']),
        'description' => htmlspecialchars($_POST['descriptionEn']),
        'priceService' => htmlspecialchars($_POST['priceService']),
        'priceRecurrentService' => htmlspecialchars($_POST['priceRecurrentService']),
        'priceTypeService' => $priceTypeEn,
        'minimumType' => htmlspecialchars($_POST['minimumType'])
    ]);

}


header('Location: index.php');

?>
<!-- Rajouter des #positions a la fin des requetes ( plus des div aux id correspondant ) -->
