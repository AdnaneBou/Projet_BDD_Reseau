SELECT * FROM lecteurs WHERE id_lecteur = id_lecteur;

SELECT * FROM etudiants WHERE numero_carte_etudiant = numero_carte_etudiant;

SELECT * FROM etudiants WHERE id_persn_etudiant = id_persn_etudiant;

SELECT statut_etudiant FROM assister WHERE numero_carte_etudiant = numero_carte_etudiant;

SELECT COUNT(*) AS nombre_etudiant FROM etudiants;

SELECT nom_materiel, quantite FROM materiels ORDER BY quantite ASC;

SELECT ROUND(AVG(effectif_salle), 2) AS moyenne_salle FROM salles;

SELECT id_salle, effectif_salle FROM salles WHERE effectif_salle > ( SELECT ROUND(AVG(effectif_salle), 2) FROM salles ) ORDER BY effectif_salle ASC;

SELECT * FROM personnes WHERE email = :email AND mot_de_passe = :mot_de_passe;