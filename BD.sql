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

DROP TABLE IF EXISTS ressource  CASCADE;
DROP TABLE IF EXISTS semestre   CASCADE;
DROP TABLE IF EXISTS rattrapage CASCADE;
DROP TABLE IF EXISTS ds         CASCADE;
DROP TABLE IF EXISTS etudiant   CASCADE;
DROP TABLE IF EXISTS absence    CASCADE;
DROP TABLE IF EXISTS enseignant CASCADE;
DROP TABLE IF EXISTS personne   CASCADE;

DROP TYPE IF EXISTS fonction  CASCADE;
DROP TYPE IF EXISTS type_exam CASCADE;
DROP TYPE IF EXISTS etat      CASCADE;

CREATE TYPE fonction  as ENUM ('ENS', 'DE');
CREATE TYPE type_exam as ENUM ('MACHINE', 'ORAL', 'PAPIER');
CREATE TYPE etat      as ENUM ('PREVU', 'REFUSE', 'TERMINE', 'EN ATTENTE');

-- TABLE PERSONNE ET FILLES

CREATE TABLE personne (
    code   VARCHAR(10) PRIMARY KEY,
    nom    VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email  VARCHAR(100) UNIQUE
);

CREATE TABLE enseignant (
    password      VARCHAR(100) NOT NULL,
    fonction      fonction NOT NULL DEFAULT 'ENS',
    reset_token   VARCHAR(100),
    reset_expires TIMESTAMP,
    PRIMARY KEY (code)
) INHERITS (personne);

CREATE TABLE etudiant (
    classe VARCHAR(5),
    PRIMARY KEY (code)
) INHERITS (personne);

-- Semestre
CREATE TABLE semestre (
    id_semestre SERIAL PRIMARY KEY,
    code        VARCHAR(2) NOT NULL UNIQUE,
    annee       VARCHAR(15) NOT NULL
);

-- Ressources et DS

CREATE TABLE ressource (
    codeRessource VARCHAR(10) PRIMARY KEY,
    nomRessource  VARCHAR(100) NOT NULL
);

CREATE TABLE ds (
    id_ds         SERIAL PRIMARY KEY,
    id_semestre   INT NOT NULL,
    date_ds       DATE NOT NULL,
    duree_minutes INT NOT NULL,
    type_exam     type_exam NOT NULL,
    codeRessource VARCHAR(10) NOT NULL,
    codeEnseignant VARCHAR(10) NOT NULL,

    FOREIGN KEY (codeRessource)
        REFERENCES ressource(codeRessource)
        ON DELETE CASCADE,

    FOREIGN KEY (id_semestre)
        REFERENCES semestre(id_semestre)
        ON DELETE CASCADE,

    FOREIGN KEY (codeEnseignant)
        REFERENCES enseignant(code)
        ON DELETE CASCADE
);

-- Table ABSENCE

CREATE TABLE absence (
    id_ds          INT,
    code           VARCHAR(10),
    absenceJustifie SMALLINT NOT NULL DEFAULT 0,

    FOREIGN KEY (id_ds)
        REFERENCES ds(id_ds)
        ON DELETE CASCADE,

    FOREIGN KEY (code)
        REFERENCES etudiant(code)
        ON DELETE CASCADE,

    PRIMARY KEY(id_ds, code)
);


CREATE TABLE rattrapage (
    id_rattrapage   SERIAL PRIMARY KEY,
    id_ds           INT NOT NULL,
    code            VARCHAR(10) NOT NULL,
    date_rattrapage DATE NOT NULL,
    duree_minutes   INT NOT NULL,
    heure_debut     TIME,
    etat            etat NOT NULL DEFAULT 'EN ATTENTE',
    mail_envoye     SMALLINT DEFAULT 0,
    date_creation   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    salle           VARCHAR(10),

    FOREIGN KEY (id_ds) REFERENCES ds(id_ds) ON DELETE CASCADE,
    FOREIGN KEY (code)  REFERENCES enseignant(code) ON DELETE CASCADE
);