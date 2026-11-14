# Hébergeur d'images en PHP

Petit site pour envoyer et retrouver ses images. Projet fait pour s'exercer au PHP et à MySQL.

## Fonctionnalités

- Inscription et connexion.
- Envoi d'images dans un espace personnel.
- Liste des images de l'utilisateur.

## Installation

Il faut PHP et un serveur MySQL ou MariaDB.

1. Cloner le dépôt dans le dossier du serveur web.
2. Importer la base : le fichier `hebergeur_images.sql` contient les deux tables du projet, `utilisateurs` et `images`.
3. Vérifier l'accès à la base : la connexion est écrite en dur (`localhost`, base `hebergeur_images`, utilisateur `root` sans mot de passe) dans `connexion.php`, `inscription.php` et `fonctions/verifSession.php`. À adapter si ton serveur MySQL est configuré autrement.
4. Vérifier que `public/images/` est accessible en écriture par le serveur web.
5. Ouvrir `index.php` dans le navigateur.
