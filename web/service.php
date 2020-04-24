<?php
session_start();
$connected = isset($_SESSION['email']) ? true : false;
//Si il n'y a pas de variable $_GET['serviceID'] on retourne à services.php
if(!isset($_GET['serviceID'])){
    header('Location: services.php');
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
        <script src="JS/Ajax/addToCart.js"></script>
        <title>LuxeryService</title>
    </head>
    <body>
        <?php
        require_once('Pages/header.php');

        $requestService->execute([
            'id'=>$_GET['serviceID'],
            'lang'=>$_SESSION['lang']
        ]);
        $resultService = $requestService->fetch();
        ?>
        <main>
            <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur3.png"></p>
            <br>
            <!-- Info/Tarifs Service -->
            <section class="body_section">
                <h1><?= $resultService["nameService"] ?></h1>
                <br/>
                <p class="text" style="margin-right: 20%; margin-left: 20%;"><?= $resultService["description"] ?></p>
                <br/><br/>
                <div class="container">
                    <div class="row" style="padding: 2% 0% 2% 0%; box-sizing: border-box;">
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h4><?= _SINGLETARIFF ?></h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <h6><?= $resultService["priceService"] . "€ " . _INCLTAXES . "/" . $resultService["priceTypeService"] ?></h6>
                                        </div>
                                    </div>
                                </div>
                                <?php if($resultService['priceRecurrentService'] != NULL) { ?>
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h4><?= _RECURRINGTARIFF ?></h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <h6><?= $resultService["priceRecurrentService"] . "€ " . _INCLTAXES . "/" . $resultService["priceTypeService"] ?></h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <h6>(<?php echo _MINIMUMOF . " " . $resultService["minimumType"] . " " . $resultService["priceTypeService"] . " " . _ORDERED; ?>)</h6>
                                        </div>
                                    </div>
                                </div>
                                <?php } else {?>
                                    <div class="col">
                                        <div class="row">
                                            <div class="col">

                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php if($connected) { ?>
                                <div class="row">
                                    <div class="col">
                                        <form action="" method="post">
                                            <br/>
                                            <br/>
                                            <div class="row">
                                                <div class="col">
                                                    <input type="number" min="0" value="1" placeholder="<?= _NBTAKE ?>" id="nbTake">
                                                </div>
                                                <div class="col">
                                                    <input type="button" value="<?= _ADDTOCART ?>" id="addToCart" onclick="addCart(<?= $resultService['serviceID'] ?>)">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?php } ?>
                            <h6 id="error" style="display: none"></h6>
                        </div>
                    </div>
                </div>
            </section>
            <br/>
            <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur3.png"></p>
        </main>
        <?php require_once('Pages/footer.php'); ?>
    </body>
</html>
