<?php
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
    }elseif (!isset($_SESSION['lang'])) {
        $_SESSION['lang'] = 'FR';
    }
require_once('lang/'.$_SESSION['lang'].'.php');
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
                <a href="index.php" class="link"><?=_HOME?></a>
            </li>&nbsp&nbsp&nbsp|
            <li class="link_header">
                <a href="services.php" class="link"><?=_SERVICES?></a>
                <!--<ul class="under_menu">
                    <li><a href="#" class="link_menu">Services</a></li>
                    <li><a href="#" class="link_menu">Abonnements</a></li>
                </ul>-->
            </li>&nbsp&nbsp&nbsp|
            <li class="link_header">
                <a href="#" class="link"><?=_ABONNEMENTS?></a>
            </li>&nbsp&nbsp&nbsp|
            <?php if($connected){ ?>
                <li class="link_header">
                    <a href="interventions.php" class="link"><?=_INTERVENTIONS?></a>
                </li>&nbsp&nbsp&nbsp|
            <?php } ?>
                <li class="link_header">
                    <a href="#" class="link"><?=_CONTACT?></a>
                </li>&nbsp&nbsp&nbsp|
            <?php if(!$connected){ ?>
                <li class="link_header">
                    <a href="connection.php" class="link"><?=_CONNEXION?></a>
                </li>&nbsp&nbsp&nbsp|
                <li class="link_header">
                    <a href="connection.php" class="link"><?=_REGISTRATION?></a>
                </li>&nbsp&nbsp&nbsp|
            <?php } else { ?>
                <li class="link_header">
                    <a href="user_profil.php" class="link"><?=_PROFIL?></a>
                </li>&nbsp&nbsp&nbsp|
                <li class="link_header">
                    <a href="Pages/deconnexion.php" class="link"><?=_DECONNEXION?></a>
                </li>&nbsp&nbsp&nbsp|
            <?php } ?>
            <li class="link_header">
                <img src="Pictures/fr.png" title="français" width="30px" onclick="tr('FR')"/>
            </li>&nbsp&nbsp&nbsp|
            <li class="link_header">
                <img src="Pictures/en.png" title="english" width="30px" onclick="tr('EN')"/>
            </li>&nbsp&nbsp&nbsp|
            <li class="link_header">
                <img src="Pictures/ALL.png" title="deutsch" width="30px" onclick="tr('ALL')"/>
            </li>
            <?php if($connected){ ?>
                &nbsp&nbsp&nbsp|
                <li class="link_header">
                    <a href="cart.php">
                        <img src="Pictures/cart.png" title="cart" width="40px">
                    </a>
                </li>
            <?php } ?>
        </ul>
    </nav>
</header>
