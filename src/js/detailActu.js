document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("modal");
    const modalTitre = document.getElementById("modal-title");
    const modalDescription = document.getElementById("modal-description");
    const boutonFermer = document.querySelector(".close-btn");

    function ouvrirModal(titre, description) {
        modalTitre.textContent = titre;
        modalDescription.textContent = description;
        modal.style.display = "block";
    }

    boutonFermer.addEventListener("click", () => {
        modal.style.display = "none";
    });

    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    const cartes = document.querySelectorAll(".card");
    cartes.forEach((carte) => {
        carte.addEventListener("click", () => {
            const titre = carte.querySelector("h3").textContent;
            const description = carte.getAttribute("data-description");
            ouvrirModal(titre, description);
        });
    });
});
