<?php
//include('verif_user_profil.php');

session_start();
$connected = isset($_SESSION['email']) ? true : false;

include 'Pages/header.php';
include 'Pages/connection_DB.php';

$user = $_SESSION['email'];
$passwd = $_SESSION['password'];

$context = stream_context_create(array(
    'http' => array(
        'header' => "Authorization: Basic " . base64_encode("$user:$passwd"))
));

$json = file_get_contents("http://localhost/Conciergerie/API_TEST_URI/v1/users", false, $context);
$user_infos = json_decode($json, true);

?>
    <!DOCTYPE html>
    <html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="Projet Annuel">
    <link rel="stylesheet" type="text/css" href="CSS/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="CSS/CSS_luxery.css">
    <script src="api_link.js" charset="utf-8"></script>
    <title>Orbis</title>
</head>
<body>
    <main>
        <section class="row d-flex justify-content-center">
            <div class="col-md-5 d-flex justify-content-center">
                <div id="user_profil_form">
                        <h3 class="title_my_row">Vos informations</h3>
                        <div class="d-flex justify-content-center">
                            <div class="form-group row user_profil_input_row">
                                <div class="mx-auto user_profil_align">

                                    <div class="form-group display_input_inline_block">
                                        <input type='text' class="form-control" aria-label="firstname" aria-describedby="basic-addon1" value="<?= $user_infos[0]['firstName']; ?>" id="firstName" />
                                        <input type="button" class="co btn btn-secondary" onclick="send('firstName','firstName_res')" value="Modifier"/><br/>
                                        <div id="firstName_res"></div>

                                        <input type='text' class="form-control" aria-label="lastname" aria-describedby="basic-addon1" value="<?= $user_infos[0]['lastName']; ?>" id="lastName" />
                                        <input type="button" class="co btn btn-secondary" onclick="send('lastName','lastName_res')" value="Modifier"/><br/>
                                        <div id="lastName_res"></div>
                                    </div>

                                    <div class="form-group">
                                        <input type='text' class="form-control" aria-label="mail address" aria-describedby="basic-addon1" value="<?= $user_infos[0]['email']; ?>" size="30" id="email" />
                                        <input type="button" class="co btn btn-secondary" onclick="send('email','email_res')" value="Modifier"/><br/>
                                        <div id="email_res"></div>

                                    </div>

                                    <div id="user_profil_address_and_num_div" class="form-group">
                                        <input id="user_profil_address_input" type="text" class="form-control" aria-label="address" aria-describedby="basic-addon1" value="<?= $user_infos[0]['address']; ?>" id="address" />
                                        <input type="button" class="co btn btn-secondary" onclick="send('address','address_res')" value="Modifier"/><br/>
                                        <div id="address_res"></div>

                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control input_247px" aria-label="town" aria-describedby="basic-addon1" value="<?= $user_infos[0]['city']; ?>" id="city" />
                                        <input type="button" class="co btn btn-secondary" onclick="send('city','city_res')" value="Modifier"/><br/>
                                        <div id="city_res"></div>

                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control input_247px" placeholder="Numéro de téléphone" aria-label="num_tel" aria-describedby="basic-addon1" value="<?= $user_infos[0]['phoneNumber'];?>" id="phoneNumber" />
                                        <input type="button" class="co btn btn-secondary" onclick="send('phoneNumber','phoneNumber_res')" value="Modifier"/><br/>
                                        <div id="phoneNumber_res"></div>
                                    </div>
                                    <input type="button" class="co btn btn-secondary" onclick="delete()" value="Modifier"/><br/>

                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <div class="form-group row user_profil_input_row">
                                <div class="mx-auto user_profil_align">

                                    <div class="form-group">
                                        <input type='password' class="form-control" placeholder="Ancien mot de passe" aria-describedby="basic-addon1" id="old_password" />
                                    </div>

                                    <div class="form-group">
                                        <input type='password' class="form-control" placeholder="Nouveau mot de passe" aria-describedby="basic-addon1" id="password" />
                                    </div>

                                    <div class="form-group">
                                        <input type='password' class="form-control" placeholder="Confirmation mot de passe"  aria-describedby="basic-addon1"  id="passwd"/>
                                    </div>

                                    <input type="button" class="co btn btn-secondary" onclick="send('password','pwd_res')" value="Modifier le mot de passe"/><br/>
                                    <div id="pwd_res"></div>

                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </section>
    </main>
</body>
<?php
include 'Pages/footer.php';
?>