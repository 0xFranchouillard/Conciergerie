<?php
require_once('../lang/'.$_POST['lang'].'.php');
require_once ('../Pages/db.php');
$db = connectionDB();

if(isset($_POST['lastName']) && !empty($_POST['lastName']) &&
    isset($_POST['firstName']) && !empty($_POST['firstName']) &&
    isset($_POST['email']) && !empty($_POST['email']) &&
    isset($_POST['phoneNumber']) && !empty($_POST['phoneNumber']) &&
    isset($_POST['address']) && !empty($_POST['address']) &&
    isset($_POST['city']) && !empty($_POST['city']) &&
    isset($_POST['agency']) && !empty($_POST['agency']) &&
    isset($_POST['password']) && !empty($_POST['password']) &&
    isset($_POST['confirmPassword']) && !empty($_POST['confirmPassword'])) {

    $verifLastName = verifName($_POST['lastName']);
    $verifFirstName = verifName($_POST['firstName']);
    $verifCity = verifName($_POST['city']);
    $verifPhoneNumber = verifPhoneNumber($_POST['phoneNumber']);
    $verifAddress = verifAddress($_POST['address']);
    $verifPassword = verifPassword($_POST['password']);

    if($verifLastName == "OK") {
        if($verifFirstName == "OK") {
            if($verifCity == "OK") {
                if($verifPhoneNumber == "OK") {
                    if($verifAddress == "OK") {
                        if($verifPassword == "OK") {
                            if($_POST['password'] == $_POST['confirmPassword']) {
                                if($_POST['agency'] != _AGENCY) {
                                    if(strlen($_POST['email']) <= 140) {
                                        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                                            $request = $db->prepare('SELECT email FROM client WHERE email= :email');
                                            $request->execute([
                                                'email' => htmlspecialchars($_POST['email'])
                                            ]);
                                            $result = $request->rowCount();
                                            if ($result == 0) {

                                                $request = $db->prepare('SELECT clientID FROM client WHERE clientID= :id && agency= :agency');
                                                $find = false;
                                                $id = 1;
                                                while (!$find) {
                                                    $request->execute([
                                                        'id' => $id,
                                                        'agency' => $_POST['agency']
                                                    ]);
                                                    $n_id = $request->rowCount();
                                                    if ($n_id != 0) {
                                                        $id++;
                                                    } else {
                                                        $find = true;
                                                    }
                                                }
                                                date_default_timezone_set('Europe/Paris');

                                                $request = $db->prepare('INSERT INTO client(clientID, agency, lastName, firstName, email, phoneNumber, password, address, city, registrationDate) VALUES(:id, :agency, :lastName, :firstName, :email, :phoneNumber, :password, :address, :city, :registrationDate)');
                                                $request->execute([
                                                    'id' => $id,
                                                    'agency' => htmlspecialchars($_POST['agency']),
                                                    'lastName' => htmlspecialchars($_POST['lastName']),
                                                    'firstName' => htmlspecialchars($_POST['firstName']),
                                                    'email' => htmlspecialchars($_POST['email']),
                                                    'phoneNumber' => htmlspecialchars($_POST['phoneNumber']),
                                                    'password' => hash('sha256', $_POST['password']),
                                                    'address' => htmlspecialchars($_POST['address']),
                                                    'city' => htmlspecialchars($_POST['city']),
                                                    'registrationDate' => date('Y-m-d')
                                                ]);
                                                echo "OK ".OK_REGISTRATION;

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
                                    echo E_REGISTRATION12;
                                }
                            } else {
                                echo E_REGISTRATION11;
                            }
                        } else {
                            echo $verifPassword;
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
        if(strlen($addressSplit[$i]) < 2) {
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

function verifPassword($password) {
    $nbNumber = 0;
    $nbMaj = 0;
    $nbMin = 0;
    for($i = 0; $i < strlen($password); $i++) {
        if($password[$i] >= 'A' && $password[$i] <= 'Z') {
            $nbMaj++;
        }
        if($password[$i] >= 'a' && $password[$i] <= 'z') {
            $nbMin++;
        }
        if(is_numeric($password[$i]) == true) {
            $nbNumber++;
        }
    }
    if($nbMaj < 2 || $nbNumber < 2 || $nbMin < 4) {
        return E_REGISTRATION10;
    }
    return "OK";
}
?>