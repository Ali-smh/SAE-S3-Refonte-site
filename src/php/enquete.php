<?php
session_start();

$questions_personnel = [
    "age" => [
        "question" => "Quel âge avez vous ?",
        "type" => "text",
        "placeholder" => "Entrez votre âge"
    ],
    "vie_pro" => [
        "question" => "L’alcool a-t-il un impact sur votre vie professionnelle ?",
        "type" => "radio",
        "options" => ["Oui, fortement", "Oui, légèrement", "Non"]
    ],
    "region" => [
        "question" => "Dans quelle région vivez-vous ?",
        "type" => "select",
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
    "lieu_vie" => [
        "question" => "Quel est votre lieu de vie actuel ?",
        "type" => "select",
        "options" => [
            "Dans la famille en permanence", "Logement indépendant", "Foyer d'accueil médicalisé (FAM)",
            "Maison d'accueil spécialisée (MAS)", "Hospitalisation en psychiatrie", "Autre"
        ]
    ],
    "qualite_vie" => [
        "question" => "Quels aspects impactent votre qualité de vie ?",
        "type" => "checkbox",
        "options" => ["Santé physique", "Santé mentale (dépression, anxiété…)", "Relations familiales",
            "Vie sociale", "Finances", "Emploi ou études", "Aucun impact"]
    ],
    "soutien" => [
        "question" => "De quel type de soutien avez-vous besoin ?",
        "type" => "radio",
        "options" => [
            "Aide psychologique (écoute, thérapie, groupe de parole)",
            "Aide médicale (consultations, traitement)",
            "Aide sociale (logement, finances, démarches administratives)",
            "Accompagnement dans la réinsertion professionnelle ou sociale",
            "Aucun, je me sens autonome"
        ]
    ]
];

$questions_proche = [
    "age" => [
        "question" => "Quel âge a votre proche ?",
        "type" => "text",
        "placeholder" => "Entrez votre âge"
    ],
    "vie_pro_proche" => [
        "question" => "L’alcool a-t-il un impact sur la vie professionnelle de votre proche ?",
        "type" => "radio",
        "options" => ["Oui, fortement", "Oui, légèrement", "Non"]
    ],
    "region" => [
        "question" => "Dans quelle région vie votre proche ?",
        "type" => "select",
        "options" => ["Auvergne Rhône-Alpes", "Bourgogne Franche-Comté", "Bretagne", "Centre-Val de Loire",
            "Corse", "Grand-Est", "Hauts-de-France", "Ile-de-France", "Normandie", "Nouvelle-Aquitaine",
            "Occitanie", "Pays de la Loire", "Provence-Alpes-Côte d'Azur", "Je vis à l'étranger"]
    ],
    "lieu_vie" => [
        "question" => "Quel est son lieu de vie actuel ?",
        "type" => "select",
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
    "qualite_vie" => [
        "question" => "Quels aspects impactent sa qualité de vie ?",
        "type" => "checkbox",
        "options" => ["Santé physique", "Santé mentale (dépression, anxiété…)", "Relations familiales",
            "Vie sociale", "Finances", "Emploi ou études", "Aucun impact"]
    ],
    "soutien" => [
        "question" => "De quel type de soutien a-t-il besoin ?",
        "type" => "radio",
        "options" => [
            "Aide psychologique (écoute, thérapie, groupe de parole)",
            "Aide médicale (consultations, traitement)",
            "Aide sociale (logement, finances, démarches administratives)",
            "Accompagnement dans la réinsertion professionnelle ou sociale",
            "Aucun, je me sens autonome"
        ]
    ]
];

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

    $keys = array_keys($_SESSION['path']);
    $current_question_key = $keys[$_SESSION['current_index']];
    $_SESSION['responses'][$current_question_key] = $_POST['response'] ?? [];


    if ($current_question_key === "relation_alcool") {
        if ($_POST['response'] === "Je suis personnellement concerné(e)") {
            $_SESSION['path'] = array_merge($_SESSION['path'], $questions_personnel);
        } elseif ($_POST['response'] === "Un membre de ma famille ou un proche est concerné") {
            $_SESSION['path'] = array_merge($_SESSION['path'], $questions_proche);
        }
    }


    $_SESSION['current_index']++;
    if ($_SESSION['current_index'] >= count($_SESSION['path'])) {
        // Réinitialiser la session
        session_unset();
        session_destroy();
        session_start();

        header("Location: connexion.php");
        exit();
    }
}


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
    <link rel="stylesheet" href="/src/css/enquete.css">
</head>
<body>
<h1>Enquête - Alcool Écoute Joie et Santé</h1>

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

</body>
</html>
