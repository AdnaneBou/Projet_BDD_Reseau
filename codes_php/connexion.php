<?php
$title = "Connexion";
$filename = "connexion.php";
require_once('./include/config.inc.php');



// Vérification de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des identifiants saisis dans le formulaire
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];


    try {
        

        // Requête SQL pour vérifier les identifiants
        $query = "SELECT * FROM personnes WHERE email = :email AND mot_de_passe = :mot_de_passe";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $mot_de_passe);
        $stmt->execute();

        // Vérification des résultats de la requête
        $user = $stmt->fetch(PDO::FETCH_ASSOC); #
        if ($user) {
            // Redirection vers la page d'accueil si les identifiants sont corrects
            // Stockez l'identifiant de l'utilisateur dans la session
            $_SESSION['user_id'] = $user['id_personne'];
            header('Location: index.php');
            exit();
        } else {
            // Affichage du message d'erreur si les identifiants sont incorrects
            $errorMessage = "Identifiant et/ou mot de passe incorrect(s).";
        }
    } catch (PDOException $e){
        echo "Erreur de connexion à la base de données PostgreSQL : " . $e->getMessage();
        return;
    }
}
?>

<!DOCTYPE html>
<html lang='fr'>
<head>

    <meta charset='utf-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <title>
        <?= $title ?>
    </title>
    <meta name="author" content="BOUYKNANE Adnane MEBARKI Khalifa BOUAOUNI Samy-Mohamed" />
    <meta name="description" content="Site Web réalisé dans le cadre du projet BBD-Réseau" />
    <meta name='viewport' content='width=device-width, initial-scale=1' />
    <meta name='keywords' content='BD Réseau, Université, Présence' />
    <link rel="icon" href='./images/favicon.ico' />
    <link rel="stylesheet" href="CSS/style.css"/>
</head>
<body>
    <div class="background">
        <a href='index.php'><img src='./images/logo_rapport_bddreseau2.png' alt='erreur logo site' class='logo' title='Accueil'/></a>
        <div class="login-box">
            <p>Pas de compte ? <a href="inscription.php" style="color: blue;">Inscrivez-vous</a></p>
            <?php if(isset($errorMessage)) { ?>
                <p style="color: red;"><?= $errorMessage ?></p>
            <?php } ?>
            <h2>Connexion</h2>
            <form method="POST">
                <div class="input-group">
                    <label for="email">Identifiant (mail)</label>
                    <input type="text" id="email" name="email" maxlength="64" required>
                </div>
                <div class="input-group">
                    <label for="mot_de_passe">Mot de passe</label>
                    <div class="password-input">
                        <input type="password" id="mot_de_passe" name="mot_de_passe" maxlength="70" required>
                    </div>
                </div>
                <input type="submit" value="Se connecter">
            </form>
        </div>
    </div>
</body>
</html>