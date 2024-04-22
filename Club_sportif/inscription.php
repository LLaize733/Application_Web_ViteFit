<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ViteFit - Inscription au cours</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,900italic,900,700italic,700,500italic,500,400italic,300italic,300,100italic,100" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="inscription.css">
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



<!--      
          wamp permet d'envoyer des requêtes depuis notre navigateur  = la machine agit comme le client et le serveur
          Le script php est interprété ligne par ligne, il permet de générer du contenu dynamique en fonction de l'action
          Le html est le résultat de l'exécution des scripts php renvoyé au client
-->

<?php
require('bdd_planning.php');


#bibliohtèque php pour faciliter envoi de mail à partir des scripts php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\wamp64\www\MASTER\PROJET_M2\PHPMailer-master\src\Exception.php';
require 'C:\wamp64\www\MASTER\PROJET_M2\PHPMailer-master\src\PHPMailer.php';
require 'C:\wamp64\www\MASTER\PROJET_M2\PHPMailer-master\src\SMTP.php';


// Traitement du formulaire d'inscription
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $cours_id = $_POST["cours"];

    // Vérification des champs vides
    if (empty($nom) || empty($prenom) || empty($email) || empty($cours_id)) {
        echo "<script>alert('Veuillez remplir tous les champs.');</script>";
    } else {
        // Vérifier s'il reste des places dans le cours sélectionné
        # requete sélectionnant places_disponibles selon l'id du cours


        $places_restantes_query = "SELECT places_disponibles FROM cours WHERE id = $cours_id";
        $result = $conn->query($places_restantes_query);  #requête exécutée en se connectant à la base de données puis stockée 

        if ($result->num_rows > 0) {  #verifie si le cours avec l'id existe dans la BDD
            $row = $result->fetch_assoc();   #extrait la 1ere ligne du résultat en tableau associatif , puis stock variable
            $places_restantes = $row["places_disponibles"];    #récupère la valeur de la colonne "places_disponibles" de la ligne récupérée et la stocke dans la variable $places_restante

            #la variable place-restante est plus facilement manipulable
            if ($places_restantes > 0) {
                // Vérification si l'utilisateur est déjà inscrit
                $verification_query = "SELECT COUNT(*) AS total FROM utilisateurs WHERE email = '$email' AND cours_id = $cours_id";
                $result_verification = $conn->query($verification_query);
                $row_verification = $result_verification->fetch_assoc();
                $total = $row_verification['total'];

                if ($total > 0) {
                    echo "<script>alert('Vous êtes déjà inscrit à ce cours.');</script>";
                } else {
                    // Effectuer l'inscription
                    $inscription_query = "INSERT INTO utilisateurs (nom, prenom, email, cours_id) VALUES ('$nom', '$prenom', '$email', $cours_id)";
                    $conn->query($inscription_query); #execution de la requete d'inscription, ajout utilisateur à la BDD

                    // Mettre à jour le nombre de places disponibles
                    $places_restantes--;
                    $update_places_query = "UPDATE cours SET places_disponibles = $places_restantes WHERE id = $cours_id";
                    $conn->query($update_places_query); #execution requete pour mettre a jour le nombre de places disponibles

                    // Envoyer un mail de confirmation
                    envoyerMailConfirmation($nom, $prenom, $email, $cours_id);

                    echo "<script>alert('Inscription réussie. Un email de confirmation a été envoyé.');</script>";
                }

            } else {
                echo "Désolé, ce cours est complet.";
            }
        } else {
            echo "Erreur lors de la récupération des places disponibles.";
        }
    }
}

// Récupérer la liste des cours disponibles
$cours_disponibles_query = "SELECT id, sport, date_cours, places_disponibles FROM cours WHERE places_disponibles > 0";
$cours_result = $conn->query($cours_disponibles_query);
?>

     
<h1 id="titre_inscription">Inscription au cours</h1>
            <form method="post" class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="nom">Nom :</label>
                <input class="form-control" type="text" name="nom" required><br>

                <label for="prenom">Prénom :</label>
                <input class="form-control"type="text" name="prenom" required><br>

                <div class="input-group">

                <label for="email">Adresse Email :</label>
                <input class="form-control" id="inputEmail" type="email" name="email" required autofocus><br>

                <label for="cours">Choisissez un cours :</label>
                <select class="form-control" name="cours" required>
                    <?php
                    while ($row = $cours_result->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'>" . $row["sport"] . " - " . $row["date_cours"] . " (Places disponibles: " . $row["places_disponibles"] . ")</option>";
                    }
                    ?>
                </select><br>
                <input class="btn btn-lg btn-primary btn-block"  type="submit" value="Valider l'inscription">
        </form>

<?php
// Fonction d'envoi de mail de confirmation
function envoyerMailConfirmation($nom, $prenom, $email, $cours_id)
{
    // Construire le message de confirmation
    $message = "Bonjour $prenom $nom,\n\n";
    $message .= "Vous êtes inscrit au cours n° $cours_id.\n";
    $message .= "Merci pour votre inscription !\n\n";
    $message .= "Cordialement,\nViteFit";

    // Sujet du mail
    $sujet = "ViteFit : Confirmation d'inscription au cours $cours_id";

    // Créer une instance de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuration du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'CLUBVITEFIT@gmail.com'; 
        $mail->Password = 'fbby kgou dwyu vofe';						
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Destinataire
        $mail->setFrom('CLUBVITEFIT@gmail.com', 'ViteFit');
        $mail->addAddress($email, "$prenom $nom");

        // Contenu du mail
        $mail->isHTML(false);
        $mail->Subject = $sujet;
        $mail->Body = $message;

        // Envoyer le mail
        $mail->send();

    } 
    catch (Exception $e) {
        echo "Erreur lors de l'envoi du mail de confirmation: {$mail->ErrorInfo}";
    }
}
?>


<footer>
        <div class="bas">
            <p><a href="">Informations</a> - <a href="">Mentions Légales</a> - <a href="mailto:CLUBVITEFIT@gmail.com">Contact</a></p>
            <p>© 2023 Conception et réalisation par Afreen&amp;Loucia. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
