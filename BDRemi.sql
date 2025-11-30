DROP TABLE IF EXISTS ressource CASCADE;
DROP TABLE IF EXISTS semestre CASCADE;
DROP TABLE IF EXISTS rattrapage CASCADE;
DROP TABLE IF EXISTS rattrapants CASCADE;
DROP TABLE IF EXISTS ds CASCADE;
DROP TABLE IF EXISTS absence CASCADE;
DROP TABLE IF EXISTS etudiant CASCADE;
DROP TABLE IF EXISTS enseignant CASCADE;
DROP TABLE IF EXISTS personne CASCADE;
DROP TABLE IF EXISTS enseignant_ressource CASCADE;

DROP TYPE IF EXISTS fonction CASCADE;
DROP TYPE IF EXISTS type_exam CASCADE;
DROP TYPE IF EXISTS etat CASCADE;

CREATE TYPE fonction AS ENUM ('ENS', 'DE');
CREATE TYPE type_exam AS ENUM ('MACHINE', 'ORAL', 'PAPIER');
CREATE TYPE etat AS ENUM ('PREVU', 'REFUSE', 'TERMINE', 'EN ATTENTE');

CREATE TABLE personne (
    code VARCHAR(10) PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE
);

CREATE TABLE enseignant (
    password VARCHAR(100) NOT NULL,
    fonction fonction NOT NULL DEFAULT 'ENS',
    reset_token VARCHAR(100),
    reset_expires TIMESTAMP,
    PRIMARY KEY (code)
) INHERITS (personne);

CREATE TABLE semestre (
    id_semestre SERIAL PRIMARY KEY,
    code VARCHAR(2) NOT NULL UNIQUE,
    annee VARCHAR(15) NOT NULL
);

CREATE TABLE etudiant (
    code VARCHAR(10) PRIMARY KEY,
    classe VARCHAR(5),
    id_semestre INT NOT NULL,
    FOREIGN KEY (id_semestre) REFERENCES semestre(id_semestre) ON DELETE CASCADE,
    FOREIGN KEY (code) REFERENCES personne(code) ON DELETE CASCADE
) INHERITS (personne);

CREATE TABLE ressource (
    codeRessource VARCHAR(10) PRIMARY KEY,
    nomRessource VARCHAR(100) NOT NULL
);

CREATE TABLE ds (
    id_ds SERIAL PRIMARY KEY,
    id_semestre INT NOT NULL,
    date_ds DATE NOT NULL,
    duree_minutes INT NOT NULL,
    type_exam type_exam NOT NULL,
    codeRessource VARCHAR(10) NOT NULL,
    codeEnseignant VARCHAR(10) NOT NULL,
    etat etat NOT NULL DEFAULT 'EN ATTENTE',
    FOREIGN KEY (codeRessource) REFERENCES ressource(codeRessource) ON DELETE CASCADE,
    FOREIGN KEY (id_semestre) REFERENCES semestre(id_semestre) ON DELETE CASCADE,
    FOREIGN KEY (codeEnseignant) REFERENCES enseignant(code) ON DELETE CASCADE
);

CREATE TABLE absence (
    id_ds INT,
    code VARCHAR(10),
    absenceJustifie SMALLINT NOT NULL DEFAULT 0,
    rattrape SMALLINT NOT NULL DEFAULT 0,
    PRIMARY KEY (id_ds, code),
    FOREIGN KEY (id_ds) REFERENCES ds(id_ds) ON DELETE CASCADE,
    FOREIGN KEY (code) REFERENCES etudiant(code) ON DELETE CASCADE
);

CREATE TABLE rattrapage (
    id_rattrapage SERIAL PRIMARY KEY,
    id_ds INT NOT NULL,
    code VARCHAR(10) NOT NULL,
    date_rattrapage DATE NOT NULL,
    duree_minutes INT NOT NULL,
    heure_debut TIME,
    etat etat NOT NULL DEFAULT 'EN ATTENTE',
    mail_envoye SMALLINT DEFAULT 0,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    salle VARCHAR(10),
    type_exam type_exam NOT NULL,
    FOREIGN KEY (id_ds) REFERENCES ds(id_ds) ON DELETE CASCADE,
    FOREIGN KEY (code) REFERENCES enseignant(code) ON DELETE CASCADE
);

CREATE TABLE rattrapants (
    id_rattrapage INT NOT NULL,
    codeEtudiant VARCHAR(10) NOT NULL,
    PRIMARY KEY (id_rattrapage, codeEtudiant),
    FOREIGN KEY (id_rattrapage) REFERENCES rattrapage(id_rattrapage) ON DELETE CASCADE,
    FOREIGN KEY (codeEtudiant) REFERENCES etudiant(code) ON DELETE CASCADE
);

CREATE TABLE enseignant_ressource (
    codeEnseignant VARCHAR(10) NOT NULL,
    codeRessource VARCHAR(10) NOT NULL,
    PRIMARY KEY (codeEnseignant, codeRessource),
    FOREIGN KEY (codeEnseignant) REFERENCES enseignant(code) ON DELETE CASCADE,
    FOREIGN KEY (codeRessource) REFERENCES ressource(codeRessource) ON DELETE CASCADE
);
