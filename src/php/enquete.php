<?php
session_start();

// Définition des questions
$questions = [
    [
        "type" => "text",
        "question" => "Quel est votre âge ?",
        "name" => "age",
        "placeholder" => "Entrez votre âge"
    ],
    [
        "type" => "select",
        "question" => "Dans quelle région vivez-vous ?",
        "name" => "region",
        "options" => [
            "Auvergne Rhône-Alpes",
            "Bourgogne Franche-Comté",
            "Bretagne",
            "Centre-Val-de-Loire",
            "Corse",
            "Grand-Est",
            "Hauts-de-France",
            "Île-de-France",
            "Normandie",
            "Nouvelle-Aquitaine",
            "Occitanie",
            "Pays de la Loire",
            "Provence-Alpes-Côte d'Azur",
            "Guadeloupe",
            "Guyane",
            "Martinique",
            "Mayotte",
            "La Réunion",
            "Je vis à l'étranger"
        ]
    ],
    [
        "type" => "select",
        "question" => "Quel est votre lieu de vie actuel ?",
        "name" => "lieu_vie",
        "options" => [
            "Dans la famille en permanence",
            "Dans la famille avec une solution d'accueil ou des activités en journée",
            "Dans un logement indépendant",
            "Dans un habitat inclusif",
            "Dans un foyer d'accueil médicalisé (FAM)",
            "Dans une maison d'accueil spécialisée (MAS)",
            "Dans un foyer de vie ou foyer d'hébergement",
            "En IME avec internat",
            "Hospitalisation en psychiatrie",
            "Autre"
        ]
    ],
    [
        "type" => "radio",
        "question" => "Votre lieu de vie correspond-il à votre choix ?",
        "name" => "correspond_choix",
        "options" => ["Oui", "Non"]
    ],
    [
        "type" => "radio",
        "question" => "Votre lieu de vie est-il orienté par une CDAPH ?",
        "name" => "cdaph",
        "options" => ["Oui", "Non", "Pas d'orientation CDAPH"]
    ],
    [
        "type" => "select",
        "question" => "Avez-vous des activités professionnelles ou scolaires ?",
        "name" => "activites",
        "options" => [
            "Scolarité en milieu ordinaire",
            "Scolarité en dispositif spécialisé",
            "Instruction en famille",
            "Scolarité en établissement médico-social",
            "Formation professionnelle",
            "Études supérieures",
            "Activité professionnelle en milieu ordinaire",
            "Activité professionnelle en milieu protégé (ESAT)",
            "Sans activité scolaire ou professionnelle",
            "Autre"
        ]
    ],
    [
        "type" => "checkbox",
        "question" => "Quels aspects impactent votre qualité de vie ?",
        "name" => "qualite_vie",
        "options" => [
            "Tout va bien",
            "Restriction de la vie sociale",
            "Souffrance psychologique",
            "Fatigue, épuisement",
            "Réduction d'activité professionnelle",
            "Coûts financiers importants",
            "Impact négatif sur la fratrie",
            "Conflits familiaux",
            "Maladie ou difficulté pour la personne aidée",
            "Éloignement de la personne aidée"
        ]
    ],
    [
        "type" => "radio",
        "question" => "De quel type d'intervention avez-vous besoin ?",
        "name" => "besoin_soutien",
        "options" => [
            "Aide pour tous les actes de la vie quotidienne",
            "Interventions ponctuelles",
            "Soutien à l'autonomie (logement, santé, loisirs, démarches administratives)",
            "Aucune, je suis autonome"
        ]
    ]
];

// Initialisation de la progression
if (!isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] = 0; // Première question
    $_SESSION['responses'] = []; // Stockage des réponses
}

// Gestion du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = $_POST['response'] ?? '';
    $_SESSION['responses'][] = $response;
    $_SESSION['current_question']++;

    // Si toutes les questions sont terminées
    if ($_SESSION['current_question'] >= count($questions)) {
        $_SESSION['current_question'] = 0;
        header("Location: connexion.php");
        exit();
    }
}

// Données pour la question actuelle
$current_question = $questions[$_SESSION['current_question']];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquête</title>
    <link rel="stylesheet" href="../css/enquete.css">
</head>
<body>
<h1>Enquête</h1>

<!-- Barre de progression -->
<div class="progress-bar">
    <div class="progress-bar-inner" style="width: <?= (($_SESSION['current_question'] / count($questions)) * 100) ?>%;"></div>
</div>

<!-- Affichage de la question -->
<form action="" method="POST">
    <p><?= $current_question['question'] ?></p>

    <?php if ($current_question['type'] === 'text'): ?>
        <input type="text" name="response" placeholder="<?= $current_question['placeholder'] ?>" required>
    <?php elseif ($current_question['type'] === 'select'): ?>
        <select name="response" required>
            <?php foreach ($current_question['options'] as $option): ?>
                <option value="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></option>
            <?php endforeach; ?>
        </select>
    <?php elseif ($current_question['type'] === 'radio'): ?>
        <?php foreach ($current_question['options'] as $option): ?>
            <input type="radio" id="<?= htmlspecialchars($option) ?>" name="response" value="<?= htmlspecialchars($option) ?>" required>
            <label for="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></label>
        <?php endforeach; ?>
    <?php elseif ($current_question['type'] === 'checkbox'): ?>
        <?php foreach ($current_question['options'] as $option): ?>
            <input type="checkbox" id="<?= htmlspecialchars($option) ?>" name="response[]" value="<?= htmlspecialchars($option) ?>">
            <label for="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></label>
        <?php endforeach; ?>
    <?php endif; ?>

    <button type="submit">Suivant</button>
</form>
</body>
</html>
