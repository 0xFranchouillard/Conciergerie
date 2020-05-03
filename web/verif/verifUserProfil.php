<?php
session_start();
require_once('../lang/'.$_SESSION['lang'].'.php');
require_once ('../Pages/db.php');
$db = connectionDB();

if(isset($_POST['lastName']) && !empty($_POST['lastName']) &&
    isset($_POST['firstName']) && !empty($_POST['firstName']) &&
    isset($_POST['email']) && !empty($_POST['email']) &&
    isset($_POST['address']) && !empty($_POST['address']) &&
    isset($_POST['city']) && !empty($_POST['city']) &&
    isset($_POST['phoneNumber']) && !empty($_POST['phoneNumber'])) {

    $verifLastName = verifName($_POST['lastName']);
    $verifFirstName = verifName($_POST['firstName']);
    $verifCity = verifName($_POST['city']);
    $verifPhoneNumber = verifPhoneNumber($_POST['phoneNumber']);
    $verifAddress = verifAddress($_POST['address']);

    if($verifLastName == "OK") {
        if($verifFirstName == "OK") {
            if($verifCity == "OK") {
                if($verifPhoneNumber == "OK") {
                    if($verifAddress == "OK") {
                        if(strlen($_POST['email']) <= 140) {
                            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                                if(isset($_SESSION['provider']) && $_SESSION['provider'] == 1) {
                                    $request = $db->prepare('SELECT email FROM Client WHERE email= :email');
                                    $request->execute([
                                        'email' => htmlspecialchars($_POST['email'])
                                    ]);
                                } else {
                                    $request = $db->prepare('SELECT email FROM ServiceProvider WHERE email= :email');
                                    $request->execute([
                                        'email' => htmlspecialchars($_POST['email'])
                                    ]);
                                }
                                $result = $request->rowCount();
                                if ($result == 0) {
                                    if(isset($_SESSION['provider']) && $_SESSION['provider'] == 1) {
                                        $requestUpdateUser = $db->prepare('UPDATE ServiceProvider SET lastName= :lastName, firstName= :firstName, email= :email, address= :address, city= :city, phoneNumber= :phoneNumber WHERE providerID= :providerID && agency= :agency');
                                        $requestUpdateUser->execute([
                                           'lastName'=>htmlspecialchars($_POST['lastName']),
                                           'firstName'=>htmlspecialchars($_POST['firstName']),
                                           'email'=>htmlspecialchars($_POST['email']),
                                           'address'=>htmlspecialchars($_POST['address']),
                                           'city'=>htmlspecialchars($_POST['city']),
                                           'phoneNumber'=>htmlspecialchars($_POST['phoneNumber']),
                                            'providerID'=>$_SESSION['id'],
                                            'agency'=>$_SESSION['agencyClient']
                                        ]);
                                    } else {
                                        $requestUpdateUser = $db->prepare('UPDATE Client SET lastName= :lastName, firstName= :firstName, email= :email, address= :address, city= :city, phoneNumber= :phoneNumber WHERE clientID= :clientID && agency= :agency');
                                        $requestUpdateUser->execute([
                                            'lastName'=>htmlspecialchars($_POST['lastName']),
                                            'firstName'=>htmlspecialchars($_POST['firstName']),
                                            'email'=>htmlspecialchars($_POST['email']),
                                            'address'=>htmlspecialchars($_POST['address']),
                                            'city'=>htmlspecialchars($_POST['city']),
                                            'phoneNumber'=>htmlspecialchars($_POST['phoneNumber']),
                                            'clientID'=>$_SESSION['id'],
                                            'agency'=>$_SESSION['agencyClient']
                                        ]);
                                    }

                                    echo "OK "._MODIFSUCCESS;
                                } else {
                                    echo E_REGISTRATION15;
                                }
                            } else {
                                echo E_REGISTRATION14;
                            }
                        } else {
                            echo E_REGISTRATION13;
                        }
                    } else {
                        echo $verifAddress;
                    }
                } else {
                    echo $verifPhoneNumber;
                }
            } else {
                echo $verifCity;
            }
        } else {
            echo $verifFirstName;
        }
    } else {
        echo $verifLastName;
    }
} else {
    echo E_REGISTRATION1;
}

function verifName($name) {
    $space = 0;
    if(strlen($name) < 2 || strlen($name) > 50) {
        return E_REGISTRATION2;
    }
    for($i = 0; $i < strlen($name); $i++) {
        if(($name[$i] == ' ' || $name[$i] == '_') && ($i-$space >= 1)) {
            $space = $i;
            $i++;
        }
        if(($name[$i] < 'a' || $name[$i] > 'z') && ($name[$i] < 'A' || $name[$i] > 'Z') && ($name[$i] < 'ü' || $name[$i] > 'Ü')) {
            return E_REGISTRATION3;
        }
    }
    return "OK";
}

function verifPhoneNumber($phoneNumber) {
    return preg_match("#^\+?[0-9\./, -]{6,20}$#", $phoneNumber) > 0 ? "OK" : E_REGISTRATION4;
}

function verifAddress($address) {
    if(strlen($address) < 9 || strlen($address) > 140) {
        return E_REGISTRATION5;
    }
    $addressSplit = explode(' ',$address);
    if(count($addressSplit) < 3) {
        return E_REGISTRATION6;
    }
    if(is_numeric($addressSplit[0]) != true) {
        return E_REGISTRATION7;
    }
    for($i = 1; $i < count($addressSplit); $i++) {
        if(strlen($addressSplit[$i]) < 3) {
            return E_REGISTRATION8;
        }
        for($j = 0; $j < strlen($addressSplit[$i]); $j++) {
            if(($addressSplit[$i][$j] < 'a' || $addressSplit[$i][$j] > 'z') && ($addressSplit[$i][$j] < 'A' || $addressSplit[$i][$j] > 'Z') && ($addressSplit[$i][$j] < 'ü' || $addressSplit[$i][$j] > 'Ü')) {
                return E_REGISTRATION9;
            }
        }
    }
    return "OK";
}
?>
