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
		<link rel="stylesheet" type="text/css" href="CSS/CSS_luxery.css">
		<title>Orbis</title>
	</head>
	<body>
    <?php require_once('Pages/header.php'); ?>
    <main>
		<section id="connection" class="body_section">
			<h1>Connexion :</h1>
			<br/>
			<form action="" method="post">
				<div class="container">
					<div class="row">
						<div class="col">
							<input type="email" name="email_connection" placeholder="identifiant" id="email_connection">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<input type="password" name="mdp_connection" placeholder="Mot de Passe" id="mdp_connection">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<input type="submit" name="connection" value="Connexion">
						</div>
					</div>
					<?php if(isset($error_connection)) { ?>
						<div class="row">
							<div class="col">
								<?php echo '<h6 style="color: #b52626">'.$error_connection.'</h6>'; ?>
							</div>
						</div>
					<?php } ?>
				</div>
			</form>
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
							<input type="text" name="telephone" placeholder="Numéro de téléphone" id="telephone">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<input type="text" name="address" placeholder="Adresse" id="address">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<input type="password" name="mdp" placeholder="Mot de Passe" id="mdp">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<input type="password" name="mdp2" placeholder="Confirmer mot de Passe" id="mdp2">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<input type="submit" name="registration" value="S'inscrire">
						</div>
					</div>
					<?php if(isset($error_registration)) { ?>
						<div class="row">
							<div class="col">
								<?php echo '<h6 style="color: #b52626">'.$error_registration.'</h6>'; ?>
							</div>
						</div>
					<?php } ?>
				</div>
			</form>
		</section>
    </main>
    <?php require_once('Pages/footer.php'); ?>
    </body>
</html>
