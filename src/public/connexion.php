<?php
require_once '../app/fonctions.php';
if (!session_id())
    session_start();

global $trousseau;

createTrousseau($trousseau);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    //  debugForm($_POST, "post");
    $user['email'] = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $user['password'] = $_POST['password'];

    if(userExists($trousseau, $user)) {
        $_SESSION['user'] = $user['email'];
        $direction = $_SERVER['HTTP_ORIGIN'];
        header("Location: $direction/index.php");
    } else {
        echo "<div class='alert alert-danger'>Identifiants non valides</div>";
        echo "<a class='text-primary' href='index.php'>Recommencer l'authentification</a>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

    <link rel="icon" href="img/logos/logo_alcool_ecoute.png">
    <link rel="stylesheet" href="css/connexion.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">

    <link rel="icon" href="img/logos/logo_alcool_ecoute.png">
    <script src="../public/js/partialsLoader.js" defer></script>
</head>
<body>

<?php
require_once 'header.php';
?>

<main class="main-content">
    <section class="form-section">
        <img src="/src/public/img/logos/logo_alcool_ecoute.png" alt="Logo Alcool Écoute" class="form-logo">
        <h1>Connectez-vous à votre espace Alcool Écoute Joie & Santé</h1>

        <form action="" method="POST" class="form-container">
            <label for="email"> <img src="/src/public/img/mail.png">Email :</label>
            <input type="email" id="email" name="email" required class="form-input">

            <label for="password"> <img src="/src/public/img/mdp.webp">Mot de passe :</label>
            <input type="password" id="password" name="password" required class="form-input">

            <button type="submit" class="form-button">Se connecter</button>
        </form>
        <p class="form-footer">Pas encore de compte ? <a href="creation.php">Créer un compte</a></p>

        <?php
        if (isset($error)) {
            echo "<p class='error-message'>$error</p>"; }
        ?>
    </section>
</main>

<?php
require_once 'footer.php';
?>

</body>
</html>
