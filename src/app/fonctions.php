<?php
$trousseau = __DIR__
    . DIRECTORY_SEPARATOR
    . 'data'
    . DIRECTORY_SEPARATOR . 'account.ndjson';

function addAccount(array $account, $pdo): void
{
    $account['password'] = password_hash($account['password'], PASSWORD_DEFAULT);

    // Préparation de la requête d'insertion
    $query = "INSERT INTO ADHERENTS (nom, prenom, email, mdp) VALUES (:nom, :prenom, :email, :mdp)";

    $stmt = $pdo->prepare($query);

    // Exécution de la requête
    $stmt->execute([
        ':nom' => $account['name'],
        ':prenom' => $account['firstname'],
        ':email' => $account['email'],
        ':mdp' => $account['password']
    ]);
}

function userExists(array $user, $pdo) : bool {
    $query = "SELECT * FROM ADHERENTS WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':email' => $user['email']]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        return password_verify($user['password'], $data['mdp']);
    }

    return false;
}

function checkEmail(array $user, $pdo) : bool {
    $query = "SELECT COUNT(*) FROM ADHERENTS WHERE email = :email";

    $stmt = $pdo->prepare($query);
    $stmt->execute([':email' => $user['email']]);

    return $stmt->fetchColumn() > 0;
}

function getQuestions($pdo, $categorie) {
    $questions = [];
    $stmt = $pdo->prepare("
        SELECT q.question_id, q.cle_question, q.libelle_question, q.type_question, q.placeholder
        FROM QUESTIONS q
        WHERE q.libelle_categorie = :categorie
    ");

    $stmt->execute([':categorie' => $categorie]);
    $questionsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($questionsData as $question) {
        $questionId = $question['question_id'];
        $stmtOptions = $pdo->prepare("SELECT valeur_option FROM OPTIONS WHERE question_id = :question_id");
        $stmtOptions->execute(['question_id' => $questionId]);
        $options = $stmtOptions->fetchAll(PDO::FETCH_COLUMN);

        $questions[$question['cle_question']] = [
            'question' => $question['libelle_question'],
            'type' => $question['type_question'],
            'placeholder' => $question['placeholder'],
            'options' => $options
        ];
    }

    return $questions;
}
function getAdherentIdByEmail($pdo, $email) {
    // Préparer la requête SQL pour rechercher l'adhérent par email
    $query = "SELECT adherent_id FROM ADHERENTS WHERE email = :email";

    // Préparer la requête
    $stmt = $pdo->prepare($query);

    // Lier l'email à la requête
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    // Exécuter la requête
    $stmt->execute();

    // Vérifier si un adhérent est trouvé
    if ($stmt->rowCount() > 0) {
        // Récupérer l'id de l'adhérent
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['adherent_id'];
    } else {
        // Aucun adhérent trouvé
        return null;
    }
}



/**
 * Sauvegarde les réponses dans la base de données.
 *
 * @param PDO $pdo L'objet PDO pour interagir avec la base de données.
 * @param array $responses Les réponses à sauvegarder.
 * @param int $adherentId L'ID de l'adhérent répondant.
 * @param string $categorie La catégorie des questions (Personnel ou Proche).
 */
function saveResponses(PDO $pdo, array $responses, int $adherentId, string $categorie)
{
    try {
        $pdo->beginTransaction();

        foreach ($responses as $cleQuestion => $valeurReponse) {
            // Récupérer l'ID de la question à partir de sa clé
            $query = "SELECT question_id FROM QUESTIONS WHERE cle_question = :cleQuestion AND categorie = :categorie";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':cleQuestion' => $cleQuestion,
                ':categorie' => $categorie,
            ]);
            $questionId = $stmt->fetchColumn();

            if ($questionId) {
                if (is_array($valeurReponse)) {
                    // Pour les réponses multiples (checkbox)
                    foreach ($valeurReponse as $reponse) {
                        $insertQuery = "INSERT INTO REPONSES (valeur_reponse, question_id, adherent_id) 
                                        VALUES (:valeurReponse, :questionId, :adherentId)";
                        $insertStmt = $pdo->prepare($insertQuery);
                        $insertStmt->execute([
                            ':valeurReponse' => $reponse,
                            ':questionId' => $questionId,
                            ':adherentId' => $adherentId,
                        ]);
                    }
                } else {
                    // Pour les réponses uniques (radio, text, select)
                    $insertQuery = "INSERT INTO REPONSES (valeur_reponse, question_id, adherent_id) 
                                    VALUES (:valeurReponse, :questionId, :adherentId)";
                    $insertStmt = $pdo->prepare($insertQuery);
                    $insertStmt->execute([
                        ':valeurReponse' => $valeurReponse,
                        ':questionId' => $questionId,
                        ':adherentId' => $adherentId,
                    ]);
                }
            }
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erreur lors de la sauvegarde des réponses : " . $e->getMessage());
    }
}

function hasAlreadyResponded($pdo, $adherentId) {
    $query = "SELECT COUNT(*) FROM REPONSES WHERE adherent_id = :adherentId";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':adherentId' => $adherentId]);
    return $stmt->fetchColumn() > 0;
}

function getTypeAdherentByEmail($pdo, $email) {
    // Préparer la requête SQL pour rechercher le rôle de l'adhérent par email
    $query = "SELECT libelle_type FROM ADHERENTS WHERE email = :email";

    // Préparer la requête
    $stmt = $pdo->prepare($query);

    // Exécuter la requête
    $stmt->execute([':email' => $email]);

    // Vérifier si un utilisateur est trouvé
    if ($stmt->rowCount() > 0) {
        // Récupérer le rôle de l'adhérent
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retourner le rôle de l'adhérent
        return $row['libelle_type'];
    }

    // Si l'utilisateur n'est pas trouvé
    return null;  // ou vous pouvez renvoyer 'user' par défaut
}


?>
