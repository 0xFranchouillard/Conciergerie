<?php

include('config.php');
$bdd = connectionDB();

$d = date("Y-m-d");
$d7 = date('Y-m-d',(strtotime($d.' - 7 DAY')));
$m1 = date('Y-m-d',(strtotime($d.' - 1 MONTH')));


$utilisateurs = $bdd->query('SELECT * FROM client ORDER BY clientID DESC LIMIT 0,3');
$prestataires = $bdd->query('SELECT * FROM serviceprovider ORDER BY providerID DESC LIMIT 0,3');

$services = $bdd->query('SELECT * FROM service GROUP BY serviceID ORDER BY serviceID LIMIT 0,3');
$services_tariff = $bdd->query('SELECT * FROM service INNER JOIN tariff WHERE tariff.serviceID = service.serviceID GROUP BY tariffID ORDER BY service.serviceID DESC LIMIT 0,3');

$NBsubscriptions = $bdd->query('SELECT COUNT(subscribes.subscriptionID) as nb,SUM(subscription.pricePerYear/12) as sum FROM subscribes INNER JOIN subscription WHERE subscription.subscriptionID = subscribes.subscriptionID');

$NBbills = $bdd->query('SELECT COUNT(billID) as nb, SUM(billAmount) as sum FROM bill');
$NBbillsOK = $bdd->query('SELECT COUNT(billID) as nb, SUM(billAmount) as sum  FROM bill WHERE statutBill = 1');
$NBbillsWait = $bdd->query('SELECT COUNT(billID) as nb, SUM(billAmount) as sum FROM bill WHERE statutBill = 0');
$NBbillsWait1M = $bdd->prepare('SELECT COUNT(billID) as nb, SUM(billAmount) as sum FROM bill WHERE statutBill = 0 AND billDate NOT BETWEEN :today1monthlower AND :today ');
$NBbillsWait7D = $bdd->prepare('SELECT COUNT(billID) as nb, SUM(billAmount) as sum FROM bill WHERE statutBill = 0 AND billDate NOT BETWEEN :today7daylower AND :today');
$NBbillsWait7D->execute(array(':today7daylower'=>$d7,':today'=>$d));
$NBbillsWait1M->execute(array(':today1monthlower'=>$m1,':today'=>$d));

$interventions = $bdd->prepare('SELECT * FROM intervention ORDER BY interventionID DESC LIMIT 0,3');
$NBinterventionsOK = $bdd->prepare('SELECT COUNT(interventionID) as nb FROM intervention WHERE confirmIntervention = 1 ORDER BY interventionID DESC LIMIT 0,3');
$NBinterventionsWaitOrKO = $bdd->prepare('SELECT COUNT(interventionID) as nb FROM intervention WHERE confirmIntervention = 0 ORDER BY interventionID DESC LIMIT 0,3');

$NBbillsWait = $NBbillsWait->fetch();

foreach ($NBsubscriptions as $key => $value){
    echo $value["nb"];
    echo $value["sum"];
    echo '<br';
}


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

    <div class="DashCard" style="width: 450.667px; margin-top: 10px; margin-bottom: 10px; height: 224.444px;">
        <div class="Card bordered rounded flex flex-column hover-parent hover--visibility">
            <div class="flex-full flex flex-column"><div class="full-height full flex-wrap relative sc-bwzfXH gRDUuY">
                    <div class="Card-title absolute top right p1 px2"></div>
                    <div class="fullscreen-normal-text fullscreen-night-text text-brand-hover cursor-pointer" style="max-width: 100%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                        <span><h1 class="ScalarValue cursor-pointer text-brand-hover"><?=$NBbillsWait['nb']?></h1></span>
                    </div>
                    <div class="flex align-center full justify-center px2"><h3 class="Scalar-title overflow-hidden fullscreen-normal-text fullscreen-night-text text-brand-hover cursor-pointer">
                            <div class="" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">Nombre de commande WAIT</div></h3></div></div><span class="hide"></span><!-- react-empty: 3031 --></div></div></div>

    <div class="DashCard" style="width: 450.667px; margin-top: 10px; margin-bottom: 10px; height: 224.444px;">
        <div class="Card bordered rounded flex flex-column hover-parent hover--visibility">
            <div class="flex-full flex flex-column"><div class="full-height full flex-wrap relative sc-bwzfXH gRDUuY">
                    <div class="Card-title absolute top right p1 px2"></div>
                    <div class="fullscreen-normal-text fullscreen-night-text text-brand-hover cursor-pointer" style="max-width: 100%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                        <span><h1 class="ScalarValue cursor-pointer text-brand-hover"><?=$NBbillsWait['sum']?>€</h1></span>
                    </div>
                    <div class="flex align-center full justify-center px2"><h3 class="Scalar-title overflow-hidden fullscreen-normal-text fullscreen-night-text text-brand-hover cursor-pointer">
                            <div class="" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">Somme des commandes WAIT</div></h3></div></div><span class="hide"></span><!-- react-empty: 3031 --></div></div></div>

    <section class="corps_admin">
        <h1 class="center">--- RECHERCHE D'UTILISATEUR ---</h1>
        <br>
        <div class="container">
            <input type="search" class="co btn" id="recherche_user" name="recherche_user" onkeyup="search3('recherche_user','results')" placeholder="Rechercher...">
                    <select class="form-control search-slt" id="input_type" onchange="search3('recherche_user','results')">
                        <option>Prestataire</option>;
                        <option>Client</option>;
                    </select>
                    <br>
                    <div id="results"></div>
                    <br><br>
                    <h1 class="center">Gestion des Clients</h1>
                    <br>
                    <?php foreach($utilisateurs as  $user) {?>
                        <br/>
                        <div class="row" id="<?=$user['clientID']?><?= $user['agency']?>clientdata";>
                            <div class="col" id="<?=$user['clientID'].'data'?>" >
                                <?= $user['clientID'] ?> - <?= $user['lastName'] ?> <?= $user['firstName'] ?>
                            </div>
                            <div class="col-6">
                                <input type="button" class="co btn btn-secondary" value="Supprimer le compte"  onclick="suppression(<?=$user['clientID']?>,'<?= $user['agency']?>','client')"/>
                                <input type="button" class="co btn btn-secondary" onclick="modif_data('<?=$user['clientID'].$user["agency"].'client'?>')" value="Modifier les informations utilisateur"/><br/>
                            </div>
                            <div class="col" id="<?=$user['clientID'].$user["agency"].'client'?>" style="display:none">
                                <form  action="verif_admin.php?clientID=<?=$user['clientID']?>" method="post"><!-- form post + id_get -->
                                    <input type="hidden" name="agency" value="<?=$user["agency"]?>">
                                    <input type="text" name="nom_" value="<?=$user['lastName']?>" placeholder="Nouveau nom">
                                    <br>
                                    <input type="text" name="prenom_" value="<?=$user['firstName']?>" placeholder="Nouveau Prénom">
                                    <br>
                                    <input type="submit" value="Valider la Modification">
                                    <br>
                                </form>
                            </div>
                        </div>
                    <?php } ?> <!-- "}" du while -->
        </div>
        <br><br>
        <h1 class="center">Gestion des Prestataires</h1>
        <br>
        <?php foreach($prestataires as  $user) {?>
            <br/>
            <div class="row" id="<?=$user['providerID']?><?= $user['agency']?>prestatairedata";>
                <div class="col" id="<?=$user['providerID'].'data'?>" >
                    <?= $user['providerID'] ?> - <?= $user['lastName'] ?> <?= $user['firstName'] ?>
                </div>
                <div class="col-6">
                    <input type="button" class="co btn btn-secondary" value="Supprimer le compte"  onclick="suppression(<?=$user['providerID']?>,'<?= $user['agency']?>','prestataire')"/>
                    <input type="button" class="co btn btn-secondary" onclick="modif_data('<?=$user['providerID'].$user["agency"].'prestataire'?>')" value="Modifier les informations utilisateur"/><br/>
                </div>
                <div class="col" id="<?=$user['providerID'].$user["agency"].'prestataire'?>" style="display:none">
                    <form  action="verif_admin.php?providerID=<?=$user['providerID']?>" method="post"><!-- form post + id_get -->
                        <input type="hidden" name="agency" value="<?=$user["agency"]?>">
                        <input type="text" name="nom_" value="<?=$user['lastName']?>" placeholder="Nouveau nom">
                        <br>
                        <input type="text" name="prenom_" value="<?=$user['firstName']?>" placeholder="Nouveau Prénom">
                        <br>
                        <input type="submit" value="Valider la Modification">
                        <br>
                    </form>
                </div>
            </div>
        <?php } ?> <!-- "}" du while -->
        </div>

        <br><br>
        <h1 class="center">Gestion des Services</h1>
        <input type="button" class="co btn btn-secondary" value="Ajouter un service"  onclick="modif_data('add_service')"/>
        <div class="col" id="add_service" style="display:none">
            <form  action="verif_admin.php?addService=1" method="post"><!-- form post + id_get -->
                <input type="text" name="nameServiceFr" placeholder="Nom du Service Français">
                <input type="text" name="nameServiceEn" placeholder="Nom du Service Anglais">
                <input type="text" name="descriptionFr" placeholder="Description du Service Français">
                <input type="text" name="descriptionEn" placeholder="Description du Service Anglais">
                <input type="submit" value="Valider l'ajout">
                <br>
            </form>
        </div>
        <br>
        <input type="search" class="co btn" id="recherche_service" name="recherche_service" onkeyup="search3('recherche_service','service_results')" placeholder="Rechercher...">
        <br>
        <div id="service_results"></div>
        <br>
        <?php foreach($services as  $service) {?>
            <?php if($service['priceTypeService']==1) $s2 = "Facturation à la tâche"; elseif ($service['priceTypeService']==2) $s2 = "Facturation à l'heure"; else $s2 = "Facturation mixte";?>
            <?php if($service['priceTypeService']==2) $s3 = "Facturation à la tâche"; elseif ($service['priceTypeService']==0) $s3 = "Facturation à l'heure"; else $s3 = "Facturation mixte";?>

            <br/>
            <div class="row" id="<?=$service['serviceID']?><?= $service['language']?>servicedata";>
                <div class="col" id="<?=$service['serviceID'].'data'?>" >
                    <?= $service['serviceID'] ?> - <?= $service['nameService'] ?> <?php if($service['priceTypeService']==0) echo "Facturation à la tâche"; elseif ($service['priceTypeService']==1) echo "Facturation à l'heure"; else echo "Facturation mixte"; ?>
                </div>
                <div class="col-6">
                    <input type="button" class="co btn btn-secondary" value="Supprimer le service"  onclick="suppression(<?=$service['serviceID']?>,<?= $service['language']?>,'service')"/>
                    <input type="button" class="co btn btn-secondary" onclick="modif_data('<?=$service['serviceID'].'service'?>')" value="Modifier les informations du service"/><br/>
                </div>
                <div class="col" id="<?=$service['serviceID'].'service'?>" style="display:none">
                    <form  action="verif_admin.php?id=<?=$service['serviceID']?>" method="post"><!-- form post + id_get -->
                        <input type="text" name="nameServiceFr" value="<?=$service['nameServiceFr']?>" placeholder="Nouveau nom en français">
                        <input type="text" name="nameServiceEn" value="<?=$service['nameServiceEn']?>" placeholder="Nouveau nom en anglais">
                        <input type="text" name="descriptionFr" value="<?=$service['descriptionFr']?>" placeholder="Nouvelle description en français">
                        <input type="text" name="descriptionEn" value="<?=$service['descriptionEn']?>" placeholder="Nouvelle description en anglais">
                        <input type="submit" value="Valider la Modification">
                        <br>
                    </form>
                </div>
            </div>
        <?php } ?> <!-- "}" du while -->

        <br><br>
        <h1 class="center">Gestion des Services des Prestataires</h1>
        <br>
        <?php foreach($services_tariff as  $service) {?>
            <br/>
            <div class="row" id="<?=$service['tariffID']?><?= $service['language']?>service_prestatairedata";>
                <div class="col" id="<?=$service['tariffID'].'data'?>" >
                    <?= $service['tariffID'] ?> - <?= $service['startTime'] ?> <?= $service['endTime'] ?> <br><?= $service['nameService'] ?> <?= $service['priceService'] ?>€/<?= $service['priceTypeService']?>
                </div>
                <div class="col-6">
                    <input type="button" class="co btn btn-secondary" value="Supprimer le service"  onclick="suppression(<?=$service['tariffID']?>,<?= $service['language']?>,'service_prestataire')"/>
                </div>
            </div>
        <?php } ?> <!-- "}" du while -->
        </div>


    </section>
</main>