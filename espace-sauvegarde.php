<?php
include "fonctions/verifCookie.php";
if (empty($_COOKIE['utilisateur'])) {
    header('location: accueil');
    exit();
}
// Gestion de l'enregistrement de l'image
if (isset($_FILES['image'])) {
    if ($_FILES['image']['error'] == 0) {
        $informationImage = pathinfo($_FILES['image']['name']);
        $extensionImage = $informationImage['extension'];
        $extensionTableau = ['png', 'gif', 'jpg', 'jpeg']; // Extension autorisé
        if (in_array($extensionImage, $extensionTableau)) {
            $nomFichier = time() . rand() . '.' . $extensionImage;
            $tailleFichier = $_FILES['image']['size'];

            // Verification si il reste de la place pour enregistrer de l'image
            $reqVerificationPlace = $bdd->prepare('SELECT * FROM utilisateurs WHERE id = ?') or die(print_r($bdd->errorInfo(), true));
            $reqVerificationPlace->execute([$_COOKIE['utilisateur']]);
            $resultat = $reqVerificationPlace->fetch();

            // Calcul de la place restantae
            $calculEspaceDisponible = (int)$resultat['espace_dispo'] - (int)$tailleFichier;
            if ($calculEspaceDisponible >= 0) {
                // Si j'ai assez de place alors j'enregistre le fichier
                // Je déplace le fichier et le nomme correctement
                move_uploaded_file($_FILES['image']['tmp_name'], 'public/images/' . $nomFichier);
                // J'enregistre l'image dans la BDD
                $reqSauvegardeImage = $bdd->prepare('INSERT INTO images(id_utilisateur, nom_image, taille) VALUE (?,?,?)') or die(print_r($bdd->errorInfo(), true));
                $reqSauvegardeImage->execute([$_COOKIE['utilisateur'], $nomFichier, $tailleFichier]);
                // Je change modifie l'espace disponbile de l'utilisateur
                $reqChangementEspaceDisponible = $bdd->prepare('UPDATE utilisateurs SET espace_dispo = ? WHERE id = ?') or die(print_r($bdd->errorInfo(), true));
                $reqChangementEspaceDisponible->execute([$calculEspaceDisponible, $_COOKIE['utilisateur']]);
            } else {
                header('location: ?erreur=3');
                exit();
            }
        };
    } else {
        if ($_FILES['image']['error'] == 1) {
            header('location: ?erreur=1');
            exit();
        } else {
            header('location: ?erreur=2');
            exit();
        }
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
    <link rel="stylesheet" href="styles/espace-sauvegarde.css">

    <link href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/css/lightbox.min.css" rel="stylesheet" />
    <title>Mon espace sauvegarde - Hébergeur d'images</title>
</head>

<body>
    <h1 id="titre">Mon espace sauvegarde</h1>
    <div id="divJauge">
        <?php

        $reqRecuperationTailleDispo = $bdd->prepare('SELECT espace_dispo from utilisateurs WHERE id = ?') or die(print_r($bdd->errorInfo(), true));
        $reqRecuperationTailleDispo->execute([$_COOKIE['utilisateur']]);
        $resultat = $reqRecuperationTailleDispo->fetch();

        $pourcentageJauge = round(((int)$resultat['espace_dispo'] / 20000000) * 100);
        echo '
              <div class="jauge-container">
                <div class="jauge" data-valeur=' . $pourcentageJauge . '></div>
              </div>
              <p id="espaceDispo" class="gras">' . $pourcentageJauge . ' % d\'espace disponible </p>';

        ?>
    </div>
    <a id="boutonAjouterImage" class="bouton">Ajouter une image</a>
    <div id="divResultat"></div>
    <?php if (isset($_GET['erreur'])) { ?>
        <div id="divErreur">
            <p>
                <?php
                switch ($_GET['erreur']) {
                    case 1:
                        // Fichier trop gros
                        echo 'Attention le fichier ne doit pas faire plus de 16 MB';
                        break;
                    case 2:
                        // Autre code d'erreur que le fichier trop gros
                        echo 'Une erreur est survenue lors de l\'enregistrement de l\'image';
                        break;
                    case 3:
                        // Espace de stockage trop remplis
                        echo "Vous n'avez plus assez de place pour enregistré cette image";
                        break;
                }
                ?>
            </p>
        </div>
    <?php } ?>
    <div id="divAffichageImage">
        <?php
        $reqRecuperationImages = $bdd->prepare('SELECT * FROM images WHERE id_utilisateur = ?') or die(print_r($bdd->errorInfo(), true));
        $reqRecuperationImages->execute([$_COOKIE['utilisateur']]);
        $resultat = $reqRecuperationImages->fetchAll();

        if (count($resultat) == 0) {
            echo '<p id="paragrapheAucuneImagesBDD">Vous n\'avez enregistré aucune image</p>';
        } else {
            echo '<p id="paragrapheNbrPhotos">Vous avez ' . count($resultat) . ' photos sauvegardées.</p>';
            $contenantImage = '<div id="divImages">';
            for ($i = 0; $i < count($resultat); $i++) {
                $element = $resultat[$i];
                $contenantImage .= '<a href="public/images/' . $element['nom_image'] . '" data-lightbox="gallery" ><img src="public/images/' . $element['nom_image'] . '" alt="Image" class="imagesEspaceSauvegarde"/></a>';
            }
            echo $contenantImage . '</div>';
        }
        ?>
    </div>
    <script src="scripts/espace-sauvegarde.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/js/lightbox-plus-jquery.min.js"></script>
</body>

</html>