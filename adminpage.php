<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ViteFit Dashboard - Profile page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,900italic,900,700italic,700,500italic,500,400italic,300italic,300,100italic,100" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="adminpage.css">
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
                <li><a href="calendrier.php">Calendrier</a></li>
                <li><a href="inscription.php">Inscription</a></li>
                <li><a href="connexion.php">Connexion</a></li>
                <li><a href="mailto:CLUBVITEFIT@gmail.com">Contact</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>

<?php
require('bdd_planning.php');

// Afficher la liste des inscrits dans un cours
if (isset($_GET['cours_id'])) {
    $cours_id = $_GET['cours_id'];

    $sql = "SELECT utilisateurs.id, utilisateurs.nom, utilisateurs.prenom, utilisateurs.email
            FROM utilisateurs
            WHERE utilisateurs.cours_id = $cours_id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Liste des inscrits dans le cours :</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row["nom"] . " " . $row["prenom"] . " - " . $row["email"] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "Aucun inscrit dans ce cours.";
    }
}

if (isset($_POST['ajouter_inscription'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $cours_id = $_POST['cours_id'];

    $sql = "INSERT INTO utilisateurs (nom, prenom, email, cours_id) VALUES ('$nom', '$prenom', '$email', $cours_id)";

    if ($conn->query($sql) === TRUE) {
        echo "Nouvelle inscription ajoutée avec succès.";
    } else {
        echo "Erreur lors de l'ajout de l'inscription : " . $conn->error;
    }
}

if (isset($_POST['modifier_inscription'])) {
    $utilisateur_id = $_POST['utilisateur_id'];
    $nouveau_cours_id = $_POST['nouveau_cours_id'];

    $sql = "UPDATE utilisateurs
            SET cours_id=$nouveau_cours_id
            WHERE id=$utilisateur_id";

    if ($conn->query($sql) === TRUE) {
        echo "Inscription modifiée avec succès.";
    } else {
        echo "Erreur lors de la modification de l'inscription : " . $conn->error;
    }
}


if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['utilisateur_id'])) {
    $utilisateur_id = $_GET['utilisateur_id'];

    $sql = "DELETE FROM utilisateurs WHERE id=$utilisateur_id";

    if ($conn->query($sql) === TRUE) {
        echo "Inscription supprimée avec succès.";
    } else {
        echo "Erreur lors de la suppression de l'inscription : " . $conn->error;
    }
}
?>

<!-- Afficher la liste des cours -->
<h2>Liste des cours :</h2>
<ul>
    <?php
    $sql = "SELECT id, sport, date_cours FROM cours";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<li><a href='?cours_id=".$row["id"]."'>" . $row["sport"] . " - " . $row["date_cours"] . "</a></li>";
        }
    } else {
        echo "Aucun cours disponible.";
    }
    ?>
</ul>

<!-- Formulaire pour ajouter une nouvelle inscription -->
<h2>Ajouter une nouvelle inscription :</h2>
<form method="post" action="">
    Nom: <input type="text" name="nom" required><br>
    Prénom: <input type="text" name="prenom" required><br>
    Email: <input type="email" name="email" required><br>
    Cours:
    <select name="cours_id">
        <?php
        $sql = "SELECT id, sport, date_cours FROM cours";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<option value='".$row["id"]."'>" . $row["sport"] . " - " . $row["date_cours"] . "</option>";
            }
        } else {
            echo "<option value=''>Aucun cours disponible</option>";
        }
        ?>
    </select><br>
    <input class="btn" type="submit" name="ajouter_inscription" value="Ajouter inscription">
</form>

<!-- Formulaire pour modifier une inscription -->
<h2>Modifier une inscription :</h2>
<form method="post" action="">
    Sélectionner l'inscription à modifier:
    <select name="utilisateur_id">
        <?php
        $sql = "SELECT utilisateurs.id, utilisateurs.nom, utilisateurs.prenom, utilisateurs.email, utilisateurs.cours_id, cours.sport, cours.date_cours 
                FROM utilisateurs 
                JOIN cours ON utilisateurs.cours_id = cours.id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<option value='".$row["id"]."'>" . $row["nom"] . " " . $row["prenom"] . " - " . $row["email"] . " (Cours actuel: " . $row["sport"] . " - " . $row["date_cours"] . ")</option>";
            }
        } else {
            echo "<option value=''>Aucune inscription disponible</option>";
        }
        ?>
    </select><br>

    Choisir un nouveau cours:
    <select name="nouveau_cours_id">
        <?php
        $sql_cours = "SELECT id, sport, date_cours FROM cours";
        $result_cours = $conn->query($sql_cours);

        if ($result_cours->num_rows > 0) {
            while($row_cours = $result_cours->fetch_assoc()) {
                echo "<option value='".$row_cours["id"]."'>" . $row_cours["sport"] . " - " . $row_cours["date_cours"] . "</option>";
            }
        } else {
            echo "<option value=''>Aucun cours disponible</option>";
        }
        ?>
    </select><br>

    <input class="btn" type="submit" name="modifier_inscription" value="Modifier inscription">
</form>


<!-- Lien pour supprimer une inscription -->
<h2>Supprimer une inscription :</h2>
Sélectionner l'inscription à supprimer :
<ul>
    <?php
    $sql = "SELECT utilisateurs.id as utilisateur_id, utilisateurs.nom, utilisateurs.prenom, utilisateurs.email, cours.sport, cours.date_cours 
            FROM utilisateurs 
            JOIN cours ON utilisateurs.cours_id = cours.id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<li><a href='?action=supprimer&utilisateur_id=".$row["utilisateur_id"]."'>" . $row["nom"] . " " . $row["prenom"] . " - " . $row["email"] . " (Cours: " . $row["sport"] . " - " . $row["date_cours"] . ")</a></li>";
        }
    } else {
        echo "Aucune inscription disponible.";
    }
    ?>
</ul>


<?php
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
