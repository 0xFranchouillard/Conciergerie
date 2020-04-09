<?php

$utilisateur = json_decode(file_get_contents("http://localhost/Conciergerie/API_TEST_URI/v1/client/search", false));
$prestataire = json_decode(file_get_contents("http://localhost/Conciergerie/API_TEST_URI/v1/prestataire", false));

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="Projet Annuel">
    <link rel="stylesheet" type="text/css" href="../web/CSS/CSS_luxery.css">
    <link rel="stylesheet" type="text/css" href="../web/CSS/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <script src="../web/api_link.js" charset="utf-8"></script>

    <title>Administration Orbis</title>
</head>
<body>
<main id="main_admin" class="main_admin">
    <section class="corps_admin">
        <h1 class="center">--- RECHERCHE D'UTILISATEUR ---</h1>
        <br>
        <div class="container">

            <center><input type="search" class="co btn" id="recherche" name="recherche" onkeyup="search('recherche_user','results')" placeholder="Rechercher..."><center>
                    <select class="form-control search-slt" id="input_type" onchange="search('recherche','results')">
                        <option>Choix du type d'utilisateur</option>
                        <option>Prestataire</option>;
                        <option>Client</option>;
                    </select>
                    <br>
                    <div id="results"></div>
                    <br><br>
                    <h1 class="center">Gestion des Utilisateurs</h1>
                    <br>
                    <?php foreach($utilisateur as  $user) {?>
                        <br/>
                        <div class="row" id="<?=$user['clientID']?>";>
                            <div class="col" id="<?=$user['clientID'].'data'?>" >
                                <?= $user['id'] ?> - <?= $user['lastName'] ?> <?= $user['firstName'] ?>
                            </div>
                            <div class="col-6">
                                <input type="button" class="co btn btn-secondary" value="Supprimer le compte"  onclick="suppression_user(<?=$user['clientID']?>)"/>
                                <input type="button" class="co btn btn-secondary" onclick="modif_data('<?=$user['clientID'].'user'?>')" value="Modifier les informations utilisateur"/><br/>
                            </div>
                            <div class="col" id="<?=$user['clientID'].'user'?>" style="display:none">
                                <form  action="verif_admin.php?id=<?=$user['clientID']?>" method="post"><!-- form post + id_get -->
                                    <input type="text" name="nom_" placeholder="Nouveau nom">
                                    <br>
                                    <input type="text" name="prenom_" placeholder="Nouveau Prénom">
                                    <br>
                                    <input type="submit"  onclick="modif_user(<?=$user['clientID']?>)" value="Valider la Modification">
                                    <br>
                                </form>
                            </div>
                        </div>
                    <?php } ?> <!-- "}" du while -->
        </div>
    </section>
</main>
