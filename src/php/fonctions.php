<?php


function loadUsers($file = "../data/users.json") {
 
    $filePath = __DIR__ . "/" . $file;
    return file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];
}

function saveUsers($users, $file = "data/users.json") {
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}

function userExists($email, $users) {
    return isset($users[$email]);
}

function createUser($email, $password, $nom, $prenom) {
    return [
        "id" => uniqid(),
        "nom" => $nom,
        "prenom" => $prenom,
        "password" => password_hash($password, PASSWORD_DEFAULT)
    ];
}
?>
