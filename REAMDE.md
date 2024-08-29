# Hébérgeur d'images

## Comment configurer MySQL

1. Crée une BDD **hebergeur_images**
2. Crée une table **utilisateurs** avec les champs : id, pseudo (unique), mot_de_passe, espace_dispo (valeur par défaut 20 000 000)
3. Crée une table **images** avec les champs : id, id_utilisateur, nom_image, taille
