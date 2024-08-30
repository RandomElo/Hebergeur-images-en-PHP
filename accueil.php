<?php
// Configure les paramètres du cookie de session
session_set_cookie_params([
    'lifetime' => 0, // Durée de vie, 0 signifie jusqu'à la fermeture du navigateur
    'path' => '/', // Chemin du cookie
    'domain' => '', // Domaine du cookie, par défaut c'est l'hôte actuel
    'secure' => true, // Envoi uniquement via HTTPS, mettre à false si en HTTP
    'httponly' => true, // Le cookie n'est pas accessible via JavaScript
    'samesite' => 'Strict' // Pour éviter les attaques CSRF, options: 'Strict', 'Lax', ou 'None'
]);
// Démarre la session
session_start();
include "fonctions/verifSession.php";
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
    <link rel="stylesheet" href="styles/accueil.css">
    <title>Accueil - Hébergeur d'images</title>
</head>

<body>
    <h1 id="titre">Bienvenue sur l'hébergeur d'images</h1>
    <p id="phraseAccroche">Vous bénéficiez d'un hébergement gratuit de 5 Mo</p>
    <a href="connexion" class="bouton">Ajouter une image</a>
</body>

</html>