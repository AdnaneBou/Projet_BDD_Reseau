<?php
$title = "Plan du site";
$filename = "plan.php";
include_once("./include/header.inc.php");
?>

    <h1>Plan du site</h1>
    <section class="plan">
        <h2 id="hide">Dernière modification : 24/11/2024</h2>
        <ul class=planDuSite>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="actualites.php">Actualités</a></li>
            <li><a href="contacts.php">Contacts</a></li>
            <li><a href="inscription.php">S'inscrire</a></li>
        </ul>
    </section>
<?php
include_once("./include/footer.inc.php");
?>