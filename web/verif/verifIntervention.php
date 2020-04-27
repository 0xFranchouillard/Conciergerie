<?php
session_start();
require_once('../lang/'.$_SESSION['lang'].'.php');
require_once('../Pages/db.php');
$db = connectionDB();
$requestService = $db->prepare('SELECT priceTypeService FROM Service WHERE serviceID= :serviceID && language= :lang');
$requestSubscribes = $db->prepare('SELECT * FROM Subscribes WHERE clientID= :clientID && agency= :agency && subscriptionID IN (SELECT subscriptionID FROM Relate WHERE serviceID= :serviceID)');
$requestSubscription = $db->prepare('SELECT * FROM Subscription WHERE subscriptionID= :subscriptionID && language= :lang');
$requestCredit = $db->prepare('SELECT numberTaken FROM Credit WHERE clientID= :clientID && agency= :agency && serviceID= :serviceID');
$requestSubscribes2 = $db->prepare('SELECT valueMonth FROM Subscribes WHERE clientID= :clientID && agency= :agency && subscriptionID= :subscriptionID');
$requestUpdateSubscribes = $db->prepare('UPDATE Subscribes SET valueMonth= :valueMonth WHERE clientID= :clientID && agency= :agency && subscriptionID= :subscriptionID');
$requestUpdateCredit = $db->prepare('UPDATE Credit SET numberTaken= :numberTaken WHERE clientID= :clientID && agency= :agency && serviceID= :serviceID');

if(isset($_POST['button']) && $_POST['button'] == 1) {
    if (isset($_POST['serviceSelect']) && $_POST['serviceSelect'] != null) {
        $requestService->execute([
            'serviceID' => $_POST['serviceSelect'],
            'lang' => $_SESSION['lang']
        ]);
        $resultService = $requestService->fetch();
        $msg = " ".$resultService['priceTypeService']."OK<select id='subscriptionSelect'><option value=''>"._USESUBSCRIPTION."</option>";
        $requestSubscribes->execute([
           'clientID'=>$_SESSION['id'],
           'agency'=>$_SESSION['agencyClient'],
           'serviceID'=>$_POST['serviceSelect']
        ]);
        while($resultSubscribes = $requestSubscribes->fetch()) {
            $requestSubscription->execute([
               'subscriptionID'=>$resultSubscribes['subscriptionID'],
               'lang'=>$_SESSION['lang']
            ]);
            $resultSubscription = $requestSubscription->fetch();
            $msg = $msg."<option value='".$resultSubscribes['subscriptionID']."'>"._SUBSCRIPTION." : ".$resultSubscription['nameSubscription']." - "._REST." : ".$resultSubscribes['valueMonth']." ".$resultService['priceTypeService']."</option>";
        }
        $msg = $msg."</select>";
        echo $msg;
    } else {
        echo "ERROR";
    }
}

if(isset($_POST['button']) && $_POST['button'] == 2) {
    if(isset($_POST['nbTakeIntervention']) && !empty($_POST['nbTakeIntervention']) &&
        isset($_POST['dateIntervention']) && !empty($_POST['dateIntervention']) &&
        isset($_POST['timeIntervention']) && !empty($_POST['timeIntervention'])) {
        if(intval($_POST['nbTakeIntervention']) >= 0) {
            $requestCredit->execute([
                'clientID' => $_SESSION['id'],
                'agency' => $_SESSION['agencyClient'],
                'serviceID' => $_POST['serviceSelect']
            ]);
            if ($requestCredit->rowCount() != 0) {
                $resultCredit = $requestCredit->fetch();
            }

            if (isset($_POST['subscriptionSelect']) && $_POST['subscriptionSelect'] != null) {
                $requestSubscribes2->execute([
                    'clientID' => $_SESSION['id'],
                    'agency' => $_SESSION['agencyClient'],
                    'subscriptionID' => $_POST['subscriptionSelect']
                ]);
                $resultSubscribes2 = $requestSubscribes2->fetch();

                if (intval($resultSubscribes2['valueMonth']) > intval($_POST['nbTakeIntervention'])) {
                    $info = createIntervention();
                    $verif = explode(" ", $info);
                    if ($verif[0] == "OK") {
                        $requestUpdateSubscribes->execute([
                            'valueMonth' => (intval($resultSubscribes2['valueMonth']) - intval($_POST['nbTakeIntervention'])),
                            'clientID' => $_SESSION['id'],
                            'agency' => $_SESSION['agencyClient'],
                            'subscriptionID' => $_POST['subscriptionSelect']
                        ]);
                        echo $info;
                    } else {
                        echo $info;
                    }
                } elseif ($requestCredit->rowCount() != 0 && (intval($resultSubscribes2['valueMonth']) + intval($resultCredit['numberTaken'])) > intval($_POST['nbTakeIntervention'])) {
                    $info = createIntervention();
                    $verif = explode(" ", $info);
                    if ($verif[0] == "OK") {
                        $requestUpdateSubscribes->execute([
                            'valueMonth' => 0,
                            'clientID' => $_SESSION['id'],
                            'agency' => $_SESSION['agencyClient'],
                            'subscription' => $_POST['subscriptionSelect']
                        ]);
                        $requestUpdateCredit->execute([
                            'numberTaken' => ((intval($resultSubscribes2['valueMonth']) + intval($resultCredit['numberTaken'])) - intval($_POST['nbTakeIntervention'])),
                            'clientID' => $_SESSION['id'],
                            'agency' => $_SESSION['agencyClient'],
                            'serviceID' => $_POST['serviceSelect']
                        ]);
                        echo $info;
                    } else {
                        echo $info;
                    }
                } else {
                    echo _YOUMISS . ' ' . (intval($_POST['nbTakeIntervention']) - (intval($resultSubscribes2['valueMonth']) + intval($resultCredit['numberTaken']))) . ' ' . _CREDITS;
                }
            } else {
                if ($requestCredit->rowCount() != 0 && intval($resultCredit['numberTaken']) > intval($_POST['nbTakeIntervention'])) {
                    $info = createIntervention();
                    $verif = explode(" ", $info);
                    if ($verif[0] == "OK") {
                        $requestUpdateCredit->execute([
                            'numberTaken' => (intval($resultCredit['numberTaken']) - intval($_POST['nbTakeIntervention'])),
                            'clientID' => $_SESSION['id'],
                            'agency' => $_SESSION['agencyClient'],
                            'serviceID' => $_POST['serviceSelect']
                        ]);
                        echo $info;
                    } else {
                        echo $info;
                    }
                } else {
                    echo _YOUMISS . ' ' . (intval($_POST['nbTakeIntervention']) - intval($resultCredit['numberTaken'])) . ' ' . _CREDITS;
                }
            }
        } else {
            echo _NEGATIVENUMBER;
        }
    } else {
        echo E_REGISTRATION1;
    }
}

if(isset($_POST['button']) && $_POST['button'] == 3) {
    if(isset($_POST['interventionID']) && $_POST['interventionID'] != null) {
        deleteIntervention();
        echo "OK";
    }
}

function createIntervention() {
    $db = connectionDB();
    $requestTariff = $db->prepare('SELECT tariffID, providerID FROM Tariff WHERE serviceID= :serviceID && agency= :agency && dateID IN (SELECT dateID FROM ValidityDate WHERE 
    ((startDay <= :day && endDay >= :day && startDay <= endDay) || (((startDay <= :day && endDay < :day) || (startDay > :day && endDay >= :day)) && startDay >= endDay)) && startTime <= :time && endTime >= :timeMax) && 
    providerID NOT IN (SELECT providerID FROM Intervention WHERE dateIntervention= :date && timeIntervention >= :timeMin && timeIntervention <= :timeMax) ORDER BY priceService LIMIT 1');
    $requestTariff->execute([
        'serviceID' => $_POST['serviceSelect'],
        'agency' => $_SESSION['agencyClient'],
        'day' => date('w', strtotime($_POST['dateIntervention'])),
        'date' => $_POST['dateIntervention'],
        'time' => $_POST['timeIntervention'],
        'timeMin' => date('H:i:s', strtotime($_POST['timeIntervention'] . '- ' . intval($_POST['nbTakeIntervention']) . ' hour')),
        'timeMax' => date('H:i:s', strtotime($_POST['timeIntervention'] . '+ ' . intval($_POST['nbTakeIntervention']) . ' hour'))
    ]);
    if ($requestTariff->rowCount() != 0) {
        $resultTariff = $requestTariff->fetch();

        $request = $db->prepare('SELECT interventionID FROM Intervention WHERE interventionID= :id');
        $find = false;
        $id = 1;
        while (!$find) {
            $request->execute([
                'id' => $id
            ]);
            $n_id = $request->rowCount();
            if ($n_id != 0) {
                $id++;
            } else {
                $find = true;
            }
        }

        $requestIntervention = $db->prepare('INSERT INTO Intervention(interventionID, dateIntervention, timeIntervention, pastType, statutIntervention, clientID, agency, serviceID, providerID, agencyProvider) 
        VALUES(:interventionID, :dateIntervention, :timeIntervention, :pastType, 0, :clientID, :agency, :serviceID, :providerID, :agencyProvider)');
        $requestIntervention->execute([
            'interventionID' => $id,
            'dateIntervention' => $_POST['dateIntervention'],
            'timeIntervention' => $_POST['timeIntervention'],
            'pastType' => intval($_POST['nbTakeIntervention']),
            'clientID' => intval($_SESSION['id']),
            'agency' => $_SESSION['agencyClient'],
            'serviceID' => intval($_POST['serviceSelect']),
            'providerID' => intval($resultTariff['providerID']),
            'agencyProvider' => $_SESSION['agencyClient']
        ]);
        return "OK " . _INTERVENTIONCREATE;
    } else {
        return _NOPROVIDER;
    }
}

function deleteIntervention() {
    $db = connectionDB();
    $requestDeleteIntervention = $db->prepare('DELETE FROM Intervention WHERE interventionID= :interventionID');
    $requestIntervention = $db->prepare('SELECT * FROM Intervention WHERE interventionID= :interventionID');
    $requestIntervention->execute([
        'interventionID'=>$_POST['interventionID']
    ]);
    if($requestIntervention->rowCount() != 0) {
        $resultIntervention = $requestIntervention->fetch();
        $requestVerifCredit = $db->prepare('SELECT numberTaken FROM Credit WHERE clientID= :clientID && agency= :agency && serviceID= :serviceID');
        $requestVerifCredit->execute([
            'clientID' => $_SESSION['id'],
            'agency' => $_SESSION['agencyClient'],
            'serviceID' => $resultIntervention['serviceID']
        ]);
        if ($requestVerifCredit->rowCount() != 0) {
            $resultVerifCredit = $requestVerifCredit->fetch();
            $requestUpadteCredit = $db->prepare('UPDATE Credit SET numberTaken= :numberTaken WHERE clientID= :clientID && agency= :agency && serviceID= :serviceID');
            $requestUpadteCredit->execute([
                'numberTaken' => (intval($resultVerifCredit['numberTaken']) + intval($resultIntervention['pastType'])),
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
        $requestDeleteIntervention->execute([
            'interventionID'=>$_POST['interventionID']
        ]);
    }
}
?>