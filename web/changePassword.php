<?php
session_start();
$connected = isset($_SESSION['email']) ? true : false;

// Si utilisateur déjà connecté => redirection page accueil
if($connected==true){
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="Projet Annuel">
    <link rel="stylesheet" type="text/css" href="CSS/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="CSS/CSS_Luxery.css">
    <title>LuxeryService</title>
</head>
<body>
<script src="JS/Ajax/changePassword.js" charset="utf-8"></script>
<?php require_once('Pages/header.php'); ?>
<main>
    <!-- Changer de mot de passe -->
    <section class="body_section">
        <h1><?=_CHANGEPASSWORD?> :</h1>
        <br/>
        <form action="" method="POST">
            <div class="container">
                <!-- Mot de passe -->
                <div class="row">
                    <div class="col">
                        <input type="password" name="password" placeholder="<?=_PASSWORD?>" id="password">
                    </div>
                </div>
                <!-- Confirmation mot de Passe -->
                <div class="row">
                    <div class="col">
                        <input type="password" name="password2" placeholder="<?=_PASSWORD2?>" id="password2">
                    </div>
                </div>
                <!-- Button de Confirmation -->
                <div class="row">
                    <div class="col">
                        <input type="button" name="confirm" value="<?=_CONFIRM?>" onclick="changePassword()">
                    </div>
                </div>
            </div>
        </form>
        <!-- Erreur changement mot de passe-->
        <h6 style="color: #b52626; display: none" id="error"></h6>
    </section>
</main>
<?php require_once('Pages/footer.php'); ?>
</body>
</html>
