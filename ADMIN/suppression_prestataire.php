<?php
include('config.php');
$bdd = connectionDB();

if(isset($_GET['id']) AND !empty($_GET['id']) &&
    isset($_GET['agency']) AND !empty($_GET['agency'])) {
    $id = $_GET['id'];
    $agency = $_GET['agency'];

    $req2 = $bdd->prepare('DELETE FROM intervention WHERE providerID = ? AND agency = ?');
    $req2->execute(array($id,$agency));

    $req = $bdd->prepare('DELETE FROM tariff WHERE providerID = ? AND agency = ?');
    $req->execute(array($id,$agency));

    $reqx = $bdd->prepare('DELETE FROM serviceprovider WHERE providerID = ? AND agency = ?');

    if($reqx->execute(array($id,$agency))) {
        return "Account has been deleted !";
    }else{
        return 'Fatal error';
    }
}