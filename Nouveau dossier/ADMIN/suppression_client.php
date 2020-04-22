<?php
include('config.php');
$bdd = connectionDB();

if(isset($_GET['id']) AND !empty($_GET['id']) &&
    isset($_GET['agency']) AND !empty($_GET['agency'])){
    $id = $_GET['id'];
    $agency = $_GET['agency'];

    $req = $bdd->prepare('DELETE FROM subscribes WHERE clientID = ? AND agency = ?');
    $req->execute(array($id,$agency));

    $req = $bdd->prepare('DELETE FROM intervention WHERE clientID = ? AND agency = ?');
    $req->execute(array($id,$agency));

    $req = $bdd->prepare('DELETE FROM client WHERE clientID = ? AND agency = ?');

    if($req->execute(array($id,$agency))) {
        return "Account has been deleted !";
    }else{
        return 'Fatal error';
    }


}