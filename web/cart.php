<?php
session_start();
$connected = isset($_SESSION['email']) ? true : false;
// Si l'utilisateur n'est pas connecté => redirection page accueil
if($connected==false){
    header('Location: index.php');
    exit;
}
require_once('Pages/db.php');
$db = connectionDB();
$requestService = $db->prepare('SELECT * FROM Service WHERE serviceID= :id && language= :lang');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Projet Annuel">
        <link rel="stylesheet" type="text/css" href="CSS/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="CSS/CSS_Luxery.css">
        <script src="JS/Ajax/cart.js"></script>
        <title>LuxeryService</title>
    </head>
    <body>
        <?php require_once('Pages/header.php'); ?>
        <main>
            <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur3.png"></p>
            <br>
            <!-- Panier -->
            <section class="body_section">
                <h1><?= _CART ?> :</h1>
                <br/>
                <div class="container">
                    <?php if(isset($_SESSION['serviceIDCart']) && count($_SESSION['serviceIDCart']) != 0) { ?>
                        <div class="row" id="valueOnHold">
                            <div class="col">
                                <div class="row" style="margin-bottom: 2%">
                                    <div class="col">
                                        <h5><?= _NUMBER ?></h5>
                                    </div>
                                    <div class="col">
                                        <h5><?= _SERVICE ?></h5>
                                    </div>
                                    <div class="col">
                                        <h5><?= _SINGLETARIFF ?></h5>
                                    </div>
                                    <div class="col">
                                        <h5><?= _RECURRINGTARIFF ?></h5>
                                    </div>
                                </div>
                                <?php
                                $_SESSION['nameServiceEstimate'] = array();
                                $_SESSION['nbTakeEstimate'] = array();
                                $_SESSION['priceServiceEstimate'] = array();
                                $_SESSION['priceRecurrentServiceEstimate'] = array();
                                $_SESSION['minimumTypeEstimate'] = array();
                                $_SESSION['priceTypeServiceEstimate'] = array();
                                $color = 0;
                                for ($i = 0; $i < count($_SESSION['serviceIDCart']); $i++) {
                                    $requestService->execute([
                                        'id' => $_SESSION['serviceIDCart'][$i],
                                        'lang' => $_SESSION['lang']
                                    ]);
                                    $resultService = $requestService->fetch();
                                    ?>
                                    <div class="row" style="padding: 2% 0% 2% 0%; box-sizing: border-box; border: solid 1px #DFDFDF; <?php if($color%2 == 1) { echo 'background-color: #DFDFDF'; } ?>">
                                        <div class="col">
                                            <h7><?= $_SESSION['nbTakeCart'][$i] ?></h7>
                                        </div>
                                        <div class="col">
                                            <h7><?= $resultService['nameService'] ?></h7>
                                        </div>
                                        <div class="col">
                                            <h7><?= $resultService['priceService'] . "€ " . _INCLTAXES . "/" . $resultService['priceTypeService'] ?></h7>
                                        </div>
                                        <?php if($resultService['priceRecurrentService'] != NULL) { ?>
                                        <div class="col">
                                            <h7><?= $resultService['priceRecurrentService'] . "€ " . _INCLTAXES . "/" . $resultService['priceTypeService'] ?></h7>
                                        </div>
                                        <?php } else { ?>
                                        <div class="col">
                                        </div>
                                        <?php } ?>
                                    </div>
                                <?php
                                    $_SESSION['nameServiceEstimate'][$i] = $resultService['nameService'];
                                    $_SESSION['nbTakeEstimate'][$i] = $_SESSION['nbTakeCart'][$i];
                                    $_SESSION['priceServiceEstimate'][$i] = $resultService['priceService'];
                                    $_SESSION['priceRecurrentServiceEstimate'][$i] = $resultService['priceRecurrentService'];
                                    $_SESSION['minimumTypeEstimate'][$i] = $resultService['minimumType'];
                                    $_SESSION['priceTypeServiceEstimate'][$i] = $resultService['priceTypeService'];
                                    $color++;
                                } ?>
                                <div class="row" style="margin-top: 2%">
                                    <div class="col">
                                        <input type="button" value="Annuler" onclick="cancel()"/>
                                    </div>
                                    <div class="col">
                                        <input type="button" value="Devis" onclick="estimate()"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else {
                        ?>
                    <div class="row">
                        <div class="col">
                            <h4><?= _CARTEMPTY ?></h4>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </section>
            <br/>
            <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur4.png"></p>
            <br/>
            <!-- Intervention créer en attente de paiement -->
            <section class="body_section">
                <h1><?= _INTERVENTIONS ?> :</h1>
                <br/>

            </section>
            <br/>
            <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur3.png"></p>
        </main>
        <?php require_once('Pages/footer.php'); ?>
    </body>
</html>
<?php
function rescoverDate($noDay) {
    switch ($noDay) {
        case 1:
            return _MONDAY;
            break;
        case 2:
            return _TUESDAY;
            break;
        case 3:
            return _WEDNESDAY;
            break;
        case 4:
            return _THURSDAY;
            break;
        case 5:
            return _FRIDAY;
            break;
        case 6:
            return _SATURDAY;
            break;
        case 7:
            return _SUNDAY;
            break;
    }
}
?>