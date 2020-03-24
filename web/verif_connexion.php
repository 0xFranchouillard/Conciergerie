<?php
if (isset($_POST['connection']) && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && !empty($_POST['password'])) {
        $user = $_POST['email'];
        $passwd = hash('sha256', $_POST['password']);

    $context = stream_context_create(array(
            'http' => array(
                'header' => "Authorization: Basic " . base64_encode("$user:$passwd"))
        ));
        $json = file_get_contents("http://localhost/conciergerie/API_TEST_URI/v1/".$_POST['type'], true, $context);
        $user_infos = json_decode($json, true);

        if ($user_infos[0]['email'] != NULL) {
            session_start();
            $_SESSION['email'] = $user_infos[0]['email'];
            $_SESSION['agency'] = $user_infos[0]['agency'];
            if ($_POST['type'] == "client") {
                $_SESSION['clientID'] = $user_infos[0]['clientID'];
            }else{
                $_SESSION['providerID'] = $user_infos[0]['providerID'];
            }
            $_SESSION['password'] = $user_infos[0]['password'];
            header('Location: connection.php?dz');
        } elseif ($user_infos['error'] != NULL)
            $GLOBALS['error_connexion'] = $user_infos['error'];
}



// ['data'][0]['iduser']
?>