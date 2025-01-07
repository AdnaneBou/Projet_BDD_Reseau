<?php
session_start();

// Détruire la session (déconnexion de l'utilisateur)
session_destroy();

// Rediriger vers la page d'accueil (index.php)
header('Location: connexion.php');
exit();
?>
