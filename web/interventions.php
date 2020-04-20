<?php
session_start();
$connected = isset($_SESSION['email']) ? true : false;
require_once('Pages/db.php');
$db = connectionDB();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Projet Annuel 2i1">
        <link rel="stylesheet" type="text/css" href="CSS/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="CSS/CSS_Luxery.css">
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
            <section class="body_section">
                <h1><?=_INTERVENTIONDEMAND?> :</h1>
                <br/>

            </section>
            <br/>
            <p style="text-align:center"><img alt="separateur" id="separateur" src="Pictures/Separateur3.png"></p>
        </main>
        <?php require_once('Pages/footer.php'); ?>
    </body>
</html>
