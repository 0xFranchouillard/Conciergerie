<?php
include('config.php');
$bdd = connectionDB();

if(isset($_GET['id'])){
    $id = $_GET['id'];

     $req2 = $bdd->prepare('SELECT interventionID FROM contains WHERE tariffID = ?');
     $req2->execute(array($id));

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
    $req->execute(array($id));

    if($req->execute(array($id))) {
        return "Account has been deleted !";
    }else{
        return 'Fatal error';
    }


}