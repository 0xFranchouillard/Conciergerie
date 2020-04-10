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
    <script src="JS/Ajax/connexion.js" charset="utf-8"></script>
    <?php require_once('Pages/header.php'); ?>
    <main>
        <!-- Connexion -->
		<section id="connexion" class="body_section">
			<h1><?=_CONNEXION?> :</h1>
			<br/>
			<form action="" method="POST">
				<div class="container">
                    <!-- Type de Compte -->
                    <div class="row">
                        <div class="col">
                            <select name="type" id="type">
                                <option><?=_CLIENT?></option>
                                <option><?=_PROVIDER?></option>
                            </select>
                        </div>
                    </div>
                    <!-- Email -->
					<div class="row">
						<div class="col">
							<input type="email" name="email" placeholder="<?=_EMAIL?>" id="email">
						</div>
					</div>
                    <!-- Mot de Passe -->
					<div class="row">
						<div class="col">
							<input type="password" name="password" placeholder="<?=_PASSWORD?>" id="password">
						</div>
					</div>
                    <!-- Buttons de Connection -->
					<div class="row">
						<div class="col">
                            <input type="button" name="connexion" value="<?=_CONNEXION?>" onclick="Connexion('<?= $_SESSION['lang'] ?>')">
						</div>
					</div>
				</div>
			</form>
            <!-- Erreur de connexion -->
            <h6 style="color: #b52626; display: none" id="error"></h6>
		</section>
        <!-- Inscription -->
		<section id="registration" class="body_section">
			<h1><?=_REGISTRATION?> :</h1>
			<br/>
			<form action="" method="post">
				<div class="container">
                    <!-- Nom -->
					<div class="row">
						<div class="col">
							<input type="text" name="lastName" placeholder="<?=_LASTNAME?>" id="lastName">
						</div>
					</div>
                    <!-- Prénom -->
					<div class="row">
						<div class="col">
							<input type="text" name="firstName" placeholder="<?=_FIRSTNAME?>" id="firstName">
						</div>
					</div>
                    <!-- Email -->
					<div class="row">
						<div class="col">
							<input type="email" name="email" placeholder="<?=_EMAIL?>" id="R_email">
						</div>
					</div>
                    <!-- Numéro de téléphone -->
					<div class="row">
						<div class="col">
							<input type="tel" name="phoneNumber" placeholder="<?=_PHONENUMBER?>" id="phoneNumber">
						</div>
					</div>
                    <!-- Adresse -->
					<div class="row">
						<div class="col">
							<input type="text" name="address" placeholder="<?=_ADDRESS?>" id="address">
						</div>
					</div>
                    <!-- Ville -->
                    <div class="row">
                        <div class="col">
                            <input type="text" name="city" placeholder="<?=_CITY?>" id="city">
                        </div>
                    </div>
                    <!-- Sélection agence -->
                    <div class="row">
                        <div class="col">
                        <select name="agency" id="agency">
                            <option>Choix de l'agence</option>
                            <?php
                            $file = file("Agency.txt");
                            for ($i = 0 ; $i < count($file) ; $i++){
                                echo '<option>'.$file[$i].'</option>';
                            }
                            ?>
                        </select>
                        </div>
                    </div>
                    <!-- Mot de Passe -->
					<div class="row">
						<div class="col">
							<input type="password" name="password" placeholder="<?=_PASSWORD?>" id="R_password">
						</div>
					</div>
                    <!-- Confirmation Mot de Passe -->
					<div class="row">
						<div class="col">
							<input type="password" name="confirmPassword" placeholder="<?=_PASSWORD2?>" id="confirmPassword">
						</div>
					</div>
                    <!-- Button d'Inscription -->
					<div class="row">
						<div class="col">
                            <input type="button" name="registration" value="<?=_REGISTRATION?>" onclick="Registration('<?= $_SESSION['lang'] ?>')">
						</div>
					</div>
				</div>
			</form>
            <!-- Erreur d'inscription -->
            <h6 style="color: #b52626; display: none" id="R_error"></h6>
		</section>
    </main>
    <?php require_once('Pages/footer.php'); ?>
    </body>
</html>
