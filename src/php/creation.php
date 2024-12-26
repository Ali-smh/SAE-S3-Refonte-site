<?php
require_once "fonctions.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);

    
    $users = loadUsers();

    if (userExists($email, $users)) {
        $error = "Un compte avec cet email existe déjà.";
    } else {
        
        $users[$email] = createUser($email, $password, $nom, $prenom);
        saveUsers($users);

        header("Location: connexion.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>
    <link rel="stylesheet" href="../css/creation.css">
</head>
<body>
<header class="main-header">
</header>

<main class="main-content">
    <section class="form-section">
        <h1>Créer un compte</h1>
        <form action="" method="POST" class="form-container">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required class="form-input">

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required class="form-input">

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required class="form-input">

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required class="form-input">

            <button type="submit" class="form-button">Créer un compte</button>
        </form>

        <?php
        if (isset($error)) {
            echo "<p class='error-message'>$error</p>";
        }
        ?>
    </section>
</main>

<footer class="main-footer">
    <p>&copy; 2024 Votre Organisation. Tous droits réservés.</p>
</footer>
</body>
</html>
