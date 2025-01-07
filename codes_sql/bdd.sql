--DROP TABLE/TYPE ...
DROP TABLE IF EXISTS personnes;
DROP TABLE IF EXISTS etudiants;
DROP TABLE IF EXISTS directeurs;
DROP TABLE IF EXISTS groupes;
DROP TABLE IF EXISTS enseignants;
DROP TABLE IF EXISTS materiels;
DROP TABLE IF EXISTS salles;
DROP TABLE IF EXISTS departements;
DROP TABLE IF EXISTS cours;
DROP TABLE IF EXISTS assister;

DROP TYPE IF EXISTS type_salle;
DROP TYPE IF EXISTS etat_lecteur;
DROP TYPE IF EXISTS genre;
DROP TYPE IF EXISTS status_assister;

-- Type
CREATE TYPE type_salle AS ENUM ('amphitheatre', 'td', 'tp', 'reseau');
CREATE TYPE etat_lecteur AS ENUM ('fonctionel', 'en panne', 'en reparation');
CREATE TYPE genre AS ENUM ('homme', 'femme', 'non binaire');
CREATE TYPE status_assister AS ENUM ('present', 'absent');

-- Tables

CREATE TABLE personnes
(
    id_personne      CHAR(9) PRIMARY KEY,
    nom              VARCHAR(60) NOT NULL,
    prenom           VARCHAR(60) NOT NULL,
    numero_telephone CHAR(10)    NOT NULL,
    email            VARCHAR(64) NOT NULL UNIQUE,
    adresse          VARCHAR(90) NOT NULL,
    date_naissance   DATE        NOT NULL,
    mot_de_passe     VARCHAR(70) NOT NULL,	
    genre            genre       NOT NULL
);

CREATE TABLE etudiants
(
    id_persn_etudiant     CHAR(9) PRIMARY KEY,
    est_redoublant        BOOLEAN       NOT NULL,
    est_boursier          BOOLEAN       NOT NULL,
    numero_carte_etudiant CHAR(8)       NOT NULL,

    FOREIGN KEY (id_persn_etudiant) REFERENCES personnes (id_personne)
);

CREATE TABLE directeurs
(
    id_persn_directeur    CHAR(9) PRIMARY KEY,
    date_nomination       DATE          NOT NULL,
    titre_poste           VARCHAR(50)   NOT NULL,
    duree_mandat          INTEGER       NOT NULL,

    FOREIGN KEY (id_persn_directeur) REFERENCES personnes (id_personne)
);

CREATE TABLE groupes
(
    id_groupe         CHAR(9) PRIMARY KEY,
    capacite_max      INT         NOT NULL,
    nom_groupe        VARCHAR(20) NOT NULL,
    effectif_groupe   INT         NOT NULL,
    fk_etudiant       CHAR(9)     NOT NULL

    FOREIGN KEY (fk_etudiant) REFERENCES etudiants (id_persn_etudiant)
);

CREATE TABLE enseignants
(
    id_persn_enseignant CHAR(9) PRIMARY KEY,
    specialite          VARCHAR(20)    NOT NULL,
    poste               VARCHAR(20)    NOT NULL,
    fk_groupe           CHAR(8)        NOT NULL,

    FOREIGN KEY (id_persn_enseignant) REFERENCES personnes (id_personne),
    FOREIGN KEY (fk_groupe) REFERENCES groupes (id_groupe)
);

CREATE TABLE materiels
(
    id_materiel     CHAR(8) PRIMARY KEY,
    nom_materiel    VARCHAR(40)         NOT NULL,
    quantite        INT                 NOT NULL,
    categorie       categorie_materiel  NOT NULL

);

CREATE TABLE salles
(
    id_salle        CHAR(9) PRIMARY KEY,
    nom_salle       VARCHAR(10)   NOT NULL,
    effectif_salle  INT         NOT NULL,
    batiment        VARCHAR(10) NOT NULL,
    etage           INT         NOT NULL,
    categorie       type_salle  NOT NULL,   
    fk_materiel     CHAR(9)     NOT NULL,

    FOREIGN KEY (fk_materiel) REFERENCES materiels (id_materiel)
);

CREATE TABLE departements
(
    id_departement  CHAR(7) PRIMARY KEY,
    nom             VARCHAR(80)         NOT NULL,
    adresse         VARCHAR(100)        NOT NULL

);

CREATE TABLE lecteurs
(
    id_lecteur      CHAR(8) PRIMARY KEY,
    modele          VARCHAR(45)         NOT NULL,
    etat            etat_lecteur        NOT NULL,
    fk_salle        CHAR(9)             NOT NULL,

    FOREIGN KEY (fk_salle) REFERENCES salles (id_salle)
);

CREATE TABLE cours
(
    id_cours        CHAR(8) PRIMARY KEY,
    nom_cours       VARCHAR(40)   NOT NULL,
    heure_debut     TIME          NOT NULL CHECK (heure_debut >= '08:00:00'),
    heure_fin       TIME          NOT NULL CHECK (heure_fin <= '18:00:00'),
    CHECK (heure_debut < heure_fin),
    date            DATE          NOT NULL,
    fk_salle        CHAR(9)       NOT NULL,
    fk_enseignant   CHAR(9)       NOT NULL,

    FOREIGN KEY (fk_salle) REFERENCES salles (id_salle),
    FOREIGN KEY (fk_enseignant) REFERENCES enseignants (id_persn_enseignant)
);

CREATE TABLE assister
(
    id_cours           CHAR(8) PRIMARY KEY,
    id_persn_etudiant  CHAR(9) PRIMARY KEY,
    statut_etudiant    status_assister   NOT NULL,
    heure_arriver      TIME              NOT NULL,

    FOREIGN KEY (id_cours) REFERENCES cours (id_cours),
    FOREIGN KEY (id_persn_etudiant) REFERENCES etudiants (id_etudiant)
);


-- Insertion de données

INSERT INTO personnes (id_personne, nom, prenom, num_telephone, email, adresse, date_naissance, mot_de_passe, genre) VALUES
('122001185', 'MEBAKRI', 'Khalifa', '0768760148', 'Abc.de@xx.com', '23 bd du port Cergy', '2001-09-23', 'mdp2024/E', 'homme'),
('122001186', 'DUPONT', 'Marie', '0654321987', 'marie.dupont@exemple.com', '45 rue Lafayette Paris', '1999-07-15', 'mdp2024/M', 'femme'),
('122001187', 'MARTIN', 'Paul', '0789465231', 'paul.martin@exemple.com', '12 avenue République Lyon', '2000-05-20', 'mdp2024/P', 'homme'),
('122001188', 'DURAND', 'Sophie', '0647895230', 'sophie.durand@exemple.com', '78 place Bellecour Lyon', '2003-11-02', 'mdp2024/S', 'femme'),
('122001189', 'LECLERC', 'Julien', '0678123094', 'julien.leclerc@exemple.com', '9 quai Voltaire Paris', '2002-03-18', 'mdp2024/J', 'homme'),
('122001190', 'ROUX', 'Amélie', '0632157894', 'amelie.roux@exemple.com', '15 rue des Lilas Toulouse', '1998-08-12', 'mdp2024/A', 'femme'),
('122001191', 'MOREL', 'Lucas', '0778945623', 'lucas.morel@exemple.com', '34 rue Victor Hugo Marseille', '1997-12-05', 'mdp2024/L', 'homme'),
('122001192', 'GIRARD', 'Camille', '0698741236', 'camille.girard@exemple.com', '21 place de la Comédie Montpellier', '2001-04-22', 'mdp2024/C', 'femme'),
('122001193', 'FOURNIER', 'Thomas', '0621457890', 'thomas.fournier@exemple.com', '8 allée des Roses Nice', '2000-10-10', 'mdp2024/T', 'homme'),
('122001194', 'ROBIN', 'Emma', '0643219785', 'emma.robin@exemple.com', '19 boulevard Haussmann Paris', '2002-06-30', 'mdp2024/E', 'femme'),
('122001195', 'PETIT', 'Nathan', '0658743291', 'nathan.petit@exemple.com', '22 avenue Foch Strasbourg', '1999-03-14', 'mdp2024/N', 'homme'),
('122001196', 'SIMON', 'Chloé', '0678941230', 'chloe.simon@exemple.com', '7 rue Nationale Bordeaux', '1998-09-27', 'mdp2024/C', 'femme'),
('122001197', 'LAMBERT', 'Bastien', '0789652143', 'bastien.lambert@exemple.com', '50 rue des Alpes Grenoble', '1996-01-08', 'mdp2024/B', 'homme'),
('122001198', 'BERTRAND', 'Léa', '0612347890', 'lea.bertrand@exemple.com', '13 avenue du Général Toulouse', '2000-07-19', 'mdp2024/L', 'femme'),
('122001199', 'FRANCOIS', 'Adrien', '0698457123', 'adrien.francois@exemple.com', '6 impasse de Église Lille', '2003-02-25', 'mdp2024/A', 'homme');

INSERT INTO etudiants (id_persn_etudiant, numero_carte_etudiant, est_redoublant, est_boursier) VALUES
('122001185', '22102591', FALSE, TRUE),
('122001186', '22102592', TRUE, TRUE),
('122001187', '22102593', FALSE, FALSE),
('122001188', '22102594', TRUE, FALSE),
('122001189', '22102595', FALSE, TRUE);

INSERT INTO groupes (id_groupe, capacite_max, nom_groupe, effectif_groupe, id_etudiant) VALUES
('grpA_info', 25, 'groupe A', 20,'122001185'),
('grpB_math', 30, 'groupe B', 28,'122001186'),
('grpC_phys', 20, 'groupe C', 18,'122001187'),
('grpD_biol', 15, 'groupe D', 12,'122001188'),
('grpE_chim', 10, 'groupe E', 8,'122001189');

INSERT INTO enseignants (id_persn_enseignant,specialite, poste, id_groupe) VALUES
('122001190', 'Informatique', 'Enseignant', 'grpA_info'),
('122001191', 'Mathématiques', 'Chef de département', 'grpB_math'),
('122001192', 'Physique', 'Enseignant chercheur', 'grpC_phys'),
('122001193', 'Chimie', 'Enseignant', 'grpD_biol'),
('122001194', 'Informatique', 'Professeur invité', 'grpE_chim');

INSERT INTO directeurs (id_persn_directeur, date_nomination, titre_poste, duree_mandat) VALUES
('122001195', '2021-09-17', 'Directeur pôle informatique', '4'),
('122001196', '2022-11-21', 'Directeur pôle mathematiques', '2'),
('122001197',  '2024-02-02', 'Directeur pôle scientifique', '5');

INSERT INTO materiels (id_materiel, nom_materiel, quantite, categorie) VALUES
('M1000200', 'Informatique', 12, 'Materiel informatique'),
('M1000201', 'Chimie', 8, 'Materiel de labo'),
('M1000202', 'Physique', 15, 'Materiel de labo'),
('M1000203', 'Mathématiques', 20, 'Materiel informatique'),
('M1000204', 'Biologie', 10, 'Materiel de labo');

INSERT INTO salles (id_salle, nom_salle, effectif_salle, batiment, etage, id_materiel) VALUES
('info2024A', 'Salle A2', 150, 'Batiment A', 5, 'M1000200'),
('math3024B', 'Salle B3', 50, 'Batiment B', 3, 'M1000201'),
('phys2024C', 'Salle C4', 100, 'Batiment C', 2, 'M1000202'),
('biol2024D', 'Salle D1', 75, 'Batiment D', 1, 'M1000203'),
('chim2024E', 'Salle E5', 25, 'Batiment E', 4, 'M1000204');

INSERT INTO lecteurs (id_lecteur, modele, etat, id_salle) VALUES
('156A', 'MBK78', 'fonctionel', 'info2024A'),
('157B', 'MBK79', 'en panne', 'math3024B'),
('158C', 'XYZ45', 'fonctionel', 'phys2024C'),
('159D', 'ABC12', 'en reparation', 'biol2024D'),
('160E', 'DEF34', 'fonctionel', 'chim2024E');

INSERT INTO cours (id_cours, nom_cours, heure_debut, heure_fin, date, id_salle, id_enseignant) VALUES
('5BEPRO4D', 'Proba Stat', '08:30', '11:45', '2024-09-16', 'info2024A', '122001190'),
('5BEPRO4E', 'Algèbre Linéaire', '09:00', '12:00', '2024-09-17', 'math3024B', '122001191'),
('5BEPRO4F', 'Analyse Numérique', '10:00', '13:00', '2024-09-18', 'phys2024C', '122001192'),
('5BEPRO4G', 'Réseaux Informatiques', '08:00', '11:00', '2024-09-19', 'biol2024D', '122001193'),
('5BEPRO4H', 'Bases de Données', '14:00', '17:00', '2024-09-20', 'chim2024E', '122001194');

INSERT INTO assister (id_etudiant, id_cours, statut_etudiant, heure_arriver) VALUES
('122001185', '5BEPRO4D', 'present', '08:25'),
('122001186', '5BEPRO4E', 'absent', NULL),
('122001187', '5BEPRO4D', 'absent', NULL),
('122001188', '5BEPRO4F', 'absent', NULL),
('122001189', '5BEPRO4F', 'absent', NULL);