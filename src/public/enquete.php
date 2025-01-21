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
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Debugging avancé
} catch (PDOException $e) {
    die("Échec de la connexion à la base de données : " . $e->getMessage());
}

$adherentId = getAdherentIdByEmail($pdo, $_SESSION['user']);

// Récupération des questions
$questions_personnel = getQuestions($pdo, 'Personnel');
$questions_proche = getQuestions($pdo, 'Proche');

// Initialisation des variables de session
if (!isset($_SESSION['responses'])) {
    $_SESSION['responses'] = [];
    $_SESSION['current_index'] = 0;
    $_SESSION['path'] = [
        "relation_alcool" => [
            "question" => "Quelle est votre relation avec les problématiques liées à l'alcool ?",
            "type" => "radio",
            "options" => [
                "Je suis personnellement concerné(e)",
                "Un membre de ma famille ou un proche est concerné"
            ]
        ]
    ];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération de la clé de la question actuelle
    $keys = array_keys($_SESSION['path']);
    $current_question_key = $keys[$_SESSION['current_index']];

    // Enregistrement de la réponse
    if (isset($_POST['responses']) && is_array($_POST['responses'])) {
        // Réponses multiples (checkboxes)
        $_SESSION['responses'][$current_question_key] = $_POST['responses'];
    } else {
        // Réponse unique
        $_SESSION['responses'][$current_question_key] = $_POST['response'] ?? null;
    }

    // Chemin de progression après la question "relation_alcool"
    if ($current_question_key === "relation_alcool") {
        if ($_POST['response'] === "Je suis personnellement concerné(e)") {
            $_SESSION['path'] = array_merge($_SESSION['path'], $questions_personnel);
            $_SESSION['categorie'] = 'Personnel';
        } elseif ($_POST['response'] === "Un membre de ma famille ou un proche est concerné") {
            $_SESSION['path'] = array_merge($_SESSION['path'], $questions_proche);
            $_SESSION['categorie'] = 'Proche';
        }
    }

    // Avancer à la question suivante
    $_SESSION['current_index']++;

    // Si toutes les questions ont été répondues
    if ($_SESSION['current_index'] >= count($_SESSION['path'])) {
        if ($_SESSION['categorie'] == 'Personnel') {
            saveResponses($pdo, $_SESSION['responses'], $adherentId, 'Personnel');
        } else {
            saveResponses($pdo, $_SESSION['responses'], $adherentId, 'Proche');
        }

        // Réinitialisation de la session et redirection
        session_unset();
        session_destroy();
        session_start();
        header("Location: merci.php");
        exit;
    }
}

// Préparation de la question actuelle
$keys = array_keys($_SESSION['path']);
$current_question_key = $keys[$_SESSION['current_index']];
$current_question = $_SESSION['path'][$current_question_key];
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquête</title>
    <link rel="stylesheet" href="css/enquete.css">
    <link rel="icon" href="img/logos/logo_alcool_ecoute.png">
</head>
<body>
<h1>Enquête - Alcool Écoute Joie et Santé</h1>

<?php if (hasAlreadyResponded($pdo, $adherentId)):?>
    <p>Vous avez déjà répondu à ce questionnaire. Merci pour votre participation.</p>
<?php else: ?>
    <div class="progress-bar">
        <div class="progress-bar-inner" style="width: <?= round($_SESSION['current_index'] / count($_SESSION['path']) * 100) ?>%;">
            <?= round($_SESSION['current_index'] / count($_SESSION['path']) * 100) ?>%
        </div>
    </div>

    <form method="POST">
        <p><?= htmlspecialchars($current_question['question']) ?></p>
        <!-- Boutons radio -->
        <?php if ($current_question['type'] === "radio"): ?>
            <?php foreach ($current_question['options'] as $index => $option): ?>
                <div class="form-option">
                    <input type="radio" id="option-<?= $index ?>" name="response" value="<?= htmlspecialchars($option) ?>" required>
                    <label for="option-<?= $index ?>"><?= htmlspecialchars($option) ?></label>
                </div>
            <?php endforeach; ?>
            <!-- choix age -->
        <?php elseif ($current_question['type'] === "text"): ?>
            <input type="number" name="response" placeholder="<?= htmlspecialchars($current_question['placeholder']) ?>" required min="0">
            <!-- Liste deroulante -->
        <?php elseif ($current_question['type'] === "select"): ?>
            <select name="response" required>
                <?php foreach ($current_question['options'] as $option): ?>
                    <option value="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></option>
                <?php endforeach; ?>
            </select>
            <!-- Cases à cocher -->
        <?php elseif ($current_question['type'] === "checkbox"): ?>
            <?php foreach ($current_question['options'] as $index => $option): ?>
                <div class="form-option">
                    <input type="checkbox" id="checkbox-<?= $index ?>" name="responses[]" value="<?= htmlspecialchars($option) ?>">
                    <label for="checkbox-<?= $index ?>"><?= htmlspecialchars($option) ?></label>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <button type="submit">Suivant</button>
    </form>
<?php endif; ?>

</body>
</html>
