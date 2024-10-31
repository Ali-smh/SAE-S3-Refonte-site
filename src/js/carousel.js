document.addEventListener("DOMContentLoaded", function () {
    const flecheGauche = document.querySelector(".prev-btn");
    const flecheDroite = document.querySelector(".next-btn");

    const conteneurPrincipal = document.querySelector(".main-cards");
    const conteneurSecondaire = document.querySelector(".secondary-cards");

    flecheGauche.addEventListener("click", () => deplacerCarteGauche(conteneurPrincipal, conteneurSecondaire));
    flecheDroite.addEventListener("click", () => deplacerCarteDroite(conteneurPrincipal, conteneurSecondaire));

    function deplacerCarteGauche(conteneurPrincipal, conteneurSecondaire) {
        if (conteneurPrincipal.children.length === 3 && conteneurSecondaire.children.length === 2) {
            const carteADepacer = conteneurPrincipal.children[0];
            conteneurSecondaire.appendChild(carteADepacer);

            if (conteneurSecondaire.children.length > 2) {
                const carteDeRetour = conteneurSecondaire.children[0];
                conteneurPrincipal.appendChild(carteDeRetour);
            }
        }
    }

    function deplacerCarteDroite(conteneurPrincipal, conteneurSecondaire) {
        if (conteneurPrincipal.children.length === 3 && conteneurSecondaire.children.length === 2) {
            const carteADepacer = conteneurPrincipal.children[conteneurPrincipal.children.length - 1];
            conteneurSecondaire.insertBefore(carteADepacer, conteneurSecondaire.firstChild);

            if (conteneurSecondaire.children.length > 2) {
                const carteDeRetour = conteneurSecondaire.children[conteneurSecondaire.children.length - 1];
                conteneurPrincipal.insertBefore(carteDeRetour, conteneurPrincipal.firstChild);
            }
        }
    }
});
