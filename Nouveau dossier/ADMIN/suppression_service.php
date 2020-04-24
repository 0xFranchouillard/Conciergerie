<?php
include('config.php');
$bdd = connectionDB();

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $req2 = $bdd->prepare('SELECT tariffID FROM tariff WHERE serviceID = ?');
    $req2->execute(array($id));

    while($tariff = $req2->fetch()) {
        $req2 = $bdd->prepare('SELECT interventionID FROM contains WHERE tariffID = ?');
        $req2->execute(array($tariff['tariffID']));

        while($intervention = $req2->fetch()) {
            $req3 = $bdd->prepare('DELETE FROM bill WHERE interventionID = ?');
            $req3->execute(array($intervention['interventionID']));
        }
        while($intervention = $req2->fetch()) {
            $req4 = $bdd->prepare('DELETE FROM intervention WHERE interventionID = ?');
            $req4->execute(array($intervention['interventionID']));
        }
        while($intervention = $req2->fetch()) {
            $req4 = $bdd->prepare('DELETE FROM contains WHERE interventionID = ?');
            $req4->execute(array($intervention['interventionID']));
        }

        $req = $bdd->prepare('DELETE FROM tariff WHERE providerID = ?');
        $req->execute(array($tariff['tariffID']));
    }

    $req = $bdd->prepare('DELETE FROM service WHERE serviceID = ?');

    if($req->execute(array($id))) {
        return "Account has been deleted !";
    }else{
        return 'Fatal error';
    }

}