<?php
require_once '../app/fonctions.php';

if (!session_id())
    session_start();

$host = 'localhost';
$dbname = 'enquete_alcool_ecoute';
$username = 'root';
$password = 'soumah123';
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

// Connexion à la base de données
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Échec de la connexion à la base de données : " . $e->getMessage());
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    //debugForm($_POST, "post");

    $account['firstname'] = ($_POST['prenom']);
    $account['name'] = $_POST['nom'];
    $account['email'] = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $account['password'] = $_POST['password'];


    if(filter_var($account['email'], FILTER_VALIDATE_EMAIL)) {
        if(checkEmail($account, $pdo)) {
            echo "<div class='alert alert-warning'>Un compte existe avec cet email</div>";
        }
        else {
            addAccount($account, $pdo);
            $_SESSION['user'] = $account['email'];
            $_SESSION['type'] = getTypeAdherentByEmail($pdo, $account['email']);
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
        <div class="form-layout">
            <img src="img/logos/logo_alcool_ecoute.png" alt="Logo Alcool Écoute" class="form-logo">
            <div class="form-container-wrapper">
                <h1>Crée votre espace Alcool Écoute Joie & Santé</h1>
                <form action="" method="POST" class="form-container">
                    <label for="nom"> <img src="/img/profil.png">Nom :</label>
                    <input type="text" id="nom" name="nom" required class="form-input">

                    <label for="prenom"> <img src="/img/profil.png">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" required class="form-input">

                    <label for="email"> <img src="/img/mail.png">Email :</label>
                    <input type="email" id="email" name="email" required class="form-input">

                    <label for="password"> <img src="/img/mdp.webp">Mot de passe :</label>
                    <input type="password" id="password" name="password" required class="form-input">

                    <button type="submit" class="form-button">Créer un compte</button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php
require_once 'footer.php';
?>

</body>
</html>
