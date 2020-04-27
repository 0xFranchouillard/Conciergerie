<?php
session_start();
$connected = isset($_SESSION['email']) ? true : false;
// Si l'utilisateur n'est pas connecté => redirection page accueil
if($connected==false){
    header('Location: index.php');
    exit;
}
require_once('Pages/db.php');
require_once('stripe/init.php');

$db = connectionDB();
$requestService = $db->prepare('SELECT * FROM Service WHERE serviceID= :id && language= :lang');
$requestIdBill = $db->prepare('SELECT DISTINCT billID, totalPrice, validityDate FROM Bill WHERE clientID= :id && agency= :agency && estimate= 1');
$requestBill = $db->prepare('SELECT * FROM Bill WHERE billID= :id');
$requestNameService = $db->prepare('SELECT nameService FROM Service WHERE serviceID= :id && language= :lang');

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Projet Annuel">
        <link rel="stylesheet" type="text/css" href="CSS/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="CSS/CSS_luxery.css">
        <script src="https://js.stripe.com/v3/"></script>
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
                                $_SESSION['nameServiceBill'] = array();
                                $_SESSION['nbTakeBill'] = array();
                                $_SESSION['priceServiceBill'] = array();
                                $_SESSION['priceRecurrentServiceBill'] = array();
                                $_SESSION['minimumTypeBill'] = array();
                                $_SESSION['totalPriceBill'] = 0;
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
                                    $_SESSION['nameServiceBill'][$i] = $resultService['nameService'];
                                    $_SESSION['nbTakeBill'][$i] = $_SESSION['nbTakeCart'][$i];
                                    $_SESSION['priceServiceBill'][$i] = $resultService['priceService'];
                                    $_SESSION['priceRecurrentServiceBill'][$i] = $resultService['priceRecurrentService'];
                                    $_SESSION['minimumTypeBill'][$i] = $resultService['minimumType'];
                                    if(!empty($_SESSION['minimumTypeBill'][$i]) && $_SESSION['nbTakeBill'][$i] >= $_SESSION['minimumTypeBill'][$i]) {
                                        $_SESSION['totalPriceBill'] += floatval($_SESSION['priceRecurrentServiceBill'][$i])*intval($_SESSION['nbTakeBill'][$i]);
                                    } else {
                                        $_SESSION['totalPriceBill'] += floatval($_SESSION['priceServiceBill'][$i])*intval($_SESSION['nbTakeBill'][$i]);
                                    }
                                    $color++;
                                       \Stripe\Stripe::setApiKey('sk_test_WDZW3sWkIUI5asuWjU1FOR7Z00kDVsxULV');
                                        $intent = \Stripe\PaymentIntent::create([
                                            'amount' => $_SESSION['totalPriceBill'] * 100,
                                            'currency' => 'eur',
                                            'customer' => $_SESSION['stripeID'],
                                            'payment_method_types' => ['card']
                                        ]);
                                } ?>
                                <div class="row" style="margin-top: 2%">
                                    <div class="col">
                                        <input type="button" value="<?= _CANCEL ?>" onclick="cancel()"/>
                                    </div>
                                    <div class="col">
                                        <input type="button" value="<?= _BUY ?>" onclick="modif_data('StripeBuy')"/>
                                    </div>
                                    <div class="col">
                                        <input type="button" value="<?= _ESTIMATE ?>" onclick="estimate()"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form method="post" id="StripeBuy" style="display: none">
                            <div id="errors"></div>
                            <input type="text" id="cardholder-name" value="<?=$_SESSION['email']?>" style="display: none">
                            <div id="card-elements"></div>
                            <div id="card-errors" role="alert"></div>
                            <button id="card-button" type="button" data-secret="<?=$intent['client_secret'] ?>">Procéder au paiement</button>
                        </form>
                        <div class="row" style="display: none" id="cartEmpty">
                            <div class="col">
                                <h4><?= _CARTEMPTY ?></h4>
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
            <!-- Devis en cours -->
            <section class="body_section">
                <h1><?= _ESTIMATE ?> :</h1>
                <br/>
                <div class="container">
                    <?php
                    $requestIdBill->execute([
                        'id'=>$_SESSION['id'],
                        'agency'=>$_SESSION['agencyClient']
                    ]);
                    if($requestIdBill->rowCount() != 0) {
                        $j = 0;
                        while ($resultIdBill = $requestIdBill->fetch()) {
                            ?>
                            <div class="row" id="estimate<?= $j ?>">
                                <div class="col">
                                    <div class="row"
                                         style="padding: 2% 0% 2% 0%; box-sizing: border-box; border: solid 1px #DFDFDF;">
                                        <div class="col" style="padding-top: 3%;">
                                            <h5><?= _ESTIMATE . ' N° ' . $resultIdBill['billID'] ?></h5>
                                        </div>
                                        <div class="col" style="padding-top: 3%;">
                                            <h5><?= _TOTALESTIMATE . ' ' . $resultIdBill['totalPrice'] . '€' ?></h5>
                                        </div>
                                        <div class="col">
                                            <h5><?= _VALIDESTIMATE . ' ' . $resultIdBill['validityDate'] ?></h5>
                                        </div>
                                        <div class="col" style="padding-top: 3%;">
                                            <input type="button" value="<?= _BUY ?>" onclick="buyEstimate(<?= $resultIdBill['billID'] . ',' . $j ?>)"/>
                                        </div>
                                    </div>
                                    <div class="row lessEstimate<?= $j ?>">
                                        <div class="col" style="margin-bottom: 1%;">
                                            <input type="button" value="<?= _DETAILS ?>" onclick="detail(<?= $j ?>)"/>
                                        </div>
                                    </div>
                                    <div class="row detailsEstimate<?= $j ?>" style="display: none;">
                                        <div class="col">
                                            <div class="row"
                                                 style="padding: 2% 0% 2% 0%; box-sizing: border-box; border: solid 1px #DFDFDF;">
                                                <div class="col">
                                                    <h5><?= _SERVICE ?></h5>
                                                </div>
                                                <div class="col">
                                                    <h5><?= _QUANTITY ?></h5>
                                                </div>
                                                <div class="col">
                                                    <h5><?= _UNITPRICE ?></h5>
                                                </div>
                                                <div class="col">
                                                    <h5><?= _TOTAL ?></h5>
                                                </div>
                                            </div>
                                            <?php
                                            $requestBill->execute([
                                                'id' => $resultIdBill['billID']
                                            ]);
                                            for ($i = 0; $resultBill = $requestBill->fetch(); $i++) {
                                                $requestNameService->execute([
                                                    'id' => $resultBill['serviceID'],
                                                    'lang' => $_SESSION['lang']
                                                ]);
                                                $resultNameService = $requestNameService->fetch();
                                                ?>
                                                <div class="row"
                                                     style="padding: 2% 0% 2% 0%; box-sizing: border-box; border: solid 1px #DFDFDF; <?php if ($i % 2 == 0) {
                                                         echo 'background-color: #DFDFDF';
                                                     } ?>">
                                                    <div class="col">
                                                        <h7><?= $resultNameService['nameService'] ?></h7>
                                                    </div>
                                                    <div class="col">
                                                        <h7><?= $resultBill['numberTaken'] ?></h7>
                                                    </div>
                                                    <div class="col">
                                                        <h7><?= $resultBill['priceService'] ?></h7>
                                                    </div>
                                                    <div class="col">
                                                        <h7><?= floatval($resultBill['priceService']) * intval($resultBill['numberTaken']) ?></h7>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row detailsEstimate<?= $j ?>" style="display: none;">
                                        <div class="col" style="margin-bottom: 1%;">
                                            <input type="button" value="<?= _LESS ?>" onclick="less(<?= $j ?>)"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $j++;
                        }
                    } else { ?>
                        <div class="row">
                            <div class="col">
                                <h4><?= _ESTIMATEEMPTY ?></h4>
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