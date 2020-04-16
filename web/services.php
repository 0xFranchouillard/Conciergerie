<?php
session_start();
$connected = isset($_SESSION['email']) ? true : false;

$json = file_get_contents("http://localhost/API_TEST_URI/v1/service", false);
$service_infos = json_decode($json, true);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="Projet Annuel 2i1">
    <link rel="stylesheet" type="text/css" href="CSS/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="CSS/CSS_luxery.css">
    <title>LuxeryService</title>
</head>
<body>
<?php require_once('Pages/header.php'); ?>
<main>

    <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur3.png"></p>
    <br>
    <section class="body_section">
        <h1>Nos Services :</h1>
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
    <section class="body_section">
        <h1>Demande de Services :</h1>
        <br/>
        <form action="" method="post">
            <div class="container">
                <?php
                if($service_infos != 0) {
                    for ($i = 0; $i < count($service_infos); $i++) {
                        if ($i % 3 == 0) { ?>
                            <div class="row">
                            <div class="col">
                                <a href="service.php?serviceID=1=<?= $service_infos[$i]['serviceID'] ?>"><?= $service_infos[$i]['nameService'] ?></a>
                            </div>
                        <?php } else if ($i % 3 == 2) { ?>
                            <div class="col">
                                <a href="service.php?serviceID=1=<?= $service_infos[$i]['serviceID'] ?>"><?= $service_infos[$i]['nameService'] ?></a>
                            </div>
                            </div>
                        <?php } else { ?>
                            <div class="col">
                                <a href="service.php?serviceID=1=<?= $service_infos[$i]['serviceID'] ?>"><?= $service_infos[$i]['nameService'] ?></a>
                            </div>
                        <?php }
                    }
                } else { ?>
                    <div class="col">
                        <label>Pas de services disponible</label>
                    </div>
               <?php }?>
            </div>
        </form>
    </section>
    <br/>
    <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur3.png"></p>
</main>
<?php require_once('Pages/footer.php'); ?>
</body>
</html>
