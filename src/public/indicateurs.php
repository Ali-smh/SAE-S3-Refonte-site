<?php
if (!session_id()) {
    session_start();
}

$host = 'localhost';
$dbname = 'enquete_alcool_ecoute';
$username = 'root';
$password = 'soumah123';
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Échec de la connexion à la base de données : " . $e->getMessage());
}

/**
 * Récupère les données pour la répartition des habitants par région.
 *
 * @param PDO $pdo
 * @return array
 */
function getRegionData(PDO $pdo)
{
    $query = "
        SELECT valeur_reponse AS region, COUNT(*) AS nombre_habitants
        FROM REPONSES
        WHERE question_id = (SELECT question_id FROM QUESTIONS WHERE cle_question = 'region' LIMIT 1)
        GROUP BY valeur_reponse
        ORDER BY nombre_habitants DESC;
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calcul du pourcentage
    $total = array_sum(array_column($data, 'nombre_habitants'));
    foreach ($data as &$row) {
        $row['percentage'] = round(($row['nombre_habitants'] / $total) * 100, 2);
    }

    return $data;
}

/**
 * Récupère les données pour la répartition des qualités de vie.
 *
 * @param PDO $pdo
 * @return array
 */
function getQualityOfLifeData(PDO $pdo)
{
    $query = "
        SELECT o.valeur_option, COUNT(r.reponse_id) as count
        FROM REPONSES r
        JOIN OPTIONS o ON r.valeur_reponse = o.valeur_option
        JOIN QUESTIONS q ON r.question_id = q.question_id
        WHERE q.cle_question = 'qualite_vie'
        GROUP BY o.valeur_option;
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculer le total des réponses
    $totalResponses = array_sum(array_column($responses, 'count'));

    // Ajouter les pourcentages à chaque réponse
    foreach ($responses as &$response) {
        $response['percentage'] = round(($response['count'] / $totalResponses) * 100, 2);
    }

    return $responses;
}

/**
 * Récupère les données pour la répartition des réponses sur l'impact sur la vie pro.
 *
 * @param PDO $pdo
 * @return array
 */
function getImpactOnProLifeData(PDO $pdo)
{
    $query = "
        SELECT valeur_reponse AS reponse, COUNT(*) AS nombre_reponses
        FROM REPONSES
        WHERE question_id = 2
        GROUP BY valeur_reponse
        ORDER BY nombre_reponses DESC;
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupération des données
$impactOnProLifeData = getImpactOnProLifeData($pdo);
$regionData = getRegionData($pdo);
$qualityOfLifeData = getQualityOfLifeData($pdo);

$pdo = null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indicateurs</title>

    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">

    <link rel="icon" href="/img/logos/logo_alcool_ecoute.png">
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <style>

        body{
            font-family: "Lato", sans-serif;
            background-color: #f9f9f9;
        }

        .bottom{
            display: flex;
            align-items: center;
        }

        #chart {
            width: 700px;
            height: 500px;
        }

        #pieChart{
            margin-left: 2em;
        }

        .right, .left{
            align-items: center;
        }

        .top h4, .left h4{
            margin-left: 3em;
        }

        .bottom{
            border-radius: 10px;
            background-color: white;
        }
    </style>
</head>
<body>
<?php
require_once 'header.php';
?>

<div class="container">
    <div class="top">
        <h4>Nombre d'habitants par région</h4>
        <div id="chart"></div>
    </div>


    <div class="bottom">
        <div class="left">
            <h4>Répartition des réponses - Qualité de vie</h4>
            <div id="qualityOfLifeChart"></div>
        </div>

        <div class="right">
            <h4>Répartition des réponses - Impact sur la vie pro</h4>
            <div id="pieChart"></div>
        </div>

    </div>
</div>


<?php
require_once 'footer.php';
?>

<script>
    const data = <?php echo json_encode($regionData); ?>;
    const width = 1250, height = 500, margin = { top: 20, right: 30, bottom: 50, left: 50 };
    const chartWidth = width - margin.left - margin.right;
    const chartHeight = height - margin.top - margin.bottom;

    const svg = d3.select("#chart")
        .append("svg")
        .attr("width", width)
        .attr("height", height);

    const chart = svg.append("g")
        .attr("transform", `translate(${margin.left},${margin.top})`);

    const x = d3.scaleBand()
        .domain(data.map(d => d.region))
        .range([0, chartWidth])
        .padding(0.3);

    const y = d3.scaleLinear()
        .domain([0, d3.max(data, d => d.nombre_habitants)])
        .range([chartHeight, 0]);

    chart.append("g")
        .style("font-size", "5px")
        .attr("transform", `translate(0,${chartHeight})`)
        .call(d3.axisBottom(x));

    chart.append("g")
        .call(d3.axisLeft(y));

    chart.selectAll(".bar")
        .data(data)
        .enter()
        .append("rect")
        .attr("class", "bar")
        .attr("x", d => x(d.region))
        .attr("y", d => y(d.nombre_habitants))
        .attr("width", x.bandwidth())
        .attr("height", d => chartHeight - y(d.nombre_habitants))
        .attr("fill", "#4CAF50");

    chart.selectAll(".bar-label")
        .data(data)
        .enter()
        .append("text")
        .attr("class", "bar-label")
        .attr("x", d => x(d.region) + x.bandwidth() / 2)
        .attr("y", d => y(d.nombre_habitants) - 5)
        .text(d => `${d.percentage}%`);

    const qualityOfLifeData = <?php echo json_encode($qualityOfLifeData); ?>;

    const qualityChartWidth = 700, qualityChartHeight = 500, qualityMargin = { top: 20, right: 30, bottom: 100, left: 50 };
    const qualityInnerWidth = qualityChartWidth - qualityMargin.left - qualityMargin.right;
    const qualityInnerHeight = qualityChartHeight - qualityMargin.top - qualityMargin.bottom;

    const qualitySvg = d3.select("#qualityOfLifeChart")
        .append("svg")
        .attr("width", qualityChartWidth)
        .attr("height", qualityChartHeight);

    const qualityChart = qualitySvg.append("g")
        .attr("transform", `translate(${qualityMargin.left},${qualityMargin.top})`);

    const qualityX = d3.scaleBand()
        .domain(qualityOfLifeData.map(d => d.valeur_option))
        .range([0, qualityInnerWidth])
        .padding(0.3);

    const qualityY = d3.scaleLinear()
        .domain([0, d3.max(qualityOfLifeData, d => d.count)])
        .range([qualityInnerHeight, 0]);

    qualityChart.append("g")
        .attr("transform", `translate(0,${qualityInnerHeight})`)
        .call(d3.axisBottom(qualityX))
        .selectAll("text")
        .attr("transform", "rotate(-45)")
        .style("text-anchor", "end");

    qualityChart.append("g")
        .call(d3.axisLeft(qualityY));

    qualityChart.selectAll(".bar")
        .data(qualityOfLifeData)
        .enter()
        .append("rect")
        .attr("class", "bar")
        .attr("x", d => qualityX(d.valeur_option))
        .attr("y", d => qualityY(d.count))
        .attr("width", qualityX.bandwidth())
        .attr("height", d => qualityInnerHeight - qualityY(d.count))
        .attr("fill", "#2196F3");

    qualityChart.selectAll(".bar-label")
        .data(qualityOfLifeData)
        .enter()
        .append("text")
        .attr("class", "bar-label")
        .attr("x", d => qualityX(d.valeur_option) + qualityX.bandwidth() / 2)
        .attr("y", d => qualityY(d.count) - 5)
        .style("text-anchor", "middle")
        .style("font-size", "10px")
        .text(d => `${d.percentage}%`);

    // Charger les données du PHP
    const pieData = <?php echo json_encode($impactOnProLifeData); ?>;

    // Calculer le total des réponses
    const totalReponses = pieData.reduce((acc, d) => acc + d.nombre_reponses, 0);

    // Ajouter un champ pourcentage à chaque donnée
    pieData.forEach(d => {
        d.pourcentage = (d.nombre_reponses / totalReponses * 100).toFixed(2); // Calcul du pourcentage
    });

    // Dimensions du camembert
    const pieWidth = 300, pieHeight = 300, pieRadius = Math.min(pieWidth, pieHeight) / 2;

    const pieSvg = d3.select("#pieChart")
        .append("svg")
        .attr("width", pieWidth)
        .attr("height", pieHeight)
        .append("g")
        .attr("transform", `translate(${pieWidth / 2},${pieHeight / 2})`);

    // Créer une échelle de couleurs
    const color = d3.scaleOrdinal(d3.schemeCategory10);

    // Préparer les données
    const pie = d3.pie()
        .value(d => d.nombre_reponses);

    const arc = d3.arc()
        .innerRadius(0) // Camembert plein
        .outerRadius(pieRadius);

    // Créer les segments
    pieSvg.selectAll("path")
        .data(pie(pieData))
        .enter()
        .append("path")
        .attr("d", arc)
        .attr("fill", d => color(d.data.reponse))
        .attr("stroke", "white")
        .attr("stroke-width", "2px");

    // Ajouter des labels avec pourcentage
    pieSvg.selectAll("text")
        .data(pie(pieData))
        .enter()
        .append("text")
        .text(d => `${d.data.reponse}: ${d.data.pourcentage}%`) // Affichage du pourcentage
        .attr("transform", d => `translate(${arc.centroid(d)})`)
        .style("text-anchor", "middle")
        .style("font-size", "12px");

</script>
</body>
</html>
