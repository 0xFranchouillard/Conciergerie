<?php
session_start();
require_once('../lang/'.$_SESSION['lang'].'.php');
require_once('../Pages/db.php');
$db = connectionDB();

if(isset($_POST['oldPassword']) && !empty($_POST['oldPassword']) &&
    isset($_POST['password']) && !empty($_POST['password']) &&
    isset($_POST['password2']) && !empty($_POST['password2'])) {

    if(isset($_SESSION['email']) && !empty($_SESSION['email'])) {
        if(isset($_SESSION['provider']) && $_SESSION['provider'] == 1) {
            $requestPassword = $db->prepare('SELECT password FROM ServiceProvider WHERE providerID= :providerID && agency= :agency');
            $requestPassword->execute([
                'providerID'=>$_SESSION['id'],
                'agency'=>$_SESSION['agencyClient']
            ]);
            $resultPassword = $requestPassword->fetch();
            if($resultPassword['password'] != hash('sha256',$_POST['oldPassword'])) {
                echo E_CONNEXION3;
                exit();
            }
        } else {
            $requestPassword = $db->prepare('SELECT password FROM Client WHERE clientID= :clientID && agency= :agency');
            $requestPassword->execute([
                'clientID'=>$_SESSION['id'],
                'agency'=>$_SESSION['agencyClient']
            ]);
            $resultPassword = $requestPassword->fetch();
            if($resultPassword['password'] != hash('sha256',$_POST['oldPassword'])) {
                echo E_CONNEXION3;
                exit();
            }
        }
    } else {
        $requestPassword = $db->prepare('SELECT password FROM ServiceProvider WHERE providerID= :providerID && agency= :agency');
        $requestPassword->execute([
           'providerID'=>$_SESSION['id'],
           'agency'=>$_SESSION['agencyClient']
        ]);
        $resultPassword = $requestPassword->fetch();
        if($resultPassword['password'] != $_POST['oldPassword']) {
            echo E_CONNEXION3;
            exit();
        }
    }

    $verifPassword = verifPwd($_POST['password']);
    if($verifPassword == "OK") {
        if($_POST['password'] == $_POST['password2']) {

            if(isset($_SESSION['email']) && !empty($_SESSION['email']) && (!isset($_SESSION['provider']) || $_SESSION['provider'] != 1)) {
                $request = $db->prepare('UPDATE client SET password= :password WHERE clientID= :clientID && agency= :agency');
                $request->execute([
                    'password'=>hash('sha256', $_POST['password']),
                    'clientID'=>$_SESSION['id'],
                    'agency'=>$_SESSION['agencyClient']
                ]);
                $_SESSION = [];
            } else {
                $request = $db->prepare('UPDATE serviceProvider SET password= :password WHERE providerID= :providerID && agency= :agency');
                $request->execute([
                    'password'=>hash('sha256', $_POST['password']),
                    'providerID'=>$_SESSION['id'],
                    'agency'=>$_SESSION['agencyClient']
                ]);
                $_SESSION = [];
            }

            echo "OK";
        } else {
            echo E_REGISTRATION11;
        }
    } else {
        echo $verifPassword;
    }
} else {
    echo E_REGISTRATION1;
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
