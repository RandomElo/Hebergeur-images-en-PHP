<?php
if (!empty($_COOKIE['utilisateur'])) {
    header('location: espace-sauvegarde');
    exit();
}
try {
    $bdd = new PDO('mysql:host=localhost;dbname=hebergeur_images;charset=utf8', 'root', '');
} catch (Exception $erreur) {
    die('Erreur : ' . $erreur->getMessage());
}
if (isset($_POST['pseudo']) && isset($_POST['motdepasse'])) {
    $pseudo = $_POST['pseudo'];
    $motDePasse = $_POST['motdepasse'];

    $reqConnexion = $bdd->prepare('SELECT * FROM utilisateurs WHERE pseudo = ?') or die(print_r($bdd->errorInfo(), true));
    $reqConnexion->execute([$pseudo]);

    $resultat = $reqConnexion->fetchAll();
    if (count($resultat) == 0) {
        header('location: ?erreur=1');
        exit();
    } elseif (count($resultat) == 1) {
        $utilisateur = $resultat[0];
        echo $utilisateur[2];
        if (password_verify($motDePasse, $utilisateur[2])) {
            setcookie('utilisateur', $utilisateur[0], time() + 3600 * 24 * 2, '/', '', false, true);
            header('location: espace-sauvegarde');
            exit();
        } else {
            header('location: ?erreur=1');
            exit();
        }
    } else {
        echo count($resultat);
    }
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
    <title>Connexion - Hébergeur d'images</title>
</head>

<body>
    <h1 id="titre">Connexion</h1>
    <form action="connexion.php" method="post">
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
                        echo "Le pseudo ou le mot de passe est incorrect";
                    }
                    ?>
                </p>
            </div>
        <?php } ?>

        <p id="changementMode"><span class="gras">Vous n'avez pas encore de compte ?</span> <a href="inscription">Inscrivez-vous</a></p>

        <button type="submit" class="bouton">Connexion</button>
    </form>
</body>

</html>