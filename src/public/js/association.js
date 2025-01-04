import {lesAssociations} from "./lesAssociations.js";

// -------------- Fonctions Utilitaires ----------------

function creerElemAvecTag(tagName, content) {
    const elem = document.createElement(tagName);
    elem.textContent = content;
    return elem;
}

function creerElemAvecIcone(iconClass, content, estCopier = false) {
    const elem = document.createElement('div');
    elem.classList.add('iconeTexte');

    const icon = document.createElement('i');
    icon.classList.add('fas', iconClass);

    elem.append(creerElemAvecTag('span', content));
    elem.append(icon);

    if (estCopier) {
        elem.classList.add('copier');

        elem.addEventListener('click', () => {
            navigator.clipboard.writeText(content)
        });

        // Changer l'icône au survol pour indiquer la copie
        elem.addEventListener('mouseenter', () => {
            icon.classList.remove(iconClass);            // Enlever l'icône d'origine
            icon.classList.add('fa-copy');               // Ajouter l'icône de copie
        });

        // Remettre l'icône d'origine lorsque la souris quitte l'élément
        elem.addEventListener('mouseleave', () => {
            icon.classList.remove('fa-copy');            // Enlever l'icône de copie
            icon.classList.add(iconClass);               // Remettre l'icône d'origine
        });
    }

    return elem;
}

//--------------Les cartes----------------

function creerCarteAssociation(association) {
    const carte = document.createElement('div');
    carte.classList.add('assoCarte');
    //Nom
    carte.append(creerElemAvecTag('h3', association.nom));

    //Sauter une ligne
    carte.append(document.createElement('br'));

    //Adresse
    const adresseElem = creerElemAvecIcone('fa-map-marker-alt', association.adresse);
    adresseElem.classList.add('adressse');
    adresseElem.addEventListener('click', (event) => {
        openMapDialog(association);
    });
    carte.append(adresseElem);

    //Numero
    if (association.numero) {
        carte.append(creerElemAvecIcone('fa-phone', association.numero,true));
    }

    //Mail
    if (association.mail) {
        carte.append(creerElemAvecIcone('fa-envelope', association.mail,true ));
    }

    //President
    carte.append(creerElemAvecIcone('fa-user-tie',  association.president));

    return carte;
}

//----------Afficher les cartes------------

const section = document.querySelector('.assoc');

function afficherCartes(associations) {
    section.innerHTML = '';
    if (associations.length === 0) {
        const message = creerElemAvecTag('p', "Aucune association trouvée pour les régions sélectionnées.");
        section.append(message);
    } else {
        associations.forEach(association => section.append(creerCarteAssociation(association)));
    }
}
afficherCartes(lesAssociations);


//---------------Filtre------------------

document.getElementById("selectAll").addEventListener("change", function () {
    const isChecked = this.checked;
    document.querySelectorAll("input[name='region']").forEach(checkbox => checkbox.checked = isChecked);
    filtrerParRegion();
});

document.querySelectorAll("input[name='region']").forEach(checkbox => {
    checkbox.addEventListener("change", filtrerParRegion);
});

function filtrerParRegion() {
    const regionsSelectionnees = Array.from(document.querySelectorAll("input[name='region']:checked"))
        .map(input => input.value);

    if (regionsSelectionnees.length === 0) {
        section.innerHTML = '';
        section.append(creerElemAvecTag('p', "Veuillez cocher au moins une région pour afficher les associations."));
        return;
    }

    const associationsFiltrees = lesAssociations.filter(association =>
        regionsSelectionnees.includes(association.region)
    );
    afficherCartes(associationsFiltrees);
}

//Bouton Filtre
document.addEventListener("DOMContentLoaded", function () {
    const boutonFiltre = document.querySelector("#boutonFiltre button");
    const filtre = document.getElementById("filtre");

    filtre.style.display = "none";

    boutonFiltre.addEventListener("click", function () {
        filtre.style.display = filtre.style.display === "none" ? "block" : "none";
    });

    document.addEventListener("click", function (event) {
        if (!filtre.contains(event.target) && !boutonFiltre.contains(event.target)) {
            filtre.style.display = "none";
        }
    });
});



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
    closeButton.addEventListener('click', () => {
        document.body.classList.remove('no-scroll');
        dialog.close();
        document.body.removeChild(dialog);
    });

    voirButton.addEventListener('click', () => {
        window.open(association.lien, '_blank');
    });
}

//---------------------Recherche-----------------

function rechercherAssociations() {
    const recherche = document.getElementById('recherche').value.toLowerCase();

    const associationsFiltrees = lesAssociations.filter(association => {
        return association.nom.toLowerCase().includes(recherche) ||
            association.president.toLowerCase().includes(recherche);
    });

    afficherCartes(associationsFiltrees);
}

document.getElementById('recherche').addEventListener('input', rechercherAssociations);





