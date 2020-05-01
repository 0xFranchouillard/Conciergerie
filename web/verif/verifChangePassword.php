<?php
session_start();
require_once('../Pages/db.php');
$db = connectionDB();

if(isset($_POST['password']) && !empty($_POST['password']) &&
    isset($_POST['password2']) && !empty($_POST['password2'])) {
    $verifPassword = verifPwd($_POST['password']);
    if($verifPassword == "OK") {
        if($_POST['password'] == $_POST['password2']) {
            $request = $db->prepare('UPDATE serviceProvider SET password= :password WHERE providerID= :providerID && agency= :agency');
            $request->execute([
               'password'=>hash('sha256', $_POST['password']),
               'providerID'=>$_SESSION['id'],
               'agency'=>$_SESSION['agency']
            ]);
            $_SESSION['id'] = [];
            $_SESSION['agency'] = [];
            echo "OK";
        } else {
            $_SESSION['id'] = [];
            $_SESSION['agency'] = [];
            echo E_REGISTRATION11;
        }
    } else {
        $_SESSION['id'] = [];
        $_SESSION['agency'] = [];
        echo $verifPassword;
    }

}

function verifPwd($password) {
    $Number = 0;
    $Maj = 0;
    $Min = 0;
    for($i = 0; $i < strlen($password); $i++) {
        if($password[$i] >= 'A' && $password[$i] <= 'Z') {
            $Maj++;
        }
        if($password[$i] >= 'a' && $password[$i] <= 'z') {
            $Min++;
        }
        if(is_numeric($password[$i]) == true) {
            $Number++;
        }
    }
    if($Maj < 2 || $Number < 2 || $Min < 4) {
        return E_REGISTRATION10;
    }
    return "OK";
}
?>
