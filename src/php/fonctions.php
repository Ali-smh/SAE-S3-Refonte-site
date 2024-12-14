<?php

// Charge les utilisateurs depuis le fichier JSON
function loadUsers($file = "../data/users.json") {
    // Utilise __DIR__ pour garantir le chemin absolu
    $filePath = __DIR__ . "/" . $file;
    return file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];
}

// Sauvegarde les utilisateurs dans le fichier JSON
function saveUsers($users, $file = "data/users.json") {
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}

// Vérifie si l'utilisateur existe dans le fichier JSON
function userExists($email, $users) {
    return isset($users[$email]);
}

// Crée un nouvel utilisateur
function createUser($email, $password, $nom, $prenom) {
    return [
        "id" => uniqid(),
        "nom" => $nom,
        "prenom" => $prenom,
        "password" => password_hash($password, PASSWORD_DEFAULT)
    ];
}
?>
