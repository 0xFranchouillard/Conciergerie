<?php
session_start();
require_once('../lang/'.$_SESSION['lang'].'.php');
if(isset($_POST['serviceID']) && $_POST['serviceID']>= 0 &&
    isset($_POST['nbTake']) && !empty($_POST['nbTake'])) {
    if(intval($_POST['nbTake']) >= 0) {
        if (isset($_SESSION['serviceIDCart'])) {
            for ($i = 0; $i < count($_SESSION['serviceIDCart']); $i++) {
                if ($_SESSION['serviceIDCart'][$i] == $_POST['serviceID']) {
                    $_SESSION['nbTakeCart'][$i] += $_POST['nbTake'];
                    echo "OK";
                    exit;
                }
            }
            array_push($_SESSION['serviceIDCart'], $_POST['serviceID']);
            array_push($_SESSION['nbTakeCart'], $_POST['nbTake']);
        } else {
            $_SESSION['serviceIDCart'] = array();
            $_SESSION['nbTakeCart'] = array();

            $_SESSION['serviceIDCart'][0] = $_POST['serviceID'];
            $_SESSION['nbTakeCart'][0] = $_POST['nbTake'];
        }
        echo "OK";
    } else {
        echo _NEGATIVENUMBER;
    }
} else {
    echo "KO";
}
?>