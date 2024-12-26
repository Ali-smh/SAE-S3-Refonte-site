<?php
require_once "fonctions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $users = loadUsers();

    if (isset($users[$email]) && password_verify($password, $users[$email]['password'])) {
        header("Location: enquete.php");
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/connexion.css">
    <link rel="icon" href="../img/logos/logo_alcool_ecoute.png">
    <script src="../js/partialsLoader.js" defer></script>
</head>
<body>
<header class="main-header">
</header>

<main class="main-content">
    <section class="form-section">
        <h1>Connexion</h1>
        <form action="" method="POST" class="form-container">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required class="form-input">

            <label for="password">Mot de passe :</label>
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

<footer class="main-footer">
    <p>&copy; 2024 Votre Organisation. Tous droits réservés.</p>
</footer>
</body>
</html>
