import {lesAssociations} from "./lesAssociations.js";

//--------------Les cartes----------------

function creerElemAvecTag(tagName, content) {
    const elem = document.createElement(tagName);
    elem.textContent = content;
    return elem;
}

function creerElemAvecIcone(iconClass, content) {
    const elem = document.createElement('div'); // Crée un conteneur pour le texte et l'icône
    elem.classList.add('iconeTexte'); // Classe pour styliser cet élément

    const icon = document.createElement('i'); // Cree un élément icône
    icon.classList.add('fas', iconClass); // Ajoute les classes de FontAwesome à l'icône

    elem.append(creerElemAvecTag('span',content)); // Ajoute le texte dans le conteneur
    elem.append(icon); // Ajoute l'icône après le texte

    return elem;
}

function creerCarteAssociation(association) {
    const carte = document.createElement('div');
    carte.classList.add('assoCarte');
    //Nom
    carte.append(creerElemAvecTag('h3', association.nom));

    //Sauter une ligne
    carte.append(document.createElement('br'));

    //Adresse
    const adresseElem = creerElemAvecIcone('fa-map-marker-alt', association.adresse);
    adresseElem.classList.add('adressse'); // Ajouter une classe spécifique pour l'adresse
    adresseElem.addEventListener('click', (event) => {
        openMapDialog(association);
    });
    carte.append(adresseElem);

    //Numero
    if (association.numero) {
        carte.append(creerElemAvecIcone('fa-phone', association.numero));
    }

    //Mail
    if (association.mail) {
        carte.append(creerElemAvecIcone('fa-envelope', association.mail));
    }

    //President
    carte.append(creerElemAvecIcone('fa-user-tie',  association.president));

    return carte;
}

//----------Afficher les cartes------------

const section = document.querySelector('.assoc');

for (const association of lesAssociations) {
    section.append(creerCarteAssociation(association));
}



//---------------------Map-----------------

function openMapDialog(association) {
    document.body.classList.add('no-scroll');
    // Créer l'élément dialog
    const dialog = document.createElement('dialog');
    dialog.id = 'map';

    // Contenu du dialog
    const title = creerElemAvecTag('h2', association.nom);

    // Bouton de fermeture
    const closeButton = document.createElement('button');
    closeButton.id = 'closeButton';
    closeButton.innerHTML = '&times;';

    // Image de l'association
    const imgMap = document.createElement('img');
    imgMap.id = 'imgMap';
    imgMap.src = association.img;
    imgMap.alt = 'Image';

    // Conteneur pour le pied du dialog
    const dialogFooter = document.createElement('div');
    dialogFooter.classList.add('dialogFooter');

    // Adresse
    const adresse = creerElemAvecTag('p', association.adresse);

    // Bouton "Voir"
    const voirButton = creerElemAvecTag('button', 'Voir');
    voirButton.id = 'voir';

    // Ajouter les éléments dans le dialog
    dialog.appendChild(title);
    dialog.appendChild(closeButton);
    dialog.appendChild(imgMap);
    dialogFooter.appendChild(adresse);
    dialogFooter.appendChild(voirButton);
    dialog.appendChild(dialogFooter);
    document.body.appendChild(dialog);    // Ajouter le dialog au body

    dialog.showModal();    // Afficher le dialog


    // Fermer le dialog en cliquant sur le bouton
    document.getElementById('closeButton').addEventListener('click', () => {
        document.body.classList.remove('no-scroll');
        dialog.close(); // Fermer le dialog
        document.body.removeChild(dialog); // Supprimer le dialog du DOM après fermeture
    });

    document.getElementById('voir').addEventListener('click', () => {
        window.open(association.lien, '_blank');
    });
}












//---------------Filtre------------------
const filtre = document.querySelector('#filtre');

const regions = ["Auvergne Rhone Alpes", "Bourgogne Franche Comte",
        "Centre Val de Loire", "Grand Est", "Hauts de france", "Ile de France",
        "Normandie", "Nouvelle-Aquitaine", "Occitanie ",
        "Pays de la Loire", "Provence Alpes Cote d'Azur", "Ouest"];

    // Créer et ajouter le titre
    filtre.append(creerElemAvecTag('h3', "Région"));

    // La ligne
    filtre.appendChild(document.createElement("hr"));

    // Créer et ajouter les cases à cocher
    regions.forEach(region => {
        const label = document.createElement("label");
        label.innerHTML = `<input type="checkbox" name="region" value="${region}"> ${region}`;
        filtre.appendChild(label);
    });



document.addEventListener("DOMContentLoaded", function () {
    const boutonFiltre = document.querySelector("#boutonFiltre button");
    const filtre = document.getElementById("filtre");

    // Initialiser le filtre comme masqué
    filtre.style.display = "none";

    // Attacher un événement de clic au bouton pour afficher/masquer le filtre
    boutonFiltre.addEventListener("click", function () {
        // Vérifier l'état actuel du filtre et le changer
        if (filtre.style.display === "none") {
            filtre.style.display = "block";  // Afficher le filtre
        } else {
            filtre.style.display = "none";   // Masquer le filtre
        }
    });

    // Cacher le filtre si on clique en dehors du bouton et de l'élément filtre
    document.addEventListener("click", function (event) {
        if (!filtre.contains(event.target) && !boutonFiltre.contains(event.target)) {
            filtre.style.display = "none";   // Masquer le filtre
        }
    });
});
