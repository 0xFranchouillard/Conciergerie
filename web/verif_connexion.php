<?php
require_once('lang/'.$_POST['lang'].'.php');
require_once ('Pages/db.php');
$db = connectionDB();

if(isset($_POST['email']) && !empty($_POST['email']) &&
    isset($_POST['password']) && !empty($_POST['password']) &&
    isset($_POST['type']) && !empty($_POST['type'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = hash('sha256',$_POST['password']);

    if($_POST['type'] == 'Provider' || $_POST['type'] == 'Prestataire') {
        $request = $db->prepare('SELECT providerID, password FROM serviceprovider WHERE email = :email');
    } else {
        $request = $db->prepare('SELECT clientID, password FROM client WHERE email = :email');
    }
    $request->execute([
        'email'=>$email
    ]);
    $result = $request->rowCount();
    if($result <= 0) {
        echo E_CONNEXION2;
    } else {
        $result = $request->fetch();
        if($password == $result['password']) {
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['id'] = $result[0];
            $_SESSION['password'] = $password;
            if ($_POST['type'] == 'Provider' || $_POST['type'] == 'Prestataire') {
                $_SESSION['providerID'] = $result[0];
            } else {
            $_SESSION['clientID'] = $result[0];
            }
            echo "OK";
        } else {
            echo E_CONNEXION3;
        }
    }
} else {
    echo E_CONNEXION1;
}
?>