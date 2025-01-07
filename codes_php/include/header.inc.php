<?php
require_once('./include/config.inc.php');
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
    <header>
        <aside>
            <nav class="daylight">
                <ul>
                    <li>
                    <?php
                    
                    if (isset($_SESSION['user_id'])) {
                    // Utilisateur connecté : afficher "Se Déconnecter" et lien vers le script de déconnexion
                        echo '<a href="deconnexion.php">Se Déconnecter</a>';
                    } else {
                    // Utilisateur non connecté : afficher "Se Connecter" et lien vers la page de connexion
                        echo '<a href="connexion.php">Se Connecter</a>';
                    }
                    ?>
                    </li>
                </ul>
            </nav>
        </aside>
        <nav class="navbar">
            <ul>
                <li><?php echo "<a href='index.php'><img src='./images/logo_rapport_bddreseau2.png' alt='erreur logo site' height='75' width='120' title='Accueil'/></a>"?></li>
                <li><?php echo "<a href='actualites.php'>Actualités</a>"?></li>
                <li><?php echo "<a href='contacts.php'>Contacts</a>"?></li>
                <?php
                if (isset($_SESSION['user_id'])) {
                    echo "<li><a href='profil.php'>Mon Profil</a></li>";
                }
                ?>
            </ul>
        </nav>
    </header>
    <main>