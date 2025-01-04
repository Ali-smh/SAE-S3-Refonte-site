<?php
require_once '../app/fonctions.php';
if (!session_id())
    session_start();

global $trousseau;
createTrousseau($trousseau);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    //debugForm($_POST, "post");

    $account['firstname'] = ($_POST['prenom']);
    $account['name'] = $_POST['nom'];
    $account['email'] = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $account['password'] = $_POST['password'];


    if(filter_var($account['email'], FILTER_VALIDATE_EMAIL)) {
        if(checkEmail($trousseau, $account)) {
            echo "<div class='alert alert-warning'>Un compte existe avec cet email</div>";
        }
        else {
            addAccount($trousseau, $account);
            $_SESSION['user'] = $account['email'];
            $direction = $_SERVER['HTTP_ORIGIN'];
            header("Location: $direction/index.php");
        }
        echo "<a class='text-primary' href='index.php'>Vous pouvez vous authentifier</a>";
    }
    else {
        echo "<div class='alert alert-warning'>Email non valide</div>";
        echo "<a class='text-primary' href='index.php'>Recommencer l'enregistrement</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>

    <link rel="stylesheet" href="css/creation.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">

    <link rel="icon" href="img/logos/logo_alcool_ecoute.png">

</head>
<body>

<?php
require_once 'header.php';
?>

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

<?php
require_once 'footer.php';
?>

</body>
</html>
