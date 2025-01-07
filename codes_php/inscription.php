<?php
$title = "Inscription";
$filename = "inscription.php";

require_once('./include/config.inc.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id_personne = $_POST['id_personne'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['num_telephone'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $date_naissance = $_POST['date_naissance'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $genre = $_POST['genre'];


    // Requête d'insertion dans la base de données
    $query = "INSERT INTO personnes (id_personne, nom, prenom, num_telephone, email, adresse, date_naissance, mot_de_passe, genre) 
              VALUES (:id_personne, :nom, :prenom, :num_telephone, :email, :adresse, :date_naissance, :mot_de_passe, :genre)";
    
    try {
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_personne', $id_personne);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':num_telephone', $telephone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':date_naissance', $date_naissance);
        $stmt->bindParam(':mot_de_passe', $mot_de_passe);
        $stmt->bindParam(':genre', $genre);
        $stmt->execute();
        // Redirection vers une page  de connexion après l'inscription
        header('Location: connexion.php');
        exit();
    } catch (PDOException $e) {
        $errorMessage = "L'inscription est incorrecte (respecter la typo de mail, téléphone, etc...)";
    }
}

include_once("./include/header.inc.php");
?>

<h1>Inscription</h1>
<section>
    <?php if(isset($errorMessage)) { ?>
        <p style="color: red;"><?= $errorMessage ?></p>
    <?php } ?>
    <form method="POST">
        <label for="id_personne">Identifiant (9 caractères obligatoires) :</label>
        <input type="text" id="id_personne" name="id_personne" required pattern=".{9}" maxlength="9" title="L'identifiant doit contenir exactement 9 caractères."><br>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required><br>

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required><br>

        <label for="genre">Genre :</label>
        <select id="genre" name="genre" required>
            <option value="homme">Homme</option>
            <option value="femme">Femme</option>
        </select><br>

        <label for="date_naissance">Date de naissance :</label>
        <input type="date" id="date_naissance" name="date_naissance" required><br>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required><br>

        <label for="adresse">Adresse :</label>
        <input type="text" id="adresse" name="adresse" required><br>

        <label for="telephone">Téléphone :</label>
        <input type="tel" id="num_telephone" name="num_telephone" required><br>

        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>

        <input type="submit" value="S'inscrire">
    </form>
</section>

<?php
include_once("./include/footer.inc.php");
?>
