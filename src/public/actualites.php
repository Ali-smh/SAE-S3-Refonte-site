<?php
if(!session_id())
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Acceuil</title>

    <link rel="stylesheet" href="css/accueil.css">
    <link rel="stylesheet" href ="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/actualites.css">

    <link rel="icon" href="img/logos/logo_alcool_ecoute.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=League+Gothic&family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ledger&display=swap" rel="stylesheet">

    <script src="js/script.js" defer></script>
    <script src="js/carousel.js" defer></script>
    <script src="js/detailActu.js" defer></script>

    <script src="https://kit.fontawesome.com/9e2d0b6ebd.js" crossorigin="anonymous"></script>
</head>
<body>

<?php
require_once 'header.php';
?>

<section class="tabs">
    <a href="#" class="tab active" id="actualite-tab">Actualité à la Une</a>
    <a href="#" class="tab" id="evenements-tab">Événements</a>
</section>

<section id="actualite" class="section">
    <div class="headline">
        <h1>Actualité à la Une</h1>
    </div>

 <section class="cards-container">
        <section class="cardsprincipal">
            <button class="prev-btn">❮</button>
            <div class="cards main-cards">
                <div class="card" data-description="Vous êtes motivé(e) par les causes sociales et souhaitez contribuer à faire évoluer les mentalités face à l'alcoolisme ? Notre association est en pleine expansion et a besoin de bénévoles pour mener à bien ses missions. En rejoignant notre équipe, vous participerez à des actions concrètes telles que l’organisation d’événements de sensibilisation, la distribution de supports éducatifs, ou encore l’accompagnement des personnes dans leur démarche de sobriété.
Nous recherchons des personnes prêtes à s'engager, même à temps partiel. Chaque geste compte, et chaque bénévole apporte une pierre précieuse à l'édifice. N’hésitez pas à nous contacter via notre formulaire en ligne ou par téléphone pour obtenir plus d'informations.">
                    <img src="/src/img/benevole.jpg" alt="A la recherche de bénévole">
                    <h3>A LA RECHERCHE DE BENEVOLE</h3>
                </div>
                <div class="card" data-description="Notre collaboration avec l'ANPA marque un tournant décisif dans nos actions de prévention. Ensemble, nous allons développer des outils pédagogiques, organiser des ateliers dans les écoles et entreprises, et proposer des formations aux professionnels de santé. L'objectif est de sensibiliser à l'impact de l'alcool sur la santé physique, mentale, et sociale, tout en donnant les moyens concrets aux personnes touchées de s’en sortir.
Ce partenariat s’inscrit dans une démarche d’innovation, avec notamment la création d’un observatoire des addictions permettant d'analyser les données collectées et de mieux orienter nos actions. Nous remercions chaleureusement l’ANPA pour sa confiance et espérons des résultats positifs pour l’avenir.

">
                    <img src="/src/img/partenariats.jpg" alt="De nouveaux partenariats">
                    <h3>DE NOUVEAUX PARTENARIATS</h3>
                </div>
                <div class="card" data-description="Après cinq années de présidence marquées par de nombreuses initiatives et une expansion significative des activités de l’association, Mme Claire Dubois cède sa place à M. Julien Martin. Ce dernier apporte une expertise précieuse grâce à ses nombreuses années d’engagement dans des associations similaires.
Sous son leadership, nous prévoyons de développer de nouveaux projets ambitieux, notamment la création d’un réseau national d’entraide pour les familles touchées par l’alcoolisme et le lancement d’une campagne de sensibilisation à l’échelle européenne.
Nous remercions Mme Dubois pour son dévouement et souhaitons la bienvenue à M. Martin, convaincus qu’il saura porter haut les valeurs et les ambitions de notre association.">
                    <img src="/src/img/president.jpg" alt="Changement de président">
                    <h3>CHANGEMENT DE PRESIDENT</h3>
                </div>
            </div>
            <button class="next-btn">❯</button>
        </section>

        <section class="cardssecondaire">
            <div class="cards secondary-cards">
                <div class="card" data-description="Cette journée spéciale se tiendra au centre des congrès de Lyon et rassemblera des experts, des témoignages de personnes ayant surmonté leur addiction, et des professionnels de santé. Parmi les ateliers proposés :

Comprendre les mécanismes de la dépendance à l’alcool.
Soutenir un proche en difficulté.
Initiatives locales : construire des solutions ensemble.
Un espace dédié aux familles et enfants permettra également de sensibiliser les plus jeunes à l’importance d’une consommation responsable. Vous pourrez également assister à une conférence animée par le Dr Caroline Lefèvre, spécialiste en addictologie, qui répondra à toutes vos questions.
L’événement est gratuit, mais les places sont limitées. Inscrivez-vous dès maintenant via notre site internet pour participer à cette journée riche en échanges et apprentissages.">
                    <img src="/src/img/lyon.jpg" alt="Changement de président">
                    <h3>JOURNEE DE PREVENTION A LYON</h3>
                </div>
                <div class="card" data-description="En réponse aux besoins croissants de nos adhérents, nous sommes fiers de présenter nos nouveaux programmes de soutien. Ces derniers incluent des consultations individuelles avec des psychologues spécialisés, des ateliers de groupe animés par des coachs formés, et des sessions éducatives pour sensibiliser à l’impact de l’alcool.
Les programmes sont adaptés aux différents stades de la dépendance, qu’il s’agisse de personnes en début de parcours ou de celles qui souhaitent maintenir leur sobriété. Chaque participant recevra un plan d’action personnalisé en fonction de ses besoins et objectifs.
Ces initiatives visent à créer un espace bienveillant et motivant, où chacun peut avancer à son rythme. Inscrivez-vous dès aujourd’hui pour bénéficier d’un accompagnement unique et commencer à changer votre vie.">
                    <img src="/src/img/soutien.jpg" alt="A la recherche de bénévole">
                    <h3>NOUVEAUX PROGRAMMES DE SOUTIEN PERSONNALISES</h3>
                </div>
            </div>
        </section>
    </section>
</section>


<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2 id="modal-title">Titre de l'actualité</h2>
        <p id="modal-description">Description détaillée de l'actualité sélectionnée.</p>
    </div>
</div>


<section class="events-section">
    <div class="events-title">
        <h1>Événements</h1>
    </div>
    <div class="events">
        <div class="event">
            <div class="year">2025</div>
            <p>Lancement du projet "Sobriété en entreprise" pour sensibiliser les employés et les employeurs sur l'impact de l'alcool au travail, avec des formations et des ressources adaptées.</p>
        </div>

        <div class="event">
            <div class="year">2024</div>
            <p>Inauguration d'un centre de prévention à Lyon, offrant des consultations gratuites et des ateliers éducatifs sur les effets de l'alcool sur la santé physique et mentale.</p>
        </div>

        <div class="event">
            <div class="year">2024</div>
            <p>Lancement de la campagne nationale "Familles et Résilience" avec un focus sur le soutien des proches des personnes touchées par l'alcoolisme.</p>
        </div>

        <div class="event">
            <div class="year">2023</div>
            <p>Lancement de la campagne nationale "Vivre sobrement", visant à sensibiliser les jeunes adultes sur les risques de l'alcoolisme avec des ateliers interactifs et des témoignages en ligne.</p>
        </div>

        <div class="event">
            <div class="year">2022</div>
            <p>Ouverture de trois nouvelles antennes locales à Lille, Bordeaux et Marseille pour offrir un soutien de proximité aux familles touchées par l'alcoolisme.</p>
        </div>

        <div class="event">
            <div class="year">2021</div>
            <p>Organisation de la conférence internationale "Alcool et Santé Mentale" réunissant des experts en addictologie pour échanger sur les dernières recherches.</p>
        </div>

        <div class="event">
            <div class="year">2020</div>
            <p>Création du programme de soutien en ligne "Sobriété Connectée" pour accompagner les adhérents pendant la pandémie de COVID-19.</p>
        </div>

        <div class="event">
            <div class="year">2019</div>
            <p>Participation au salon national de la prévention à Paris pour promouvoir les initiatives éducatives sur les effets de l'alcool.</p>
        </div>

        <div class="event">
            <div class="year">2018</div>
            <p>Organisation de journées portes ouvertes dans toutes les antennes locales pour encourager les échanges entre professionnels, familles et adhérents.</p>
        </div>
    </div>

</section>
<?php
require_once 'footer.php';
?>

</body>
</html>
