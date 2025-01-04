<header>
    <i class="fa-solid fa-bars"></i>
    <a href="index.php"><img alt="" class="logo" src="img/logos/logo_alcool_ecoute.png"></a>
    <div class="navbar">
        <ul>
            <li><a href="#">Fédération</a></li>
            <li><a href="associations.php">Associations</a></li>
            <li><a href="prevention.php">Prévention</a></li>
            <li><a href="#">Accompagnement</a></li>
            <li><a href="actualites.php">Actualités</a></li>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Contact</a></li>
            <?php if (!isset($_SESSION['user'])): ?>
                <li><a href="connexion.php">Se connecter</a></li>
            <?php else: ?>
                <li><a href="enquete.php">Enquete</a></li>
                <li><a href="deconnexion.php">Se déconnecter</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <i class="fa-solid fa-magnifying-glass"></i>
</header>