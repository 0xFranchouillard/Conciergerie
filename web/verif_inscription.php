<?php

if (isset($_POST['registration'])){
    if(isset($_POST['lastName']) && !empty($_POST['lastName'])
    && isset($_POST['firstName']) && !empty($_POST['firstName'])
    && isset($_POST['email']) && !empty($_POST['email'])
    && isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['pwd']) && !empty($_POST['pwd'])
    //&& isset($_POST['userFunction']) && !empty($_POST['userFunction'])
    //&& isset($_POST['city']) && !empty($_POST['city'])
    && isset($_POST['address']) && !empty($_POST['address'])
    && isset($_POST['phoneNumber']) && !empty($_POST['phoneNumber'])){
    //&& isset($_POST['qrCode']) && !empty($_POST['qrCode'])){

        $lastname = htmlspecialchars($_POST['lastName']);
        $firstName = htmlspecialchars($_POST['firstName']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $pwd = htmlspecialchars($_POST['pwd']);
        //$userFunction = htmlspecialchars($_POST['userFunction']);
        //$city = htmlspecialchars($_POST['city']);
        $address = htmlspecialchars($_POST['address']);
        $phoneNumber = htmlspecialchars($_POST['phoneNumber']);
        //$qrCode = htmlspecialchars($_POST['qrCode']);
        $userFunction = "1";
        $city = "PARIS";
        $qrCode = "bn";
        $hash = "bjk";
        $post = array(
            'lastName' => $lastname,
            'firstName' => $firstName,
            'email' => $email,
            'password' => $password,
            'userFunction' => $userFunction,
            'city' => $city,
            'address' => $address,
            'phoneNumber' => $phoneNumber,
            'qrCode' => $qrCode,
            'hash' => $hash
        );
        $data = http_build_query($post);

        $context =stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($data) . "\r\n",
                'content' => $data,
            )
            )
        );
        $json = file_get_contents(
            'http://localhost/Conciergerie/API_TEST_URI/v1/users',
            FALSE,$context);

        $user_infos = json_decode($json, true);

            foreach ($user_infos as $key => $value){
                if($key == "error") {
                    $GLOBALS['error_registration'] .= $value . "</br>";
                    $GLOBALS['valid_registration'] .= $key . "</br>";

                }else{
                    $GLOBALS['valid_registration'] .= $lastname. "'s Account has been created</br>";
                    sleep(2);
                    session_start();
                    $_SESSION["useID"] = $value;
                    $_SESSION["email"] = $email;
                    $_SESSION["password"] = $password;
                    $_SESSION["valid_mail"] = 0;
                }
        }
        /*
        $url = 'http://localhost/Conciergerie/API_TEST_URI/v1/users';

        $postdata = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, "Content-type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($data) . "\r\n");
        $result = curl_exec($ch);
        curl_close($ch);
        print_r ($result);
*/

    }else{
        echo "NON OK";
}
}

// ['data'][0]['iduser
?>
