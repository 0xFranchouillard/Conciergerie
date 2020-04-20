<footer>
    <div id="table_footer" class="container">
        <div class="row">
            <a href="services.php" class="col link_footer"><?=_SERVICES?></a>
            <a href="#" class="col link_footer"><?=_ABONNEMENTS?></a>
        </div>
        <?php if(!$connected){ ?>
            <div class="row">
                <a href="connection.php" class="col link_footer"><?=_CONNEXION?></a>
                <a href="connection.php" class="col link_footer"><?=_REGISTRATION?></a>
            </div>
        <?php } else { ?>
            <div class="row">
                <a href="user_profil.php" class="col link_footer"><?=_PROFIL?></a>
                <a href="Pages/deconnexion.php" class="col link_footer"><?=_DECONNEXION?></a>
            </div>
        <?php } ?>
        <div class="row">
            <?php if($connected){ ?>
                <a href="interventions.php" class="col link_footer"><?=_INTERVENTIONS?></a>
            <?php } ?>
            <a href="#" class="col link_footer"><?=_CONTACT?></a>
        </div>
    </div>
    <br/>
    <br/>
    <span>©GARVENES Cédric, CHAMPION Cyrille et BRONGNIART Arthur 2020</span>
</footer>