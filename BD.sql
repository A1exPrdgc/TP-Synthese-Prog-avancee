/******************************/
/*       TP de Synthese       */
/******************************/
/*          Groupe :          */
/*                            */
/*       Guelle Clement       */
/*         Viez Remi          */
/*    Pradignac Alexandre     */
/*       Cauvin Pierre        */
/*      Chuzeville Jules      */
/******************************/

/*
   À exécuter une fois en dehors de la base :
   CREATE DATABASE tp_synthese;

   Puis se connecter sur tp_synthese et exécuter ce script.
*/

DROP TABLE IF EXISTS rattrapage CASCADE;
DROP TABLE IF EXISTS etat_rattrapage CASCADE;
DROP TABLE IF EXISTS ds CASCADE;
DROP TABLE IF EXISTS matiere CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS etudiant CASCADE;
DROP TABLE IF EXISTS semestre CASCADE;
DROP TABLE IF EXISTS enseignant CASCADE;
DROP TABLE IF EXISTS personne CASCADE;

-- TABLES DE BASE

CREATE TABLE personne (
    id_personne  SERIAL PRIMARY KEY,
    nom          VARCHAR(50) NOT NULL,
    prenom       VARCHAR(50) NOT NULL,
    email        VARCHAR(100) UNIQUE
);

CREATE TABLE enseignant (
    id_enseignant SERIAL PRIMARY KEY,
    id_personne   INT NOT NULL UNIQUE,
    FOREIGN KEY (id_personne) 
        REFERENCES personne(id_personne)
        ON DELETE CASCADE
);

-- Semestre (BUT1-S1, BUT1-S2, etc.)
CREATE TABLE semestre (
    id_semestre SERIAL PRIMARY KEY,
    code        VARCHAR(2) NOT NULL UNIQUE,  -- ex : S1, S2...
    libelle     VARCHAR(50) NOT NULL,         -- ex : "BUT1 - S1"
    annee       INT NOT NULL
);

CREATE TABLE etudiant (
    id_etudiant      SERIAL PRIMARY KEY,
    id_personne      INT NOT NULL UNIQUE,
    id_semestre      INT NOT NULL,
    numero_etudiant  VARCHAR(20) UNIQUE NOT NULL,
    date_creation    DATE DEFAULT CURRENT_DATE,

    FOREIGN KEY (id_personne) 
        REFERENCES personne(id_personne)
        ON DELETE CASCADE,

    FOREIGN KEY (id_semestre)
        REFERENCES semestre(id_semestre)
        ON DELETE CASCADE
);

CREATE TABLE users (
    username    VARCHAR(50) PRIMARY KEY,
    id_personne INT NOT NULL,
    password    VARCHAR(100) NOT NULL,
    -- rôle de l'utilisateur (DE, ENS, ADMIN)
    role        VARCHAR(20) NOT NULL DEFAULT 'ENS',
    reset_token VARCHAR(100),
    reset_expires TIMESTAMP,

    FOREIGN KEY (id_personne) 
        REFERENCES personne(id_personne)
        ON DELETE CASCADE
);

-- Matières et DS

CREATE TABLE matiere (
    id_matiere SERIAL PRIMARY KEY,
    nom        VARCHAR(100) NOT NULL
);

CREATE TABLE ds (
    id_ds      SERIAL PRIMARY KEY,
    date_ds    DATE NOT NULL,
    id_matiere INT NOT NULL,

    FOREIGN KEY (id_matiere) 
        REFERENCES matiere(id_matiere)
        ON DELETE CASCADE
);

-- États de rattrapage (PREVU, FAIT, ANNULE...)

CREATE TABLE etat_rattrapage (
    id_etat SERIAL PRIMARY KEY,
    code    VARCHAR(20) NOT NULL UNIQUE,  -- ex: "PREVU", "FAIT", "ANNULE"
    libelle VARCHAR(50) NOT NULL
);

-- Table RATTRAPAGE

CREATE TABLE rattrapage (
    id_rattrapage   SERIAL PRIMARY KEY,
    id_etudiant     INT NOT NULL,
    id_ds           INT,            -- rattrapage lié à un DS particulier (optionnel)
    id_matiere      INT,            -- ou directement la matière
    id_semestre     INT NOT NULL,
    id_enseignant   INT NOT NULL,
    date_rattrapage DATE NOT NULL,
    duree_minutes   INT NOT NULL,
    absent          BOOLEAN DEFAULT FALSE,
    absent_justifie BOOLEAN DEFAULT FALSE,
    id_etat         INT NOT NULL,    -- PREVU/FAIT/ANNULE...
    mail_envoye     BOOLEAN DEFAULT FALSE,
    date_creation   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_etudiant)   REFERENCES etudiant(id_etudiant)   ON DELETE CASCADE,
    FOREIGN KEY (id_ds)         REFERENCES ds(id_ds)               ON DELETE SET NULL,
    FOREIGN KEY (id_matiere)    REFERENCES matiere(id_matiere)     ON DELETE SET NULL,
    FOREIGN KEY (id_semestre)   REFERENCES semestre(id_semestre),
    FOREIGN KEY (id_enseignant) REFERENCES enseignant(id_enseignant),
    FOREIGN KEY (id_etat)       REFERENCES etat_rattrapage(id_etat)
);