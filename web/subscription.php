<?php
session_start();
$connected = isset($_SESSION['email']) ? true : false;
require_once('Pages/db.php');
require_once('stripe/init.php');

$db = connectionDB();
$requestSubscription = $db->prepare('SELECT * FROM Subscription WHERE language= :lang');
$requestSubscribes = $db->prepare('SELECT * FROM Subscribes WHERE clientID= :clientID && agency= :agency');
$requestClientSubscription = $db->prepare('SELECT * FROM Subscription WHERE subscriptionID= :subscriptionID && language= :lang');
$requestSubscription->execute([
   'lang'=>$_SESSION['lang']
]);
$requestSubscribes->execute([
   'clientID'=>$_SESSION['id'],
   'agency'=>$_SESSION['agencyClient']
]);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="Projet Annuel 2i1">
    <link rel="stylesheet" type="text/css" href="CSS/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="CSS/CSS_Luxery.css">
    <script src="JS/Ajax/subscription.js"></script>
    <title>LuxeryService</title>
</head>
<body>
<?php require_once('Pages/header.php'); ?>
<main>
    <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur3.png"></p>
    <br>
    <section class="body_section">
        <h1><?=_ABONNEMENTS?> :</h1>
        <br/>
        <p class="text">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
    </section>
    <br/>
    <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur6.png"></p>
    <br/>
    <!-- Abonnement disponible -->
    <section class="body_section">
        <h1><?=_SUBSCRIPTION?> :</h1>
        <br/>
        <div class="container">
            <?php if($requestSubscription->rowCount() != 0) {
                for($i = 0; $resultSubscription = $requestSubscription->fetch(); $i++) {
                    if($i % 3 == 0) { ?>
                        <div class="row">
                    <?php } ?>
                            <div class="col" style="padding: 0% 0% 2% 0%; box-sizing: border-box; border: solid 1px #DFDFDF;">
                                <div class="row" style=" max-width: 100%; margin: 0%;">
                                    <div class="col" style="padding: 2% 0% 2% 0%; box-sizing: border-box; border: solid 1px #DFDFDF;">
                                        <h5><?= $resultSubscription['nameSubscription']?></h5>
                                    </div>
                                </div>
                                <div class="row" style=" max-width: 100%; margin: 0%;">
                                    <div class="col">
                                        <?php if(strtotime($resultSubscription['startTime']) == strtotime($resultSubscription['endTime'])) { ?>
                                            <h6><?= _BENEFITPRIVILEGED.' '.$resultSubscription['nbDays']._DAYS.'/7 24'._H.'/24' ?></h6>
                                        <?php } else { ?>
                                            <h6><?= _BENEFITPRIVILEGED.' '.$resultSubscription['nbDays']._DAYS.'/7 '._OF.' '.date('H:i', strtotime($resultSubscription['startTime']))._H.' '._TO1.' '.date('H:i', strtotime($resultSubscription['endTime']))._H ?></h6>
                                        <?php } ?>
                                    </div>
                                </div><br/><br/>
                                <div class="row" style=" max-width: 100%; margin: 0%;">
                                    <div class="col">
                                        <h6><?= _UNLIMITEDINQUIRIES ?></h6>
                                    </div>
                                </div><br/>
                                <div class="row" style=" max-width: 100%; margin: 0%;">
                                    <div class="col">
                                        <h6><?= $resultSubscription['value']._H.' '._SERVICEMONTH ?></h6>
                                    </div>
                                </div><br/><br/>
                                <div class="row" style=" max-width: 100%; margin: 0%;">
                                    <div class="col">
                                        <h6><?= $resultSubscription['pricePerYear'].'€ '._INCLTAXES.' /'._YEAR ?></h6>
                                    </div>
                                </div><br/>
                                <div class="row" style=" max-width: 100%; margin: 0%;">
                                    <div class="col">
                                        <a href="pay.php?sub=<?= $resultSubscription['subscriptionID'] ?>" target="_blank"><input type="button" value="<?= _BUY ?>"> </a>
                                        <!--<input type="button" value="<?= _BUY ?>" onclick="buySubscription(<?= $resultSubscription['subscriptionID'] ?>)"/>    -->
                                    </div>
                                </div>
                                <h6 id="error<?= $resultSubscription['subscriptionID'] ?>" style="none"></h6>
                            </div>
                    <?php if ($i % 3 == 2) { ?>
                        </div>
                    <?php } ?>
               <?php }
            } else { ?>
                <div class="row">
                    <div class="col">
                        <h6><?= E_SUBSCRIPTION ?></h6>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
    <br/>
    <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur6.png"></p>
    <br/>
    <!-- Abonnement pris -->
    <section class="body_section">
        <h1><?=_YOURSUBSCRIPTION?> :</h1>
        <br/>
        <div class="container">
            <?php if($requestSubscribes->rowCount() != 0) {
                for($i = 0; $resultSubscribes = $requestSubscribes->fetch(); $i++) {
                    $requestClientSubscription->execute([
                       'subscriptionID'=>$resultSubscribes['subscriptionID'],
                       'lang'=>$_SESSION['lang']
                    ]);
                    $resultClientSubscription = $requestClientSubscription->fetch();
                    if($i % 3 == 0) { ?>
                        <div class="row">
                    <?php } ?>
                    <div class="col" style="padding: 0% 0% 2% 0%; box-sizing: border-box; border: solid 1px #DFDFDF;">
                        <div class="row" style=" max-width: 100%; margin: 0%;">
                            <div class="col" style="padding: 2% 0% 2% 0%; box-sizing: border-box; border: solid 1px #DFDFDF;">
                                <h5><?= $resultClientSubscription['nameSubscription']?></h5>
                            </div>
                        </div>
                        <div class="row" style=" max-width: 100%; margin: 0%;">
                            <div class="col">
                                <?php if(strtotime($resultClientSubscription['startTime']) == strtotime($resultClientSubscription['endTime'])) { ?>
                                    <h6><?= _BENEFITPRIVILEGED.' '.$resultClientSubscription['nbDays']._DAYS.'/7 24'._H.'/24' ?></h6>
                                <?php } else { ?>
                                    <h6><?= _BENEFITPRIVILEGED.' '.$resultClientSubscription['nbDays']._DAYS.'/7 '._OF.' '.date('H:i', strtotime($resultClientSubscription['startTime']))._H.' '._TO1.' '.date('H:i', strtotime($resultClientSubscription['endTime']))._H ?></h6>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row" style=" max-width: 100%; margin: 0%;">
                            <div class="col">
                                <h6><?= _ENDSUBSCRIPTION.' '.$resultSubscribes['endDate'] ?></h6>
                            </div>
                        </div><br/>
                        <div class="row" style=" max-width: 100%; margin: 0%;">
                            <div class="col">
                                <h6><?= _UNLIMITEDINQUIRIES ?></h6>
                            </div>
                        </div><br/>
                        <div class="row" style=" max-width: 100%; margin: 0%;">
                            <div class="col">
                                <h6><?= $resultClientSubscription['value']._H.' '._SERVICEMONTH ?></h6>
                            </div>
                        </div>
                        <div class="row" style=" max-width: 100%; margin: 0%;">
                            <div class="col">
                                <h6><?= _STILLHAVE.' '.$resultSubscribes['valueMonth']._H.' '._ONSUBSCRIPTION ?></h6>
                            </div>
                        </div><br/>
                        <div class="row" style=" max-width: 100%; margin: 0%;">
                            <div class="col">
                                <h6><?= $resultClientSubscription['pricePerYear'].'€ '._INCLTAXES.' /'._YEAR ?></h6>
                            </div>
                        </div>
                    </div>
                    <?php if ($i % 3 == 2) { ?>
                        </div>
                    <?php } ?>
                <?php }
            } else { ?>
                <div class="row">
                    <div class="col">
                        <h6><?= _NOSUBSCRIPTION ?></h6>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
    <br/>
    <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur3.png"></p>
</main>
<?php require_once('Pages/footer.php'); ?>
</body>
</html>
