document.addEventListener("DOMContentLoaded", function () {
    const onglets = document.querySelectorAll(".tab");

    onglets.forEach(function (onglet) {
        onglet.addEventListener("click", function (event) {
            event.preventDefault();

            const cibleId = onglet.getAttribute("id");
            let sectionCible = "";

            if (cibleId === "actualite-tab") {
                sectionCible = document.querySelector("#actualite");
            } else if (cibleId === "evenements-tab") {
                sectionCible = document.querySelector(".events-section");
            }

            if (sectionCible) {
                window.scrollTo({
                    top: sectionCible.offsetTop - 60,
                    behavior: "smooth"
                });
            }
        });
    });
});
