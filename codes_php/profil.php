<?php
$title = "Mon Profil";
$filename = "profil.php";
include_once("./include/header.inc.php");


// Vérifiez si l'utilisateur est connecté (vérification factice pour l'exemple)
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php'); // Redirigez vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Récupérez les informations de l'utilisateur depuis la base de données
$personne = $_SESSION['user_id'];

// Requête pour récupérer les informations de l'utilisateur depuis la base de données
$query = "SELECT * FROM personnes WHERE id_personne = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $personne);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mot_de_passe'])) {
    // Récupérer la nouvelle valeur de mot de passe
    $mot_de_passe = $_POST['mot_de_passe'];

    // Mettre à jour le mot de passe dans la base de données
    $query_update = "UPDATE personnes SET mot_de_passe = :mot_de_passe WHERE id_personne = :user_id";
    $stmt_update = $db->prepare($query_update);
    $stmt_update->bindParam(':mot_de_passe', $mot_de_passe);
    $stmt_update->bindParam(':user_id', $personne);
    $stmt_update->execute();

    // Redirection vers la même page après la mise à jour
    header("Location: profil.php");
    exit();
    
}
?>

    <h1>Mon Profil</h1>
    <section>
        <h2><?= $user['prenom'] ?></h2>
        <p>Nom : <?= $user['nom'] ?></p>
        <p>Prénom : <?= $user['prenom'] ?></p>
        <p>Date de Naissance : <?= $user['date_naissance'] ?></p>
        <p>Adresse : <?= $user['adresse'] ?></p>
        <p>Email : <?= $user['email'] ?></p>
        <p>Téléphone : <?= $user['num_telephone'] ?></p>
        <p>Mot de passe : <?= $user['mot_de_passe'] ?></p>
        <?php if(isset($errorMessage)) { ?>
                <p style="color: red;"><?= $errorMessage ?></p>
        <?php } ?>
        <form method="POST">
        <label for="mot_de_passe">Nouveau mot de passe :</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
        <input type="submit" value="Appliquer">
        </form>
    </section>
    
<?php
include_once("./include/footer.inc.php");
?>
