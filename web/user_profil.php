<?php
//include('verif_user_profil.php');
session_start();
$connected = isset($_SESSION['email']) ? true : false;

// Si utilisateur déjà connecté => redirection page accueil
if($connected!=true){
    header('Location: connection.php');
    exit;
}

$user = $_SESSION['email'];
$passwd = $_SESSION['password'];

$context = stream_context_create(array(
    'http' => array(
        'header' => "Authorization: Basic " . base64_encode("$user:$passwd"))
));

if (!$_SESSION['clientID']) {
    $id = $_SESSION['providerID'];
    $json = file_get_contents("http://localhost/Conciergerie/API_TEST_URI/v1/prestataire", false, $context);

}else{
    $id = $_SESSION['clientID'];
    $json = file_get_contents("http://localhost/Conciergerie/API_TEST_URI/v1/client", false, $context);
}

$user_infos = json_decode($json, true);
//$agency = json_decode(file_get_contents("http://localhost/Conciergerie/API_TEST_URI/v1/agency/$id", true));
$agencies = json_decode(file_get_contents("http://localhost/Conciergerie/API_TEST_URI/v1/agency", false));

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="Projet Annuel">
    <link rel="stylesheet" type="text/css" href="CSS/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="CSS/CSS_Luxery.css">
    <script src="api_link.js" charset="utf-8"></script>
    <title>Orbis</title>
</head>
<body>
<?php require_once 'Pages/header.php'; ?>
    <main>
        <section class="body_section">
                <div id="user_profil_form">
                    <h1><?=_YOURINFORMATION?> :</h1>
                    <br/>
                    <div class="container">
                        <!-- Nom -->
                        <div class="row">
                            <div class="col">
                                <input type='text' class="form-control" placeholder="<?= _LASTNAME ?>" aria-label="lastname" aria-describedby="basic-addon1" value="<?= $user_infos[0]['lastName']; ?>" id="lastName" />
                            </div>
                            <div class="col">
                                <input type="button" class="co btn btn-secondary" onclick="send('lastName','lastName_res')" value="Modifier"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div id="lastName_res"></div>
                            </div>
                        </div>
                        <br/>
                        <!-- Prénom -->
                        <div class="row">
                            <div class="col">
                                <input type='text' class="form-control" placeholder="<?= _FIRSTNAME ?>" aria-label="firstname" aria-describedby="basic-addon1" value="<?= $user_infos[0]['firstName']; ?>" id="firstName" />
                            </div>
                            <div class="col">
                                <input type="button" class="co btn btn-secondary" onclick="send('firstName','firstName_res')" value="Modifier"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div id="firstName_res"></div>
                            </div>
                        </div>
                        <br/>
                        <!-- Email -->
                        <div class="row">
                            <div class="col">
                                <input type='email' class="form-control" placeholder="<?= _EMAIL ?>" aria-label="mail address" aria-describedby="basic-addon1" value="<?= $user_infos[0]['email']; ?>" size="30" id="email" />
                            </div>
                            <div class="col">
                                <input type="button" class="co btn btn-secondary" onclick="send('email','email_res')" value="Modifier"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div id="email_res"></div>
                            </div>
                        </div>
                        <br/>
                        <!-- Adresse -->
                        <div id="user_profil_address_and_num_div" class="row">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="<?= _ADDRESS ?>" aria-label="address" aria-describedby="basic-addon1" value="<?= $user_infos[0]['address']; ?>" id="address" />
                            </div>
                            <div class="col">
                                <input type="button" class="co btn btn-secondary" onclick="send('address','address_res')" value="Modifier"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div id="address_res"></div>
                            </div>
                        </div>
                        <br/>
                        <!-- Ville -->
                        <div class="row">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="<?= _CITY ?>" aria-label="town" aria-describedby="basic-addon1" value="<?= $user_infos[0]['city']; ?>" id="city" />
                            </div>
                            <div class="col">
                                <input type="button" class="co btn btn-secondary" onclick="send('city','city_res')" value="Modifier"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div id="city_res"></div>
                            </div>
                        </div>
                        <br/>
                        <!-- Numéro de téléphone -->
                        <div class="row">
                            <div class="col">
                                <input type="tel" class="form-control" placeholder="<?= _PHONENUMBER ?>" aria-label="num_tel" aria-describedby="basic-addon1" value="<?= $user_infos[0]['phoneNumber'];?>" id="phoneNumber" />
                            </div>
                            <div class="col">
                                <input type="button" class="co btn btn-secondary" onclick="send('phoneNumber','phoneNumber_res')" value="Modifier"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div id="phoneNumber_res"></div>
                            </div>
                        </div>
                        <br/>

                        <div class="row">
                            <input type='password' class="form-control" placeholder="Ancien mot de passe" aria-describedby="basic-addon1" id="old_password" />
                        </div>

                        <div class="row">
                            <input type='password' class="form-control" placeholder="Nouveau mot de passe" aria-describedby="basic-addon1" id="password" />
                        </div>

                        <div class="row">
                            <input type='password' class="form-control" placeholder="Confirmation mot de passe"  aria-describedby="basic-addon1"  id="passwd"/>
                        </div>

                        <input type="button" class="co btn btn-secondary" onclick="send('password','pwd_res')" value="Modifier le mot de passe"/><br/>
                        <div id="pwd_res"></div>
                    </div>
                </div>
        </section>
    </main>
<?php require_once 'Pages/footer.php'; ?>
</body>