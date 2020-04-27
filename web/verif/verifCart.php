<?php
session_start();
require_once('../lang/'.$_SESSION['lang'].'.php');
require_once('../Pages/db.php');
$db = connectionDB();
$requestService = $db->prepare('SELECT nameService FROM Service WHERE serviceID= :id && language= :lang');
$requestClient = $db->prepare('SELECT lastName, firstName, city, address, registrationDate FROM Client WHERE clientID= :id && agency= :agency');
$requestIdBill = $db->prepare('SELECT * FROM Bill WHERE billID= :billID');
$requestBill = $db->prepare('INSERT INTO Bill(billID, estimate, totalPrice, priceService, numberTaken, billDate, validityDate, clientID, agency, serviceID) VALUES(:billID, :estimate, :totalPrice, :priceService, :numberTaken, :billDate, :validityDate, :clientID, :agency, :serviceID)');
$requestUpdateBill = $db->prepare('UPDATE Bill SET validityDate= null, estimate= false, billDate= :date WHERE billID= :id');


if(isset($_POST['button']) && $_POST['button'] == 2) {
    fillSession($_POST['button'],$requestClient,$requestIdBill);
    insertBdd($_POST['button'],$requestBill);
    echo "ESTIMATE";
}

if(isset($_POST['button']) && $_POST['button'] == 1) {
    fillSession($_POST['button'],$requestClient,$requestIdBill);
    insertBdd($_POST['button'],$requestBill);
    echo "BILL";
}

if(isset($_POST['button']) && $_POST['button'] == 0) {
    $_SESSION['serviceIDCart'] = [];
    $_SESSION['nbTakeCart'] = [];
    $_SESSION['nameServiceBill'] = [];
    $_SESSION['nbTakeBill'] = [];
    $_SESSION['priceServiceBill'] = [];
    $_SESSION['priceRecurrentServiceBill'] = [];
    $_SESSION['minimumTypeBill'] = [];
    $_SESSION['totalPriceBill'] = [];
    echo "CANCEL";
}

if(isset($_POST['button']) && $_POST['button'] == 3 &&
    isset($_POST['billID']) && !empty($_POST['billID'])) {
    fillSession($_POST['button'],$requestClient,$requestIdBill);
    $_SESSION['billID'] = $_POST['billID'];

    $requestIdBill->execute([
       'billID'=>$_POST['billID']
    ]);
    $_SESSION['nameServiceBill'] = array();
    $_SESSION['nbTakeBill'] = array();
    $_SESSION['priceServiceBill'] = array();
    $_SESSION['totalPriceBill'] = 0;
    for($i=0; $resultIdBill = $requestIdBill->fetch(); $i++) {
        $requestService->execute([
           'id'=>$resultIdBill['serviceID'],
           'lang'=>$_SESSION['lang']
        ]);
        $resultService = $requestService->fetch();
        $_SESSION['nameServiceBill'][$i] = $resultService['nameService'];
        $_SESSION['nbTakeBill'][$i] = $resultIdBill['numberTaken'];
        $_SESSION['priceServiceBill'][$i] = $resultIdBill['priceService'];
        $_SESSION['totalPriceBill'] += floatval($resultIdBill['priceService'])*intval($resultIdBill['numberTaken']);
        addCredit($resultIdBill['serviceID'],$resultIdBill['numberTaken']);
    }

    $requestUpdateBill->execute([
        'date'=>$_SESSION['billDate'],
        'id'=>$_POST['billID']
    ]);

    echo "BUYESTIMATE";
}

function fillSession($button, $requestClient, $requestIdBill) {
    $requestClient->execute([
        'id'=>$_SESSION['id'],
        'agency'=>$_SESSION['agencyClient']
    ]);
    $result = $requestClient->fetch();

    $_SESSION['lastNameBill'] = $result['lastName'];
    $_SESSION['firstNameBill'] = $result['firstName'];
    $_SESSION['cityBill'] = $result['city'];
    $_SESSION['addressBill'] = $result['address'];
    $_SESSION['registrationDateBill'] = $result['registrationDate'];
    date_default_timezone_set('Europe/Paris');
    $_SESSION['billDate'] = date("Y-m-d");
    if($button == 2) {
        $_SESSION['validityDate'] = date('Y-m-d', strtotime($_SESSION['billDate'] . " +3 month"));
        $_SESSION['nameFileBill'] = _ESTIMATE . '_LuxeryService.pdf';
        $_SESSION['estimate'] = true;
    }
    if($button == 1 || $button == 3) {
        $_SESSION['nameFileBill'] = _BILL . '_LuxeryService.pdf';
        $_SESSION['estimate'] = false;
    }

    if($button == 1 || $button == 2) {
        $find = false;
        $_SESSION['billID'] = 1;
        while (!$find) {
            $requestIdBill->execute([
                'billID' => $_SESSION['billID']
            ]);
            $n_id = $requestIdBill->rowCount();
            if ($n_id != 0) {
                $_SESSION['billID']++;
            } else {
                $find = true;
            }
        }
    }
}

function insertBdd($button, $requestBill) {
    if($button == 2) {
        $estimate=true;
        $validityDate=$_SESSION['validityDate'];
    }
    if($button == 1) {
        $estimate=false;
        $validityDate=null;
    }

    for($i = 0; $i < count($_SESSION['serviceIDCart']); $i++) {
        if(!empty($_SESSION['minimumTypeBill'][$i]) && $_SESSION['nbTakeBill'][$i] >= $_SESSION['minimumTypeBill'][$i]) {
            $requestBill->execute([
                'billID'=>$_SESSION['billID'],
                'estimate'=>$estimate,
                'totalPrice'=>$_SESSION['totalPriceBill'],
                'priceService'=>$_SESSION['priceRecurrentServiceBill'][$i],
                'numberTaken'=>$_SESSION['nbTakeBill'][$i],
                'billDate'=>$_SESSION['billDate'],
                'validityDate'=>$validityDate,
                'clientID'=>$_SESSION['id'],
                'agency'=>$_SESSION['agencyClient'],
                'serviceID'=>$_SESSION['serviceIDCart'][$i]
            ]);
        } else {
            $requestBill->execute([
                'billID'=>$_SESSION['billID'],
                'estimate'=>$estimate,
                'totalPrice'=>$_SESSION['totalPriceBill'],
                'priceService'=>$_SESSION['priceServiceBill'][$i],
                'numberTaken'=>$_SESSION['nbTakeBill'][$i],
                'billDate'=>$_SESSION['billDate'],
                'validityDate'=>$validityDate,
                'clientID'=>$_SESSION['id'],
                'agency'=>$_SESSION['agencyClient'],
                'serviceID'=>$_SESSION['serviceIDCart'][$i]
            ]);
        }
        if($button == 1) {
            addCredit($_SESSION['serviceIDCart'][$i],$_SESSION['nbTakeBill'][$i]);
        }
    }
}

function addCredit($serviceID, $numberTaken) {
    $db = connectionDB();
    $requestCredit = $db->prepare('SELECT * FROM Credit WHERE clientID= :clientID && agency= :agency && serviceID= :serviceID');
    $requestCredit->execute([
       'clientID'=>$_SESSION['id'],
       'agency'=>$_SESSION['agencyClient'],
       'serviceID'=>$serviceID
    ]);
    if($requestCredit->rowCount() != 0) {
        $resultCredit = $requestCredit->fetch();
        $nbTake = intval($resultCredit['numberTaken'])+intval($numberTaken);
        $requestCredit = $db->prepare('UPDATE Credit SET numberTaken= :nbTake WHERE clientID= :clientID && agency= :agency && serviceID= :serviceID');
        $requestCredit->execute([
           'nbTake'=>$nbTake,
           'clientID'=>$_SESSION['id'],
           'agency'=>$_SESSION['agencyClient'],
           'serviceID'=>$serviceID
        ]);
    } else {
        $requestCredit = $db->prepare('INSERT INTO Credit(clientID, agency, serviceID, numberTaken) VALUES(:clientID, :agency, :serviceID, :nbTake)');
        $requestCredit->execute([
            'clientID'=>$_SESSION['id'],
            'agency'=>$_SESSION['agencyClient'],
            'serviceID'=>$serviceID,
            'nbTake'=>$numberTaken
        ]);
    }
}
?>