<?php
header('location: accueil');
// Configure les paramètres du cookie de session
session_set_cookie_params([
    'lifetime' => 0, // Durée de vie, 0 signifie jusqu'à la fermeture du navigateur
    'path' => '/', // Chemin du cookie
    'domain' => '', // Domaine du cookie, par défaut c'est l'hôte actuel
    'secure' => true, // Envoi uniquement via HTTPS, mettre à false si en HTTP
    'httponly' => true, // Le cookie n'est pas accessible via JavaScript
    'samesite' => 'Lax' // Pour éviter les attaques CSRF, options: 'Strict', 'Lax', ou 'None'
]);
// Démarre la session
session_start();
