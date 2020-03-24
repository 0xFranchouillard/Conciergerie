<?php
include('verif_inscription.php');

session_start();
$connected = isset($_SESSION['email']) ? true : false;

// Si utilisateur déjà connecté => redirection page accueil
if($connected==true){
	header('Location: index.php');
	exit;
}
$agencies = json_decode(file_get_contents("http://localhost/Conciergerie/API_TEST_URI/v1/agency", false));
include('verif_connexion.php');

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
    <?php require_once('Pages/header.php'); ?>
    <main>
		<section id="connection" class="body_section">
			<h1>Connexion></h1>
			<br/>
			<form action="" method="POST">
				<div class="container">
                    <div class="row">
                        <div class="col">
                            <select name="type" id="type">
                                <option>client</option>
                                <option>prestataire</option>
                            </select>
                        </div>
                    </div>
					<div class="row">
						<div class="col">
							<input type="email" name="email" placeholder="identifiant" id="email">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<input type="password" name="password" placeholder="Mot de Passe" id="password">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<input type="submit" name="connection" value="Connexion">
						</div>
					</div>
				</div>
			</form>
            <?php if(isset($GLOBALS['error_connexion'])) { ?>
                <?= '<h6 style="color: #b52626">'.$GLOBALS['error_connexion'].'</h6>'; ?>
            <?php } ?>
		</section>
		<section id="registration" class="body_section">
			<h1>Inscription :</h1>
			<br/>
			<form action="" method="post">
				<div class="container">
					<div class="row">
						<div class="col">
							<input type="text" name="lastName" placeholder="Nom" id="lastName">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<input type="text" name="firstName" placeholder="Prénom" id="firstName">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<input type="email" name="email" placeholder="Adresse Email" id="email">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<input type="text" name="phoneNumber" placeholder="Numéro de téléphone" id="phoneNumber">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<input type="text" name="address" placeholder="Adresse" id="address">
						</div>
					</div>
                    <div class="row">
                        <div class="col">
                        <select name="agency" id="agency">
                            <option>Choix de l'agence</option>
                            <?php
                            for ($i = 0 ; $i < count($agencies) ; $i++){
                                if($agencies[$i][0] != "")
                                    echo '<option>'.$agencies[$i][0].'</option>';
                            }
                            ?>
                        </select>
                        </div>
                    </div>
					<div class="row">
						<div class="col">
							<input type="password" name="password" placeholder="Mot de Passe" id="password">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<input type="password" name="pwd" placeholder="Confirmer mot de Passe" id="pwd">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<input type="submit" name="registration" value="S'inscrire">
						</div>
					</div>
                    <?php if(isset($GLOBALS['error_registration'])) { ?>
                        <?= '<h6 style="color: #b52626">'.$GLOBALS['error_registration'].'</h6>'; ?>
                    <?php } ?>
                    <?php if(isset($GLOBALS['valid_registration'])) { ?>
                        <?= '<h6 style="color: #0eb502">' .$GLOBALS['valid_registration'].'</h6>'; ?>
                    <?php } ?>
				</div>
			</form>
		</section>
    </main>
    <?php require_once('Pages/footer.php'); ?>
    </body>
</html>
