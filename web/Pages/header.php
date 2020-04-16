<?php
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
    }elseif (!isset($_SESSION['lang']) || !in_array($_SESSION['lang'], ['FR', 'EN'])) {
        $_SESSION['lang'] = 'FR';
    }
include('lang/'.$_SESSION['lang'].'.php');
?>
<script src="JS/Translate.js" charset="utf-8"></script>
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
            <?php if($connected){ ?>
                <li class="link_header">
                    <a href="#" class="link"><?=_PLANNING?></a>&nbsp&nbsp&nbsp|
                </li>
            <?php } ?>
                <li class="link_header">
                    <a href="#" class="link"><?=_CONTACT?></a>&nbsp&nbsp&nbsp|
                </li>
            <?php if(!$connected){ ?>
                <li class="link_header">
                    <a href="connection.php" class="link"><?=_CONNEXION?></a>&nbsp&nbsp&nbsp|
                </li>
                <li class="link_header">
                    <a href="connection.php" class="link"><?=_REGISTRATION?></a>&nbsp&nbsp&nbsp|
                </li>
            <?php } else { ?>
                <li class="link_header">
                    <a href="../web/user_profil.php" class="link"><?=_PROFIL?></a>&nbsp&nbsp&nbsp|
                </li>
                <li class="link_header">
                    <a href="../web/Pages/deconnexion.php" class="link"><?=_DECONNEXION?></a>&nbsp&nbsp&nbsp|
                </li>
            <?php } ?>
            <li class="link_header">
                <img src="Pictures/fr.png" title="français" width="20px" onclick="tr('FR')"/>
            </li>&nbsp&nbsp&nbsp|
            <li class="link_header">
                <img src="Pictures/en.png" title="english" width="20px" onclick="tr('EN')"/>
            </li>
        </ul>
    </nav>
</header>
