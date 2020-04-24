<?php
session_start();
$connected = isset($_SESSION['email']) ? true : false;
require_once('Pages/db.php');
$db = connectionDB();
$requestPlanning = $db->prepare('SELECT * FROM Intervention WHERE clientID= :clientID && agency= :agency && statutIntervention= 0');
$requestHistory = $db->prepare('SELECT * FROM Intervention WHERE clientID= :clientID && agency= :agency && statutIntervention= 1');
$requestProvider = $db->prepare('SELECT lastName, firstName FROM ServiceProvider WHERE providerID= :providerID && agency= :agency');
$requestService = $db->prepare('SELECT nameService FROM Service WHERE serviceID= :serviceID && language= :lang');
$requestAllService = $db->prepare('SELECT * FROM Service WHERE language= :lang');
$requestPlanning->execute([
   'clientID'=>$_SESSION['id'],
   'agency'=>$_SESSION['agencyClient']
]);
$requestHistory->execute([
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
        <script src="JS/Ajax/intervention.js"></script>
        <title>LuxeryService</title>
    </head>
    <body>
        <?php require_once('Pages/header.php'); ?>
        <main>
            <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur3.png"></p>
            <br>
            <section class="body_section">
                <h1><?=_INTERVENTIONS?> :</h1>
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
            <!-- Demande de service -->
            <section class="body_section">
                <h1><?=_INTERVENTIONDEMAND?> :</h1>
                <br/>
                <div class="container">
                    <form action="" method="post">
                        <div class="row">
                            <div class="col">
                                 <select id="serviceSelect" onchange="selectService()">
                                     <option value=""><?=_SERVICE?></option>
                                     <?php
                                     $requestAllService->execute([
                                             'lang'=>$_SESSION['lang']
                                     ]);
                                     while ($resultAllService = $requestAllService->fetch()) {
                                         ?>
                                         <option value="<?= $resultAllService['serviceID'] ?>"><?= $resultAllService['nameService'] ?></option>
                                     <?php
                                     }
                                     ?>
                                 </select>
                            </div>
                        </div>
                        <div class="row serviceSelect" style="display: none">
                            <div class="col">
                                <input type="number" min="0" value="1" id="nbTakeIntervention"/>
                                <label id="typeServiceIntervention"></label>
                            </div>
                        </div>
                        <div class="row serviceSelect" style="display: none">
                            <div class="col">
                                <label><?= _DAY ?> :</label>
                                <input type="date" id="dateIntervention"/>
                            </div>
                            <div class="col">
                                <label><?= _HOUR ?> :</label>
                                <input type="time" id="timeIntervention"/>
                            </div>
                        </div>
                        <div class="row serviceSelect">
                            <div class="col" id="subscription">
                            </div>
                        </div>
                        <div class="row serviceSelect" style="display: none">
                            <div class="col">
                                <input type="button" value="<?= _CREATE ?>" onclick="createIntervention()"/>
                            </div>
                        </div>
                        <h6 id="error" style="display: none"></h6>
                    </form>
                </div>
            </section>
            <br/>
            <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur3.png"></p>
            <br/>
            <!-- Planning -->
            <section class="body_section">
                <h1><?=_PLANNING?> :</h1>
                <br/>
                <div class="container">
                    <?php if($requestPlanning->rowCount() != 0 ) { ?>
                        <div class="row" style="padding: 2% 0% 2% 0%; box-sizing: border-box; border: solid 1px #DFDFDF;">
                            <div class="col">
                                <h5><?= _SERVICE ?></h5>
                            </div>
                            <div class="col">
                                <h5><?= _NUMBER ?></h5>
                            </div>
                            <div class="col">
                                <h5><?= _PROVIDER ?></h5>
                            </div>
                            <div class="col">
                                <h5><?= _DAY.'/'._HOUR ?></h5>
                            </div>
                            <div class="col">
                            </div>
                        </div>
                        <?php for($i=0; $resultPlanning =$requestPlanning->fetch(); $i++) {
                            $requestProvider->execute([
                               'providerID'=>$resultPlanning['providerID'],
                               'agency'=>$resultPlanning['agencyProvider']
                            ]);
                            $requestService->execute([
                               'serviceID'=>$resultPlanning['serviceID'],
                                'lang'=>$_SESSION['lang']
                            ]);
                            $resultProvider = $requestProvider->fetch();
                            $resultService = $requestService->fetch();
                            ?>
                            <div class="row" style="padding: 2% 0% 2% 0%; box-sizing: border-box; border: solid 1px #DFDFDF; <?php if($i%2 == 1) { echo 'background-color: #DFDFDF'; } ?>">
                                <div class="col">
                                    <h7><?= $resultService['nameService'] ?></h7>
                                </div>
                                <div class="col">
                                    <h7><?= $resultPlanning['pastType'] ?></h7>
                                </div>
                                <div class="col">
                                    <h7><?= $resultProvider['lastName'].' '.$resultProvider['firstName'] ?></h7>
                                </div>
                                <div class="col">
                                    <h7><?= $resultPlanning['dateIntervention'].' '._TO1.' '.$resultPlanning['timeIntervention'] ?></h7>
                                </div>
                                <div class="col">
                                    <input type="button" value="<?= _CANCEL ?>"/>
                                </div>
                            </div>
                    <?php }
                        } else { ?>
                        <div class="row">
                            <div class="col">
                                <h4><?= E_PLANNING ?></h4>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </section>
            <br/>
            <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur6.png"></p>
            <br/>
            <!-- Historique -->
            <section class="body_section">
                <h1><?=_HISTORY?> :</h1>
                <br/>
                <div class="container">
                    <?php if($requestHistory->rowCount() != 0 ) { ?>
                        <div class="row" style="padding: 2% 0% 2% 0%; box-sizing: border-box; border: solid 1px #DFDFDF;">
                            <div class="col">
                                <h5><?= _SERVICE ?></h5>
                            </div>
                            <div class="col">
                                <h5><?= _NUMBER ?></h5>
                            </div>
                            <div class="col">
                                <h5><?= _PROVIDER ?></h5>
                            </div>
                            <div class="col">
                                <h5><?= _DAY.'/'._HOUR ?></h5>
                            </div>
                        </div>
                        <?php for($i=0; $resultHistory = $requestHistory->fetch(); $i++) {
                            $requestProvider->execute([
                                'providerID'=>$resultHistory['providerID'],
                                'agency'=>$resultHistory['agencyProvider']
                            ]);
                            $requestService->execute([
                                'serviceID'=>$resultHistory['serviceID'],
                                'lang'=>$_SESSION['lang']
                            ]);
                            $resultProvider = $requestProvider->fetch();
                            $resultService = $requestService->fetch();
                            ?>
                            <div class="row" style="padding: 2% 0% 2% 0%; box-sizing: border-box; border: solid 1px #DFDFDF; <?php if($i%2 == 1) { echo 'background-color: #DFDFDF'; } ?>">
                                <div class="col">
                                    <h7><?= $resultService['nameService'] ?></h7>
                                </div>
                                <div class="col">
                                    <h7><?= $resultHistory['pastType'] ?></h7>
                                </div>
                                <div class="col">
                                    <h7><?= $resultProvider['lastName'].' '.$resultProvider['firstName'] ?></h7>
                                </div>
                                <div class="col">
                                    <h7><?= $resultHistory['dateIntervention'].' '._TO1.' '.$resultHistory['timeIntervention'] ?></h7>
                                </div>
                            </div>
                    <?php }
                        } else { ?>
                        <div class="row">
                            <div class="col">
                                <h4><?= E_HISTORY ?></h4>
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
