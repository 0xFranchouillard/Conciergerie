<?php
include('verif_user_profil.php');

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
    <title>Orbis</title>
</head>
<body>
    <main>
        <section class="row d-flex justify-content-center">
            <div class="col-md-5 d-flex justify-content-center">
                <div id="user_profil_form">
                    <form method="POST" action="">
                        <h3 class="title_my_row">Vos informations</h3>
                        <div class="d-flex justify-content-center">
                            <div class="form-group row user_profil_input_row">
                                <div class="mx-auto user_profil_align">

                                    <div class="form-group display_input_inline_block">
                                        <input type='text' class="form-control" aria-label="firstname" aria-describedby="basic-addon1" value="<?= $user_infos[0]['firstName']; ?>" name="firstName" />
                                        <input type='text' class="form-control" aria-label="lastname" aria-describedby="basic-addon1" value="<?= $user_infos[0]['lastName']; ?>" name="lastName" />
                                    </div>

                                    <div class="form-group">
                                        <input type='text' class="form-control" aria-label="mail address" aria-describedby="basic-addon1" value="<?= $user_infos[0]['email']; ?>" size="30" name="email" />
                                    </div>

                                    <div id="user_profil_address_and_num_div" class="form-group">
                                        <input id="user_profil_address_input" type="text" class="form-control" aria-label="address" aria-describedby="basic-addon1" value="<?= $user_infos[0]['address']; ?>" name="address" />
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control input_247px" aria-label="town" aria-describedby="basic-addon1" value="<?= $user_infos[0]['city']; ?>" name="city" />
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control input_247px" placeholder="Numéro de téléphone" aria-label="num_tel" aria-describedby="basic-addon1" value="<?= $user_infos[0]['phoneNumber'];?>" name="phoneNumber" />
                                    </div>
                                    <?php if(isset($GLOBALS['error'])) { ?>
                                        <?= '<h6 style="color: #b52626">'.$GLOBALS['error'].'</h6>'; ?>
                                    <?php } ?>
                                    <?php if(isset($GLOBALS['valid'])) { ?>
                                        <?= '<h6 style="color: #00b504">' .$GLOBALS['valid'].'</h6>'; ?>
                                    <?php } ?>
                                    <?php if(isset($GLOBALS['false'])) { ?>
                                        <?= '<h6 style="color: #b3982e">' .$GLOBALS['false'].'</h6>'; ?>
                                    <?php } ?>
                                    <input class="btn btn-secondary" type="submit" name="update" value="Valider les changements" />
                                    <a href="ua_delete.php"><input class="btn btn-secondary" type="button" value="Supprimer son compte" /></a>

                                </div>
                            </div>
                        </div>
                    </form>

                    <form method="POST" action="">
                        <div class="d-flex justify-content-center">
                            <div class="form-group row user_profil_input_row">
                                <div class="mx-auto user_profil_align">

                                    <div class="form-group">
                                        <input type='password' class="form-control" placeholder="Ancien mot de passe" aria-describedby="basic-addon1" name="old_password" />
                                    </div>

                                    <div class="form-group">
                                        <input type='password' class="form-control" placeholder="Nouveau mot de passe" aria-describedby="basic-addon1" name="password" />
                                    </div>

                                    <div class="form-group">
                                        <input type='password' class="form-control" placeholder="Confirmation mot de passe"  aria-describedby="basic-addon1"  name="passwd"/>
                                    </div>
                                    <?php if(isset($GLOBALS['error_pwd'])) { ?>
                                        <?= '<h6 style="color: #b52626">'.$GLOBALS['error_pwd'].'</h6>'; ?>
                                    <?php } ?>
                                    <?php if(isset($GLOBALS['valid_pwd'])) { ?>
                                        <?= '<h6 style="color: #00b504">' .$GLOBALS['valid_pwd'].'</h6>'; ?>
                                    <?php } ?>
                                    <input class="btn btn-secondary" value="Modifier le mot de passe" type='submit' name="updatemdp" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
<?php
include 'Pages/footer.php';
?>