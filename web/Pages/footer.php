<footer>
    <div id="table_footer" class="container">
        <div class="row">
            <a href="service.php" class="col link_footer">Nos services</a>
            <a href="#" class="col link_footer">Abonnements</a>
        </div>
        <div class="row">
            <a href="#" class="col link_footer">Planning</a>
            <a href="#" class="col link_footer">Nous contacter</a>
        </div>
        <?php if(!$connected){ ?>
            <div class="row">
                <a href="connection.php" class="col link_footer">Se connecter</a>
                <a href="connection.php" class="col link_footer">S'inscrire</a>
            </div>
        <?php } else{ ?>
            <div class="row">
                <a href="#" class="col link_footer">Mon Profil</a>
                <a href="deconnexion.php" class="col link_footer">Deconnexion</a>
            </div>
        <?php } ?>
    </div>
    <br/>
    <br/>
    <span>©GARVENES Cédric, CHAMPION Cyrille et BRONGNIART Arthur 2020</span>
</footer>