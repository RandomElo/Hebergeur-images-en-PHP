<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=hebergeur_images;charset=utf8', 'root', '');
} catch (Exception $erreur) {
    die('Erreur : ' . $erreur->getMessage());
};
if (!empty($_COOKIE['utilisateur'])) {
    $cookie = $_COOKIE['utilisateur'];
    $reqVerificationUtilisateur = $bdd->prepare('SELECT * FROM utilisateurs WHERE id = ?') or die(print_r($bdd->errorInfo(), true));
    $reqVerificationUtilisateur->execute([$cookie]);
    $resultat = $reqVerificationUtilisateur->fetchAll();
    if (count($resultat) == 0) {
        setcookie("utilisateur", "", time() - 3600, '/');
        header('location: accueil');
        exit();
    }
}
