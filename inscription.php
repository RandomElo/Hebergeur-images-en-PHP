<?php
// Initialisation de la session
session_set_cookie_params([
    'lifetime' => 3600 * 24 * 2,           // Durée de vie du cookie, 0 signifie jusqu'à la fermeture du navigateur
    'path' => '/',             // Chemin du cookie
    'domain' => '',            // Domaine, laissez vide pour le domaine actuel
    'secure' => true,          // Le cookie est envoyé uniquement via HTTPS
    'httponly' => true,        // Le cookie n'est pas accessible via JavaScript
    'samesite' => 'Strict'     // SameSite attribut, peut être 'Strict', 'Lax', ou 'None'
]);
session_start();
include "fonctions/verifSession.php";
if (!empty($_SESSION['utilisateur'])) {
    header('location: espace-sauvegarde');
    exit();
}
try {
    $bdd = new PDO('mysql:host=localhost;dbname=hebergeur_images;charset=utf8', 'root', '');
} catch (Exception $erreur) {
    die('Erreur : ' . $erreur->getMessage()); // die pemet d'arrêter l'éxécution du script et getMessage() permet de décire l'erreur reçu par l'exception lancer
};
if (isset($_POST['pseudo']) && isset($_POST['motdepasse'])) {
    $pseudo = $_POST['pseudo'];
    $motDePasse = $_POST['motdepasse'];

    // Gestion des données du formulaire
    // Verification si le pseudo n'est pas déjà utiliser
    $reqVerificationDisponiblitePseudo = $bdd->prepare('SELECT * FROM utilisateurs WHERE pseudo = ?') or die(print_r($bdd->errorInfo(), true));
    $reqVerificationDisponiblitePseudo->execute([$pseudo]);
    $resultat = $reqVerificationDisponiblitePseudo->fetchAll();
    if (!count($resultat) == 0) {
        header('location: ?erreur=1');
        exit();
    }
    // Hashage du mdp 
    $motdePasseHash = password_hash($motDePasse, PASSWORD_DEFAULT);

    // Requete à la BDD
    $reqCreationCompte = $bdd->prepare('INSERT INTO utilisateurs(pseudo, mot_de_passe) VALUE (?,?)') or die(print_r($bdd->errorInfo(), true));
    $reqCreationCompte->execute([$pseudo, $motdePasseHash]);
    $idUtilisateur = $bdd->lastInsertId();

    $_SESSION['utilisateur'] = $idUtilisateur;
    header('location: espace-sauvegarde');
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Police Nono Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/generale.css">
    <link rel="stylesheet" href="styles/formulaire.css">
    <title>Inscription - Hébergeur d'images</title>
</head>

<body>
    <h1 id="titre">Inscription</h1>
    <form action="inscription.php" method="post">
        <div class="divChampsForm">
            <label for="pseudo">Pseudo :</label>
            <input name="pseudo" id="pseudo" type="text" required>
        </div>

        <div class="divChampsForm">
            <label for="motdepasse">Mot de passe :</label>
            <input name="motdepasse" id="motDePasse" type="password" required>
        </div>

        <?php if (isset($_GET['erreur'])) { ?>
            <div id="divErreur">
                <p>
                    <?php
                    if ($_GET['erreur'] == 1) {
                        echo "Le pseudo n'est pas disponible";
                    }
                    ?>
                </p>
            </div>
        <?php } ?>

        <p id="changementMode"><span class="gras">Vous avez déjà un compte ?</span> <a href="connexion">Connectez-vous</a></p>

        <button type="submit" class="bouton">Inscription</button>
    </form>

</body>

</html>