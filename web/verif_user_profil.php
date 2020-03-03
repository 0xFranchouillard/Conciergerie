<?php
session_start();

if (isset($_POST['update'])){

    if(isset($_POST['lastName']) && !empty($_POST['lastName'])
        && isset($_POST['firstName']) && !empty($_POST['firstName'])
        && isset($_POST['email']) && !empty($_POST['email'])
        //&& isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['passwd']) && !empty($_POST['passwd'])
        //&& isset($_POST['userFunction']) && !empty($_POST['userFunction'])
        && isset($_POST['city']) && !empty($_POST['city'])
        && isset($_POST['address']) && !empty($_POST['address'])
        && isset($_POST['phoneNumber']) && !empty($_POST['phoneNumber'])){
        //&& isset($_POST['qrCode']) && !empty($_POST['qrCode'])){

        $lastname = htmlspecialchars($_POST['lastName']);
        $firstName = htmlspecialchars($_POST['firstName']);
        $email = htmlspecialchars($_POST['email']);
        //$userFunction = htmlspecialchars($_POST['userFunction']);
        $city = htmlspecialchars($_POST['city']);
        $address = htmlspecialchars($_POST['address']);
        $phoneNumber = htmlspecialchars($_POST['phoneNumber']);
        //$qrCode = htmlspecialchars($_POST['qrCode']);
        //$userFunction = "1";
        $city = "PARIS";
        $qrCode = "bn";
        $hash = "bjk";
        $post = array(
            'lastName' => $lastname,
            'firstName' => $firstName,
            'email' => $email,
            //'password' => $password,
            //'userFunction' => $userFunction,
            'city' => $city,
            'address' => $address,
            'phoneNumber' => $phoneNumber
            //'qrCode' => $qrCode,
            //'hash' => $hash
        );
        $data = http_build_query($post);
        $context =stream_context_create(array(
                'http' => array(
                    'method' => 'PUT',
                    'header' => "Content-type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($data) . "\r\n",
                    'content' => $data,
                )
            )
        );
        $json = file_get_contents(
            'http://localhost/Conciergerie/API_TEST_URI/v1/users/'.$_SESSION['userID'],
            FALSE,$context);

        $user_infos = json_decode($json, true);

                foreach ($user_infos as $key => $value){
                    if ($key == "false")
                        $GLOBALS['false'] .= $value . "</br>";
                    elseif ($key == "error")
                        $GLOBALS['error'] .= $value . "</br>";
                    elseif ($key == "valid") {
                        $GLOBALS['valid'] .= $value . "</br>";
                        session_start();
                        $_SESSION['email'] = $email;
                    }
                }
                //echo '<h6 style="color: #b52626">'.$user_infos['error'].'</h6>';exit();



    }else{
        echo "NON OK";
    }
}elseif (isset($_POST['updatemdp'])
    && isset($_POST['old_password']) && !empty($_POST['old_password'])
    && isset($_POST['passwd']) && !empty($_POST['passwd'])
    && isset($_POST['password']) && !empty($_POST['password'])){

    $password = htmlspecialchars($_POST['password']);
    $passwd = htmlspecialchars($_POST['passwd']);
    $old_password = htmlspecialchars($_POST['old_password']);

    if ($passwd == $password) {

        $json = file_get_contents(
            'http://localhost/Conciergerie/API_TEST_URI/v1/users/' . $_SESSION['userID'],
            FALSE, $context);

        $user_infos = json_decode($json, true);
        if ($old_password == $user_infos[0]["password"]) {
            $post = ['password' => $password];

            $data = http_build_query($post);

            $context = stream_context_create(array(
                    'http' => array(
                        'method' => 'PUT',
                        'header' => "Content-type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($data) . "\r\n",
                        'content' => $data,
                    )
                )
            );

            $json = file_get_contents(
                'http://localhost/Conciergerie/API_TEST_URI/v1/users/' . $_SESSION['userID'],
                FALSE, $context);

            $user_infos = json_decode($json, true);
            foreach ($user_infos as $key => $value){
                if ($key == "error")
                    $GLOBALS['error_pwd'] .= $value . "</br>";
                elseif ($key == "valid") {
                    $GLOBALS['valid_pwd'] .= $value . "</br>";
                    session_start();
                    $_SESSION['password'] = $password;
                }
            }
        }
    }
}
// ['data'][0]['iduser
?>
