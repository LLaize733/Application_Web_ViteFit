<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ViteFit - Calendrier des cours</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,900italic,900,700italic,700,500italic,500,400italic,300italic,300,100italic,100" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="calendrier.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <div class="haut">
        <header class="gauche">
            <a href="homepage.php"><img src="images/ViteFit.png" alt="ViteFit" width="400px" height="200px"></a>
         </header>
        <nav class="droite">
            <ul>
                <li><a href="homepage.php">Accueil</a></li>
                <li><a href="calendar.php">Calendrier</a></li>
                <li><a href="inscription.php">Inscription</a></li>
                <li><a href="connexion.php">Connexion</a></li>
                <li><a href="mailto:CLUBVITEFIT@gmail.com">Contact</a></li>
            </ul>
        </nav>
    </div>

<!-- Partie Calendrier des cours -->
        <h1>Calendrier des cours - Janvier à Avril 2024</h1>
		
<?php
// Connexion à la base de données
require('bdd_planning.php');

// Gérer le changement de mois
if (isset($_GET['mois'])) {
    $moisActuel = $_GET['mois'];
} else {
    $moisActuel = date('Y-m');
}

// Sélectionner les données des cours pour le mois actuel
$sql = "SELECT cours.id, sport, date_cours, places_disponibles
        FROM cours
        WHERE DATE_FORMAT(date_cours, '%Y-%m') = '$moisActuel'
        ORDER BY date_cours, sport";

$result = $conn->query($sql);

function traduireMois($mois) {
    $moisTraduits = array(
        'January' => 'Janvier',
        'February' => 'Février',
        'March' => 'Mars',
        'April' => 'Avril',
        'May' => 'Mai',
        'June' => 'Juin',
        'July' => 'Juillet',
        'August' => 'Août',
        'September' => 'Septembre',
        'October' => 'Octobre',
        'November' => 'Novembre',
        'December' => 'Décembre'
    );

    $moisEnAnglais = ucfirst(strftime('%B', strtotime($mois . '-01')));
    
    return isset($moisTraduits[$moisEnAnglais]) ? $moisTraduits[$moisEnAnglais] : $moisEnAnglais;
}


if ($result->num_rows > 0) {
    // Afficher le tableau
    echo "<h2 align='center'>" . ucfirst(traduireMois($moisActuel)) . "</h2>";

    // Ajouter des flèches de navigation
    $moisPrecedent = date('Y-m', strtotime('-1 month', strtotime($moisActuel)));
    $moisSuivant = date('Y-m', strtotime('+1 month', strtotime($moisActuel)));

    echo "<div id='navigation' class='center'>";
    echo "<a href='?mois=$moisPrecedent'>&lt; Mois précédent </a>&ensp;|&ensp;";
    echo "<a href='?mois=$moisSuivant'> Mois suivant &gt;</a><br><br>";
    echo " </div>";

    echo "<table border='1'>
            <tr>
                <th>Jour</th>
                <th>Date</th>
                <th>Nom du Cours</th>
                <th>Places Disponibles</th>
            </tr>";

    $joursTraduction = array(
        'Monday' => 'Lundi',
        'Tuesday' => 'Mardi',
        'Wednesday' => 'Mercredi',
        'Thursday' => 'Jeudi',
        'Friday' => 'Vendredi',
        'Saturday' => 'Samedi',
        'Sunday' => 'Dimanche'
    );

    while ($row = $result->fetch_assoc()) {
        $jourTraduit = $joursTraduction[date('l', strtotime($row['date_cours']))];
        $numeroJour = date('d', strtotime($row['date_cours']));

        echo "<tr>
                <td>$jourTraduit</td>
                <td>$numeroJour</td>
                <td>" . $row['sport'] . "</td>
                <td>" . $row['places_disponibles'] . "</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "Aucun cours trouvé.";
}

$conn->close();
?>

<footer>
        <div class="bas">
            <p><a href="">Informations</a> - <a href="">Mentions Légales</a> - <a href="mailto:CLUBVITEFIT@gmail.com">Contact</a></p>
            <p>© 2023 Conception et réalisation par Afreen&amp;Loucia. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>