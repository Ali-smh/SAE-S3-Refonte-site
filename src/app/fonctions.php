<?php
$trousseau = __DIR__
    . DIRECTORY_SEPARATOR
    . 'data'
    . DIRECTORY_SEPARATOR . 'account.ndjson';

function createTrousseau(string $trousseau)  {
    $directory = dirname($trousseau);
    if(!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
    if(!file_exists($trousseau)) {
        touch($trousseau);
    }
}

function addAccount(string $trousseau, array $account) {
    $account['password'] = password_hash($account['password'], PASSWORD_DEFAULT);
    file_put_contents($trousseau, json_encode($account) . PHP_EOL, FILE_APPEND);
}

function userExists(string $trousseau, array $user) : bool {
    $jsonContent = file_get_contents("$trousseau");
    if($jsonContent !== false) {
        $lines = explode(PHP_EOL, $jsonContent);
        foreach($lines as $line) {
            if(!empty($line)) {
                $data = json_decode($line, true);
                if(json_last_error() === JSON_ERROR_NONE) {
                    if(
                        $user['email'] === $data['email'] &&
                        password_verify($user['password'], $data['password'])
                    ) {
                        return true;
                    }
                } else {
                    echo "Erreur de décodage JSON pour la ligne : $line\n";
                }
            }
        }
    }
    return false;
}

function checkEmail(string $trousseau, array $user) : bool {
    $jsonContent = file_get_contents("$trousseau");

    if($jsonContent !== false) {
        $lines = explode(PHP_EOL, $jsonContent);
        foreach($lines as $line) {
            if(!empty($line)) {
                $data = json_decode($line, true);
                if(json_last_error() === JSON_ERROR_NONE) {
                    if($user['email'] === $data['email']) {
                        return true;
                    }
                } else {
                    echo "Erreur de décodage JSON pour la ligne : $line\n";
                }
            }
        }
    }
    return false;
}
?>
