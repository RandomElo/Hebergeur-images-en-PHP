<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=hebergeur_images;charset=utf8', 'root', '');
} catch (Exception $erreur) {
    die('Erreur : ' . $erreur->getMessage());
};
if (!empty($_SESSION['utilisateur'])) {
    $sessionUtilisateur = $_SESSION['utilisateur'];
    $reqVerificationUtilisateur = $bdd->prepare('SELECT * FROM utilisateurs WHERE id = ?') or die(print_r($bdd->errorInfo(), true));
    $reqVerificationUtilisateur->execute([$sessionUtilisateur]);
    $resultat = $reqVerificationUtilisateur->fetchAll();
    if (count($resultat) == 0) {
        session_destroy();
        header('location: accueil');
        exit();
    }
}
