<?php
session_start();

function send_data($post)
{
    $data = http_build_query($post);
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];

    $context = stream_context_create(array(
            'http' => array(
                'method' => 'PUT',
                'header' => "Authorization: Basic ". base64_encode("$email:$password")."\r\nContent-type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($data) . "\r\n",
                'content' => $data,
            )
        )
    );
    if ($_SESSION['providerID'] != null) {
        $json = file_get_contents(
            'http://localhost/conciergerie/API_TEST_URI/v1/prestataire',
            FALSE, $context);
    }else{
        echo $json = file_get_contents(
            'http://localhost/conciergerie/API_TEST_URI/v1/client',
            FALSE, $context);
    }

    $user_infos = json_decode($json, true);
    if(!$_GET['password']){
        foreach ($user_infos as $key => $value) {

            if ($key == "false" || $key == "error")
                echo $GLOBALS['error'] .= $value . "</br>";
        elseif ($key == "valid") {
                echo $GLOBALS['valid'] .= $value . "</br>";
                if ($GLOBALS['email']) {
                    session_start();
                    $_SESSION['email'] = $GLOBALS['email'];
                }
            }
        }
    }else{

        foreach ($user_infos as $key => $value){
            if ($key == "false" || $key == "error")
                $GLOBALS['error_pwd'] .= $value . "</br>";
            elseif ($key == "valid") {
                $GLOBALS['valid_pwd'] .= $value . "</br>";
                session_start();
                $_SESSION['password'] = $post['password'];
            }
        }
    }
}

$post = array();
    if (isset($_GET['lastName']) && !empty($_GET['lastName'])) {

        $lastname = htmlspecialchars($_GET['lastName']);
        $post += ['lastName' => $lastname];

    }
    if (isset($_GET['firstName']) && !empty($_GET['firstName'])) {
        $firstName = htmlspecialchars($_GET['firstName']);
        $post += ['firstName' => $firstName];


    }
    if (isset($_GET['email']) && !empty($_GET['email'])) {
        $GLOBALS['email'] = htmlspecialchars($_GET['email']);
        $post += ['email' => $GLOBALS['email']];

    }
    if (isset($_GET['city']) && !empty($_GET['city'])) {
        $city = htmlspecialchars($_GET['city']);
        $post += ['city' => $city];

    }
    if (isset($_GET['address']) && !empty($_GET['address'])) {
        $address = htmlspecialchars($_GET['address']);
        $post += ['address' => $address];

    }
    if (isset($_GET['phoneNumber']) && !empty($_GET['phoneNumber'])) {
        $phoneNumber = htmlspecialchars($_GET['phoneNumber']);
        $post += ['phoneNumber' => "$phoneNumber"];
    }
    if (isset($_GET['agency']) && !empty($_GET['agency'])) {
        $phoneNumber = htmlspecialchars($_GET['agency']);
        $post += ['agency' => "$phoneNumber"];
    }

    send_data($post);

if(isset($_GET['old_password']) && !empty($_GET['old_password'])
    && isset($_GET['passwd']) && !empty($_GET['passwd'])
    && isset($_GET['password']) && !empty($_GET['password'])) {

    $password = hash('sha256',$_GET['password']);
    $passwd = hash('sha256',$_GET['passwd']);
    $old_password = hash('sha256',$_GET['old_password']);

    if ($passwd == $password) {

        if (!$_SESSION['clientID']) {
            $json = file_get_contents(
                'http://localhost/conciergerie/API_TEST_URI/v1/prestataire/'.$_SESSION['providerID'],
                FALSE, $context);
        }else{
            $json = file_get_contents(
                'http://localhost/conciergerie/API_TEST_URI/v1/client/'.$_SESSION['clientID'],
                FALSE, $context);
        }

        $user_infos = json_decode($json, true);
        if ($old_password == $user_infos[0]["password"]) {
            $post = ['password' => $password];
            $GLOBALS['put_pwd'] = true;
            send_data($post);
        }
    }
}
// ['data'][0]['iduser
?>
