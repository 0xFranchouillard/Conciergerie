<?php
session_start();
require_once('../Pages/db.php');
$db = connectionDB();
$request = $db->prepare('SELECT lastName, firstName, city, address, registrationDate FROM Client WHERE clientID= :id');

if(isset($_POST['button']) && $_POST['button'] == 2) {
    $request->execute([
        'id'=>$_SESSION['id']
    ]);
    $result = $request->fetch();

    $_SESSION['nameFileEstimate'] = 'Devis' . '.pdf';

    echo "ESTIMATE";
}

if(isset($_POST['button']) && $_POST['button'] == 0) {
    $_SESSION['serviceIDCart'] = [];
    $_SESSION['nbTakeCart'] = [];
    echo "CANCEL";
}
?>