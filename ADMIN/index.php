<?php

ini_set('display_errors',1);
include('config.php');
$bdd = connectionDB();
echo "<br>";
$d = date("Y-m-d");
$d7 = date('Y-m-d',(strtotime($d.' - 7 DAY')));
$m1 = date('Y-m-d',(strtotime($d.' - 1 MONTH')));

$utilisateurs = $bdd->query('SELECT * FROM client ORDER BY clientID DESC LIMIT 0,3');
$prestataires = $bdd->query('SELECT * FROM serviceprovider ORDER BY providerID DESC LIMIT 0,3');
$nbClient = $bdd->query('SELECT COUNT( * )  as nb FROM client')->fetch();
$nbPresta = $bdd->query('SELECT COUNT( * ) as nb FROM serviceprovider')->fetch();

$services = $bdd->query('SELECT * FROM service GROUP BY serviceID ORDER BY serviceID LIMIT 0,3');
$services_tariff = $bdd->query('SELECT * FROM service INNER JOIN tariff ON tariff.serviceID = service.serviceID INNER JOIN validitydate ON validitydate.dateID = tariff.dateID GROUP BY tariffID ORDER BY tariff.tariffID DESC LIMIT 0,3');
$subscriptions = $bdd->query('SELECT * FROM subscription GROUP BY subscriptionID ORDER BY subscriptionID');

$NBsubscriptions = $bdd->query('SELECT COUNT(subscribes.subscriptionID) as nb,SUM(subscription.pricePerYear/12) as sum FROM subscribes INNER JOIN subscription WHERE subscription.subscriptionID = subscribes.subscriptionID');

$NBbills = $bdd->query('SELECT COUNT(billID) as nb, SUM(totalPrice) as sum, AVG(totalPrice) as avg FROM bill')->fetch();
$NBbills1M = $bdd->prepare('SELECT COUNT(billID) as nb, SUM(totalPrice) as sum, AVG(totalPrice) as avg FROM bill WHERE billDate BETWEEN :today1monthlower AND :today');
$NBbills7D = $bdd->prepare('SELECT COUNT(billID) as nb, SUM(totalPrice) as sum, AVG(totalPrice) as avg FROM bill WHERE billDate BETWEEN :today7daylower AND :today');
$NBbills7D->execute(array(':today7daylower'=>$d7,':today'=>$d));
$NBbills1M->execute(array(':today1monthlower'=>$m1,':today'=>$d));

$interventions = $bdd->query('SELECT * FROM intervention ORDER BY interventionID DESC LIMIT 0,3')->fetch();
$NBinterventionsOK = $bdd->query('SELECT COUNT(interventionID) as nb FROM intervention WHERE statutIntervention = 1')->fetch();
$NBinterventionsWAIT = $bdd->query('SELECT COUNT(interventionID) as nb FROM intervention WHERE statutIntervention = 0')->fetch();

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
    <div class="row">
        <?php if ($NBbills7D->rowCount() >= 1) { $NBbills7D = $NBbills7D->fetch(); ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Earnings(Weekly)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$NBbills7D['sum']?>€</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($NBbills1M->rowCount() >= 1) { $NBbills1M_ = $NBbills1M->fetch(); ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Earnings(Monthly)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$NBbills1M_['sum']?>€</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($NBinterventionsOK['nb'] != "" && $NBinterventionsWAIT['nb'] != "") {?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Intervention ratio</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?=$NBinterventionsOK['nb']+$NBinterventionsWAIT['nb']?></div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo (($NBinterventionsOK['nb']/$NBinterventionsWAIT['nb'])*100)?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($nbClient['nb'] != "" && $nbPresta['nb'] != "") {?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Client | Service provider Ratio</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $nbClient['nb']." | ".$nbPresta['nb'];  ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
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
            <?php foreach($utilisateurs as  $user) {
                $i = $bdd->prepare('SELECT interventionID FROM intervention WHERE clientID = :id');
                $i->execute(array(':id'=>$user['clientID']));
                ?>
                <br/>
                <div class="row" id="<?=$user['clientID']?><?= $user['agency']?>clientdata";>
                    <div class="col" id="<?=$user['clientID'].'data'?>" >
                        <?= $user['clientID'] ?> - <?= $user['lastName'] ?> <?= $user['firstName'] ?>
                    </div>
                    <div class="col-6">
                        <input type="button" class="co btn btn-secondary" value="Supprimer le compte"  onclick="suppression(<?=$user['clientID']?>,'<?= $user['agency']?>','client')"/>
                        <input type="button" class="co btn btn-secondary" onclick="modif_data('<?=$user['clientID'].$user["agency"].'client'?>')" value="Modifier les informations utilisateur"/><br/>
                        <?php if($i->rowCount() > 0) { ?>
                            <input type="button" value="Exporter les interventions" OnClick="window.location.href='recherche_intervention?clientID=<?=$user['clientID']?>&agency=<?=$user['agency']?>'" readonly  />
                        <?php } ?>

                    </div>
                    <div class="col" id="<?=$user['clientID'].$user["agency"].'client'?>" style="display:none">
                        <form  action="verif_admin.php?clientID=<?=$user['clientID']?>" method="post"><!-- form post + id_get -->
                            <input type="hidden" name="agency" value="<?=$user["agency"]?>">
                            <input type="text" name="nom_" value="<?=$user['lastName']?>" placeholder="Nouveau nom">
                            <br>
                            <input type="text" name="prenom_" value="<?=$user['firstName']?>" placeholder="Nouveau Prénom">
                            <br>
                            <select name="newagency" id="newagency">
                                <option><?=$user['agency']?></option>
                                <?php
                                $file = file("../web/Agency.txt");
                                for ($i = 0 ; $i < count($file) ; $i++){
                                    echo '<option>'.$file[$i].'</option>';
                                }
                                ?>
                            </select>
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
        <?php foreach($prestataires as  $user) {
            $i = $bdd->prepare('SELECT interventionID FROM intervention WHERE providerID = :id');
            $i->execute(array(':id'=>$user['providerID']));
            ?>
            <br/>
            <div class="row" id="<?=$user['providerID']?><?= $user['agency']?>prestatairedata";>
                <div class="col" id="<?=$user['providerID'].'data'?>" >
                    <?= $user['providerID'] ?> - <?= $user['lastName'] ?> <?= $user['firstName'] ?>
                </div>
                <div class="col-6">
                    <input type="button" class="co btn btn-secondary" value="Supprimer le compte"  onclick="suppression(<?=$user['providerID']?>,'<?= $user['agency']?>','prestataire')"/>
                    <input type="button" class="co btn btn-secondary" onclick="modif_data('<?=$user['providerID'].$user["agency"].'prestataire'?>')" value="Modifier les informations utilisateur"/><br/>
                    <?php if($i->rowCount() > 0) { ?>
                        <input type="button" value="Exporter les interventions" OnClick="window.location.href='recherche_intervention?providerID=<?=$user['providerID']?>&agency=<?=$user['agency']?>'" readonly  />
                    <?php } ?>
                </div>
                <div class="col" id="<?=$user['providerID'].$user["agency"].'prestataire'?>" style="display:none">
                    <form  action="verif_admin.php?providerID=<?=$user['providerID']?>" method="post"><!-- form post + id_get -->
                        <input type="hidden" name="agency" value="<?=$user["agency"]?>">
                        <input type="text" name="nom_" value="<?=$user['lastName']?>" placeholder="Nouveau nom">
                        <br>
                        <input type="text" name="prenom_" value="<?=$user['firstName']?>" placeholder="Nouveau Prénom">
                        <br>
                        <select name="newagency" id="newagency">
                            <option><?=$user['agency']?></option>
                            <?php
                            $file = file("../web/Agency.txt");
                            for ($i = 0 ; $i < count($file) ; $i++){
                                echo '<option>'.$file[$i].'</option>';
                            }
                            ?>
                        </select>
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
                <input type="number" name="priceService" placeholder="Prix">
                <input type="number" name="priceRecurrentService" placeholder="Prix récurrent">
                <input type="number" name="minimumType" placeholder="Minimum">
                <select class="form-control search-slt" name="priceTypeService" id="priceTypeService" name="priceTypeService"">
                <option>Facturation à la tâche</option>
                <option>Facturation à l'heure</option>
                <option>Facturation mixte</option>
                </select>
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
            <?php
            if($service['priceTypeService']=="Facturation à la tâche") $s2 = "Facturation à l'heure"; elseif ($service['priceTypeService']=="Facturation à l'heure") $s2 = "Facturation mixte"; else $s2 = "Facturation à l'heure";
            if($service['priceTypeService']=="Facturation à la tâche") $s3 = "Facturation mixte"; elseif ($service['priceTypeService']=="Facturation mixte") $s3 = "Facturation à l'heure"; else $s3 = "Facturation mixte";
            ?>
            <br/>
            <div class="row" id="<?=$service['serviceID']?><?= $service['language']?>servicedata";>
                <div class="col" id="<?=$service['serviceID'].'data'?>" >
                    <?= $service['serviceID'] ?> - <?= $service['nameService'] ?> <?=($service['priceTypeService'])?>
                </div>
                <div class="col-6">
                    <input type="button" class="co btn btn-secondary" value="Supprimer le service"  onclick="suppression(<?=$service['serviceID']?>,'<?= $service['language']?>','service')"/>
                    <input type="button" class="co btn btn-secondary" onclick="modif_data('<?=$service['serviceID'].'service'?>')" value="Modifier les informations du service"/><br/>
                </div>
                <div class="col" id="<?=$service['serviceID'].'service'?>" style="display:none">
                    <form  action="verif_admin.php?id=<?=$service['serviceID']?>" method="post"><!-- form post + id_get -->
                        <input type="text" name="nameServiceFr" placeholder="Nouveau nom en français">
                        <input type="text" name="nameServiceEn" value="<?=$service['nameService']?>" placeholder="Nouveau nom en anglais">
                        <input type="text" name="descriptionFr" placeholder="Nouvelle description en français">
                        <input type="text" name="descriptionEn" value="<?=$service['description']?>" placeholder="Nouvelle description en anglais">
                        <input type="number" name="priceService" value="<?=$service['priceService']?>" placeholder="Prix">
                        <input type="number" name="priceRecurrentService" value="<?=$service['priceRecurrentService']?>" placeholder="Prix récurrent">
                        <input type="number" name="minimumType" value="<?=$service['minimumType']?>" placeholder="Minimum">
                        <select class="form-control search-slt" name="priceTypeService" id="priceTypeService" name="priceTypeService"">
                        <option><?=$service['priceTypeService']?></option>
                        <option><?=$s2?></option>
                        <option><?=$s3?></option>
                        </select>
                        <input type="submit" value="Valider la Modification">
                        <br>
                    </form>
                </div>
            </div>
        <?php } ?> <!-- "}" du while -->

        <br><br>
        <h1 class="center">--- RECHERCHE DE PRESTATION DE SERVICE ---</h1>
        <br>
        <div class="container">
            <input type="search" class="co btn" id="recherche_prestation" name="recherche_prestation" onkeyup="search3('recherche_prestation','prestation_results')" placeholder="Rechercher...">
            <select class="form-control search-slt" id="input_type_prestatation" onchange="search3('recherche_prestation','prestation_results')">
                <option>Service</option>;
                <option>Prestataire</option>;
            </select>
            <br>
            <div id="prestation_results"></div>
            <h1 class="center">Gestion des Services des Prestataires</h1>

            <?php foreach($services_tariff as  $service_presta) {?>
                <br/>
                <div class="row" id="<?=$service_presta['tariffID']?><?= $service_presta['language']?>service_prestatairedata";>
                    <div class="col" id="<?=$service_presta['tariffID'].'data'?>" >
                        <?= $service_presta['tariffID'] ?> - <?= $service_presta['startTime'] ?> <?= $service_presta['endTime'] ?> <br><?= $service_presta['nameService'] ?> <?= $service_presta['priceService'] ?>€/<?= $service_presta['priceTypeService']?>
                    </div>
                    <div class="col-6">
                        <input type="button" class="co btn btn-secondary" value="Supprimer le service"  onclick="suppression(<?=$service_presta['tariffID']?>,'<?= $service_presta['language']?>','service_prestataire')"/>
                    </div>
                </div>
            <?php } ?> <!-- "}" du while -->
        </div>

        <br><br>
        <h1 class="center">Gestion des Offres d'abonnement</h1>
        <input type="button" class="co btn btn-secondary" value="Ajouter un service"  onclick="modif_data('add_subscription')"/>
        <div class="col" id="add_subscription" style="display:none">
            <form  action="verif_admin.php?addSubscription=1" method="post"><!-- form post + id_get -->
                <input type="text" name="nameSubscriptionFr" placeholder="Nom de l'abonnement Français">
                <input type="text" name="nameSubscriptionEn" placeholder="Nom de l'abonnement Anglais">
                <input type="number" name="nbDays" placeholder="Nombre de jour par semaine Xj/7">
                <input type="time" name="startTime" placeholder="Heure de début">
                <input type="time" name="endTime" placeholder="Heure de fin">
                <input type="number" name="pricePerYear" placeholder="Prix à l'année">
                <input type="number" name="value" placeholder="Nb d'heures de services inclus">
                <input type="submit" value="Valider l'ajout">
                <br>
            </form>
        </div>
        <br>
        <?php foreach($subscriptions as  $subscription) {?>
            <br/>
            <div class="row" id="<?=$subscription['subscriptionID']?><?= $subscription['language']?>subdata";>
                <div class="col" id="<?=$subscription['subscriptionID'].'data'?>" >
                    <?= $subscription['subscriptionID'] ?> - <?= $subscription['nameSubscription'] ?> <?=$subscription['pricePerYear']?> <?=$subscription['nbDays']?>J/7
                </div>
                <div class="col-6">
                    <?php if($subscription['validity'] == 0) { ?>
                        <a href="verif_admin?&validity1=<?= $subscription['subscriptionID'] ?>"><input type="button" class="co btn btn-secondary" value="Activer l'abonnement"/></a>
                    <?php }else{ ?>
                        <a href="verif_admin?&validity0=<?= $subscription['subscriptionID'] ?>"><input type="button" class="co btn btn-secondary" value="Désactiver l'abonnement"/></a>
                    <?php } ?>
                    <input type="button" class="co btn btn-secondary" onclick="modif_data('<?=$subscription['subscriptionID'].'sub'?>')" value="Modifier les informations de l'abonnement"/><br/>
                </div>
                <div class="col" id="<?=$subscription['subscriptionID'].'sub'?>" style="display:none">
                    <form  action="verif_admin.php?id_sub=<?=$subscription['subscriptionID']?>" method="post"><!-- form post + id_get -->
                        <input type="hidden" name="stripeID" value="<?=$subscription["stripeID"]?>">
                        <input type="text" name="nameSubscriptionFr" placeholder="Nom de l'abonnement Français">
                        <input type="text" name="nameSubscriptionEn" value="<?=$subscription['nameSubscription']?>" placeholder="Nom de l'abonnement Anglais">
                        <input type="number" name="nbDays" value="<?=$subscription['nbDays']?>" placeholder="Nombre de jour par semaine Xj/7">
                        <input type="time" name="startTime" value="<?=$subscription['startTime']?>" placeholder="Heure de début">
                        <input type="time" name="endTime" value="<?=$subscription['endTime']?>" placeholder="Heure de fin">
                        <input type="number" name="pricePerYear" value="<?=$subscription['pricePerYear']?>" placeholder="Prix à l'année">
                        <input type="number" name="value" value="<?=$subscription['value']?>" placeholder="Nb d'heures de services inclus">
                        <input type="submit" value="Valider l'ajout">
                        <br>
                    </form>
                </div>
            </div>
        <?php } ?> <!-- "}" du while -->

    </section>
</main>