<?php
if (isset($_POST['connection']) && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && !empty($_POST['password'])) {
    $user = $_POST['email'];
    $passwd = $_POST['password'];
    $context = stream_context_create(array(
        'http' => array(
            'header' => "Authorization: Basic " . base64_encode("$user:$passwd"))
    ));
    $json = file_get_contents("http://localhost/Conciergerie/API_TEST_URI/v1/users", false, $context);
    $user_infos = json_decode($json, true);
    if ($user_infos[0]['email'] != NULL) {
        session_start();
        $_SESSION['email'] = $user_infos[0]['email'];
        $_SESSION['userID'] = $user_infos[0]['userID'];
        $_SESSION['password'] = $user_infos[0]['password'];
        header('Location: connection.php');
    }elseif ($user_infos['error'] != NULL)
        echo '<h6 style="color: #b52626">'.$user_infos['error'].'</h6>';
}



// ['data'][0]['iduser']
?>