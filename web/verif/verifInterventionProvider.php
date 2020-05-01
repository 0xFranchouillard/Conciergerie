<?php
session_start();
require_once('../lang/'.$_SESSION['lang'].'.php');
require_once('../Pages/db.php');
$db = connectionDB();
$requestValidIntervention = $db->prepare('UPDATE Intervention SET statutIntervention= 1 WHERE interventionID= :interventionID');
$requestIntervention = $db->prepare('SELECT * FROM Intervention WHERE interventionID= :interventionID');
$requestTariffIntervention = $db->prepare('SELECT priceService FROM Tariff WHERE providerID= :providerID && agency= :agency && serviceID= :serviceID && dateID IN (SELECT dateID FROM ValidityDate WHERE 
    ((startDay <= :day && endDay >= :day && startDay <= endDay) || (((startDay <= :day && endDay < :day) || (startDay > :day && endDay >= :day)) && startDay >= endDay)) && startTime <= :time && endTime >= :timeMax) ORDER BY priceService LIMIT 1');
$requestTariff = $db->prepare('SELECT providerID, agency FROM Tariff WHERE serviceID= :serviceID && agency= :agency && priceService > :priceService && dateID IN (SELECT dateID FROM ValidityDate WHERE 
    ((startDay <= :day && endDay >= :day && startDay <= endDay) || (((startDay <= :day && endDay < :day) || (startDay > :day && endDay >= :day)) && startDay >= endDay)) && startTime <= :time && endTime >= :timeMax) && 
    providerID NOT IN (SELECT providerID FROM Intervention WHERE dateIntervention= :date && timeIntervention >= :timeMin && timeIntervention <= :timeMax) ORDER BY priceService LIMIT 1');
$requestUpdateIntervention = $db->prepare('UPDATE Intervention SET providerID= :providerID, agencyProvider= :agency WHERE interventionID= :interventionID');

if(isset($_POST['interventionID']) && $_POST['interventionID'] != null &&
    isset($_POST['button']) && $_POST['button'] == 1) {
    $requestValidIntervention->execute([
       'interventionID'=>$_POST['interventionID']
    ]);
    echo "OK";
}

if(isset($_POST['interventionID']) && $_POST['interventionID'] != null &&
    isset($_POST['button']) && $_POST['button'] == 2) {
    $requestIntervention->execute([
       'interventionID'=>$_POST['interventionID']
    ]);
    $resultIntervention = $requestIntervention->fetch();
    $requestTariffIntervention->execute([
        'providerID'=>$resultIntervention['providerID'],
        'agency'=>$resultIntervention['agencyProvider'],
        'serviceID'=>$resultIntervention['serviceID'],
        'day'=>$resultIntervention['dateIntervention'],
        'time'=>$resultIntervention['timeIntervention'],
        'timeMax' => date('H:i:s', strtotime($resultIntervention['timeIntervention'] . '+ ' . intval($resultIntervention['pastType']) . ' hour'))
    ]);
    $resultTariffIntervention = $requestTariffIntervention->fetch();
    $requestTariff->execute([
       'serviceID'=>$resultIntervention['serviceID'],
        'agency'=>$resultIntervention['agency'],
        'priceService'=>$resultTariffIntervention['priceService'],
        'day'=>$resultIntervention['dateIntervention'],
        'time'=>$resultIntervention['timeIntervention'],
        'timeMin' => date('H:i:s', strtotime($resultIntervention['timeIntervention'] . '- ' . intval($resultIntervention['pastType']) . ' hour')),
        'timeMax' => date('H:i:s', strtotime($resultIntervention['timeIntervention'] . '+ ' . intval($resultIntervention['pastType']) . ' hour'))
    ]);
    $resultTariff = $requestTariff->fetch();

    if($requestTariff->rowCount() != 0) {
        $requestUpdateIntervention->execute([
           'providerID'=>$resultTariff['providerID'],
            'agency'=>$resultTariff['agency']
        ]);
    } else {
        removeIntervention();
    }
    echo "OK";
}

function removeIntervention() {
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
            'clientID' => $resultIntervention['clientID'],
            'agency' => $resultIntervention['agency'],
            'serviceID' => $resultIntervention['serviceID']
        ]);
        if ($requestVerifCredit->rowCount() != 0) {
            $resultVerifCredit = $requestVerifCredit->fetch();
            $requestUpadteCredit = $db->prepare('UPDATE Credit SET numberTaken= :numberTaken WHERE clientID= :clientID && agency= :agency && serviceID= :serviceID');
            $requestUpadteCredit->execute([
                'numberTaken' => (intval($resultVerifCredit['numberTaken']) + intval($resultIntervention['pastType'])),
                'clientID' => $resultIntervention['clientID'],
                'agency' => $resultIntervention['agency'],
                'serviceID' => $resultIntervention['serviceID']
            ]);
        } else {
            $requestInsertCredit = $db->prepare('INSERT INTO Credit(clientID, agency, serviceID, numberTaken) VALUES(:clientID, :agency, :serviceID, :numberTaken)');
            $requestInsertCredit->execute([
                'clientID' => $resultIntervention['clientID'],
                'agency' => $resultIntervention['agency'],
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
