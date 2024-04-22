<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ViteFit - Connexion Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,900italic,900,700italic,700,500italic,500,400italic,300italic,300,100italic,100" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="connexion.css">
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
            </ul>
        </nav>
    </div>

<?php
require('bdd_planning.php');                     #on a prédéfinie des valeurs pour les identifiants

if (isset($_POST['submit'])) {                           # Vérifie si le formulaire a été soumis.
// Form a été soumis                                  #si oui
    $enteredUsername = $_POST['username'];               #Récupère la valeur soumise pour le champ "Nom d'utilisateur".               
    $enteredPassword = $_POST['password'];

    // Vérifier si les identifiants correspondent aux valeurs prédéfinies
    if ($enteredUsername != "admin" || $enteredPassword != "mdp") {            # vérification de la correspondance des identifiants
        $message = "Le nom d'utilisateur ou le mot de passe est incorrect.";
    } else {
        // L'administrateur est authentifié avec succès
        // Vous pouvez effectuer d'autres actions ici
        echo "L'administrateur est authentifié avec succès.";

// Initialiser la session
session_start();
$_SESSION['administrateur'] = $enteredUsername;

        header('Location: adminpage.php');
        // N'oubliez pas d'ajouter exit() après header pour éviter l'exécution supplémentaire du script
        exit();
    }
}

$conn->close();
?>

<section id="connexion">
   <form class="box" action="" method="post" name="login">
        <h1 class="titre_connexion">Connexion Admin</h1>
        <input type="text" class="box-input" name="username" placeholder="Nom d'utilisateur">
        <input type="password" class="box-input" name="password" placeholder="Mot de passe">
        <input type="submit" value="Connexion" name="submit" class="box-button">


        <?php if (!empty($message)) { ?>
            <p class="errorMessage"><?php echo $message; ?></p>
        <?php } ?>
    </form>  
      
    <footer>
        <div class="bas">
            <p><a href="">Informations</a> - <a href="">Mentions Légales</a> - <a href="mailto:CLUBVITEFIT@gmail.com">Contact</a></p>
            <p>© 2023 Conception et réalisation par Afreen&amp;Loucia. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>

