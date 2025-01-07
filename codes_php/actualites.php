<?php
$title = "Actualités";
$filename = "actualites.php";
include_once("./include/header.inc.php");


?>
    <h1>Caractéristiques de l'université</h1>
    <section class="plan">
        <h2 id="hide">Effectifs de l'université</h2>
        <?php
        $query = "SELECT COUNT(*) AS nombre_etudiant FROM etudiants";
        $stmt = $db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombre_etudiant = $result['nombre_etudiant'];

        $query = "SELECT COUNT(*) AS nombre_enseignant FROM enseignants";
        $stmt = $db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombre_enseignant = $result['nombre_enseignant'];

        $query = "SELECT COUNT(*) AS nombre_directeur FROM directeurs";
        $stmt = $db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombre_directeur = $result['nombre_directeur'];

        echo "<p>L'université est constituée de $nombre_etudiant étudiants enseignés par $nombre_enseignant enseignants, sous plusieurs départements (informatique, mathématiques, biologie, ...) dirigés par $nombre_directeur directeurs.</p>";
        ?>
    </section>
    <section class="plan">
        <h2 id="hide">Matériels disponibles (Triés par quantités)</h2>
        <ul>
            <?php
            $query = "SELECT nom_materiel, quantite FROM materiels ORDER BY quantite ASC";
            $stmt = $db->query($query);
            $materiels = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($materiels as $materiel) {
                echo "<li>".$materiel['nom_materiel']." (Quantité : ".$materiel['quantite'].")</li>";
            }
            ?>

            
        </ul>
    </section>
    <section class="plan">
        <h2 id="hide">Effectifs des salles</h2>
        <p>L'effectifs max de chaques salles (dans l'ordre croissant) :</p>
        <ul>
            <?php
            $query = "SELECT id_salle, effectif_salle FROM salles ORDER BY effectif_salle ASC;";
            $stmt = $db->query($query);
            $effectif_salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($effectif_salles as $effectif_salle) {
                echo "<li> Salle n°".$effectif_salle['id_salle']." (Effectif : ".$effectif_salle['effectif_salle']." personnes)</li>";
            }
            ?>
        </ul>
        <?php
        $query = "SELECT ROUND(AVG(effectif_salle), 2) AS moyenne_salle FROM salles";
        $stmt = $db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $moyenne_salle = $result['moyenne_salle'];

        echo "<p>L'effectif moyen de toutes les salles de l'université est de $moyenne_salle personnes.</p>";
        ?>
        <p>La liste des salles ayant un effectif supérieur à la moyenne, triées par ordre croissant d'effectif :</p>
        <ul>
            <?php
            $query = "SELECT id_salle, effectif_salle FROM salles WHERE effectif_salle > ( SELECT ROUND(AVG(effectif_salle), 2) FROM salles ) ORDER BY effectif_salle ASC;";
            $stmt = $db->query($query);
            $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($salles as $salle) {
                echo "<li> Salle n°".$salle['id_salle']." (Effectif : ".$salle['effectif_salle']." personnes)</li>";
            }
            ?>
        </ul>
    </section>
<?php
include_once("./include/footer.inc.php");
?>