<header>
    <h1>LuxeryService</h1>
    <nav>
        <ul id="menu_barre">
            <li class="link_header">
                <a href="index.php"><img src="Pictures/Logo_LuxeryService.png" alt="Image LuxeryService" height="80px"/></a>
            </li>
            <li class="link_header">
                <a href="index.php" class="link">Home Page</a>&nbsp&nbsp&nbsp|
            </li>
            <li class="link_header">
                <a href="#" class="link">Nos Services</a>&nbsp&nbsp&nbsp|
                <ul class="under_menu">
                    <li><a href="#" class="link_menu">Services simples</a></li>
                    <li><a href="#" class="link_menu">Services récurents</a></li>
                    <li><a href="#" class="link_menu">Abonnements</a></li>
                </ul>
            </li>
            <li class="link_header">
                <a href="#" class="link">Réservation de préstation</a>&nbsp&nbsp&nbsp|
            </li>
            <li class="link_header">
                <a href="#" class="link">Informations Tarifs</a>&nbsp&nbsp&nbsp|
            </li>
            <li class="link_header">
                <a href="#" class="link">Nous contacter</a>&nbsp&nbsp&nbsp|
            </li>
            <?php if(!$connected){ ?>
                <li class="link_header">
                    <a href="connection.php" class="link">Se connecter</a>&nbsp&nbsp&nbsp|
                </li>
                <li class="link_header">
                    <a href="connection.php" class="link">S'inscrire</a>
                </li>
            <?php } else { ?>
                <li class="link_header">
                    <a href="#" class="link">Mon Profil</a>&nbsp&nbsp&nbsp|
                </li>
                <li class="link_header">
                    <a href="#" class="link">Deconnexion</a>
                </li>
            <?php } ?>
        <ul>
    </nav>
</header>