<?php
$title = "Accueil";
$filename = "index.php";
include_once("./include/header.inc.php");

?>

        <h1 class='alignement 1'>Accueil</h1>
        <section>
            <h2>Préambule</h2>
            <div class="center_index">
                <p>Bienvenue sur le site Web de l'université, ce site permet de consulté son compte affilié ainsi que les actualités de la fac.</p>
                <img src='./images/salle2.jpeg' alt='salle université' height="360" width="640" title="salle université"/>
                <p>Dorénavant, à partir de l'année 2024/2025, un nouveau système de présence a été mise en place dans chaque salle de l'université pour optimiser le temps de cours :</p>
                <p>Des "lecteurs de carte étudiante" ont été installés en entrée de chaque salle, les étudiants doivent donc pointer leurs présences en passant
                    leurs cartes sur les lecteurs pour être émargés sur les feuilles d'émargement. Tout étudiant n'ayant pas pointé sa présence en entrée de cours sera considéré comme absent.
                </p>

            </div>
        </section>
<?php

include_once("./include/footer.inc.php");
?>