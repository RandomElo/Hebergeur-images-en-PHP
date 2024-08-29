// Gestion du bouton du formulaire
function cliqueBoutonAjouterImage() {
    const boutonAjouterImage = document.querySelector("#boutonAjouterImage");
    boutonAjouterImage.addEventListener("click", () => {
        boutonAjouterImage.textContent = "Fermer le formulaire";
        boutonAjouterImage.style.backgroundColor = "red";
        boutonAjouterImage.id = "boutonFermerFormulaire";

        document.querySelector("#divResultat").innerHTML = /*html*/ `
        <form method="post" action="espace-sauvegarde.php" enctype="multipart/form-data" id="formEnregistrerImage">
            <input type="file" name="image">
            <button type="submit" class="bouton">Sauvegarder</button>
        </form>
        `;

        cliqueBoutonFermerFormulaire();
    });
}
function cliqueBoutonFermerFormulaire() {
    const boutonFermerFormulaire = document.querySelector("#boutonFermerFormulaire");
    boutonFermerFormulaire.addEventListener("click", () => {
        boutonFermerFormulaire.textContent = "Ajouter une image";
        boutonFermerFormulaire.style.backgroundColor = "black";
        boutonFermerFormulaire.id = "boutonAjouterImage";

        document.querySelector("#divResultat").innerHTML = "";

        cliqueBoutonAjouterImage();
    });
}
cliqueBoutonAjouterImage();

// Modification de la taille de la jauge
let jauge = document.querySelector(".jauge");

jauge.style.width = jauge.dataset.valeur + "%";

// Gestion de la visualisaiton des images
