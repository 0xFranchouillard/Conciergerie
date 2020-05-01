<?php
require_once('../lang/'.$_POST['lang'].'.php');
require_once ('../Pages/db.php');
$db = connectionDB();

if(isset($_POST['email']) && !empty($_POST['email']) &&
    isset($_POST['password']) && !empty($_POST['password']) &&
    isset($_POST['type']) && !empty($_POST['type'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = hash('sha256',$_POST['password']);

    if($_POST['type'] == _PROVIDER) {
        $request = $db->prepare('SELECT providerID, agency, password FROM serviceprovider WHERE email = :email');
    } else {
        $request = $db->prepare('SELECT clientID, agency, password, stripeID FROM client WHERE email = :email');
    }
    $request->execute([
        'email'=>$email
    ]);
    $result = $request->rowCount();
    if($result <= 0) {
        echo E_CONNEXION2;
    } else {
        $result = $request->fetch();

        if($_POST['type'] == _PROVIDER) {

            if (strlen($_POST['password']) == 6 && $_POST['password'] == $result['password']) {
                session_start();
                $_SESSION['id'] = $result['providerID'];
                $_SESSION['agency'] = $result['agency'];
                echo "changePassword";
                exit();
            }

            if ($password == $result['password']) {
                session_start();
                $_SESSION['id'] = $result[0];
                $_SESSION['agencyClient'] = $result['agency'];
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                $_SESSION['provider'] = 1;
                $_SESSION['providerID'] = $result[0];
                echo "OK";
            } else {
                echo E_CONNEXION3;
            }

        } else {

            if ($password == $result['password']) {
                session_start();
                $_SESSION['id'] = $result[0];
                $_SESSION['agencyClient'] = $result['agency'];
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                $_SESSION['clientID'] = $result[0];
                $_SESSION['stripeID'] = $result['stripeID'];
                verifValidityEstimate();
                verifValidityIntervention();
                echo "OK";
            } else {
                echo E_CONNEXION3;
            }

        }
    }
} else {
    echo E_CONNEXION1;
}

function verifValidityEstimate() {
    $db = connectionDB();
    date_default_timezone_set('Europe/Paris');
    $request = $db->prepare('DELETE FROM Bill WHERE clientID= :clientID && agency= :agency && validityDate < :validityDate');
    $request->execute([
       'clientID'=>$_SESSION['id'],
       'agency'=>$_SESSION['agencyClient'],
       'validityDate'=>date('Y-m-d')
    ]);
}

function verifValidityIntervention() {
    $db = connectionDB();
    $requestDeleteIntervention = $db->prepare('DELETE FROM Intervention WHERE clientID= :clientID && agency= :agency && dateIntervention < :dateIntervention');
    $requestIntervention = $db->prepare('SELECT * FROM Intervention WHERE clientID= :clientID && agency= :agency && dateIntervention < :dateIntervention');
    $requestIntervention->execute([
        'clientID'=>$_SESSION['id'],
        'agency'=>$_SESSION['agencyClient'],
        'dateIntervention'=>date('Y-m-d')
    ]);
    if($requestIntervention->rowCount() != 0) {
        while ($resultIntervention = $requestIntervention->fetch()) {
            $requestCredit = $db->prepare('SELECT * FROM Credit WHERE clientID= :clientID && agency= :agency && serviceID= :serviceID');
            $requestCredit->execute([
                'clientID' => $_SESSION['id'],
                'agency' => $_SESSION['agencyClient'],
                'serviceID' => $resultIntervention['serviceID']
            ]);
            if ($requestCredit->rowCount() != 0) {
                $resultCredit = $requestCredit->fetch();
                $requestUpadteCredit = $db->prepare('UPDATE Credit SET numberTaken= :numberTaken WHERE clientID= :clientID && agency= :agency && serviceID= :serviceID');
                $requestUpadteCredit->execute([
                    'numberTaken' => (intval($resultCredit['numberTaken']) + intval($resultIntervention['pastType'])),
                    'clientID' => $_SESSION['id'],
                    'agency' => $_SESSION['agencyClient'],
                    'serviceID' => $resultIntervention['serviceID']
                ]);
            } else {
                $requestInsertCredit = $db->prepare('INSERT INTO Credit(clientID, agency, serviceID, numberTaken) VALUES(:clientID, :agency, :serviceID, :numberTaken)');
                $requestInsertCredit->execute([
                    'clientID' => $_SESSION['id'],
                    'agency' => $_SESSION['agencyClient'],
                    'serviceID' => $resultIntervention['serviceID'],
                    'numberTaken' => intval($resultIntervention['pastType'])
                ]);
            }
        }
        $requestDeleteIntervention->execute([
            'clientID'=>$_SESSION['id'],
            'agency'=>$_SESSION['agencyClient'],
            'dateIntervention'=>date('Y-m-d')
        ]);
    }
}
?>
