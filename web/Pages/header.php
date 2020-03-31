<?php
session_start();
if (!isset($_SESSION['lang']) || !in_array($_SESSION['lang'], ['FR', 'EN']))
    $_SESSION['lang'] = 'FR';

include('lang/'.$_SESSION['lang'].'.php');
?>
<header>
    <h1>LuxeryService</h1>
    <nav>
        <ul id="menu_barre">
            <li class="link_header">
                <a href="index.php"><img src="Pictures/Logo_LuxeryService.png" alt="Image LuxeryService" height="80px"/></a>
            </li>
            <li class="link_header">
                <a href="index.php" class="link"><?=_HOME?></a>&nbsp&nbsp&nbsp|
            </li>
            <li class="link_header">
                <a href="services.php" class="link"><?=_SERVICES?></a>&nbsp&nbsp&nbsp|
                <!--<ul class="under_menu">
                    <li><a href="#" class="link_menu">Services</a></li>
                    <li><a href="#" class="link_menu">Abonnements</a></li>
                </ul>-->
            </li>
            <li class="link_header">
                <a href="#" class="link"><?=_ABONNEMENTS?></a>&nbsp&nbsp&nbsp|
            </li>
            <li class="link_header">
                <a href="#" class="link"><?=_PLANNING?></a>&nbsp&nbsp&nbsp|
            </li>
            <li class="link_header">
                <a href="#" class="link"><?=_CONTACT?></a>&nbsp&nbsp&nbsp|
            </li>
            <?php if(!$connected){ ?>
                <li class="link_header">
                    <a href="connection.php" class="link"><?=_CONNECTION?></a>&nbsp&nbsp&nbsp|
                </li>
                <li class="link_header">
                    <a href="connection.php" class="link"><?=_ENROLMENT?></a>
                </li>
            <?php } else { ?>
                <li class="link_header">
                    <a href="../web/user_profil.php" class="link"><?=_PROFIL?></a>&nbsp&nbsp&nbsp|
                </li>
                <li class="link_header">
                    <a href="../web/Pages/deconnexion.php" class="link"><?=_DECONNEXION?></a>
                </li>
            <?php } ?>
        <ul>
    </nav>
</header>