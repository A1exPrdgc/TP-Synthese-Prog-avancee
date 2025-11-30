-- Insérer semestres
INSERT INTO semestre (code, annee) VALUES
    ('S1', '2025-2026'),
    ('S2', '2025-2026'),
    ('S3', '2025-2026'),
    ('S4', '2025-2026'),
    ('S5', '2025-2026'),
    ('S6', '2025-2026');

-- Insérer ressources
INSERT INTO ressource (codeRessource, nomRessource) VALUES
    ('R1.01', 'Initiation au développement'),
    ('R1.02', 'Développement interfaces Web'),
    ('R1.03', 'Introduction Architecture'),
    ('R1.04', 'Introduction Système'),
    ('R1.05', 'Introduction Base de données'),
    ('R1.06', 'Mathématiques discrètes'),
    ('R1.07', 'Outils mathématiques fondamentaux'),
    ('R1.08', 'Gestion de projet et des organisations'),
    ('R1.09', 'Économie durable et numérique'),
    ('R1.10', 'Anglais Technique'),
    ('R1.11', 'Bases de la communication'),
    ('R1.12', 'Projet Professionnel et Personnel');

-- Insérer personnes (enseignants + étudiants)
INSERT INTO personne (code, nom, prenom, email) VALUES
    ('lp000001', 'Le Pivert', 'Phillipe', 'lp000001@etu.univ-lehavre.fr'),
    ('bh000002', 'Boukachour', 'Hadhoum', 'bh000002@etu.univ-lehavre.fr'),
    ('ma000001', 'Martin', 'Alice', 'ma000001@etu.univ-lehavre.fr'),
    ('db000002', 'Dufour', 'Bob', 'db000002@etu.univ-lehavre.fr');


-- Insérer étudiants
INSERT INTO etudiant (code, classe, id_semestre) VALUES
    ('ma000001', 'A1', 1),
    ('db000002', 'A1', 1);

-- Insérer_ds
INSERT INTO ds (id_semestre, date_ds, duree_minutes, type_exam, codeRessource, codeEnseignant, etat) VALUES
    (1, '2025-10-15', 120, 'PAPIER', 'R1.01', 'lp000001', 'PREVU'),
    (1, '2025-10-20', 90, 'ORAL', 'R1.02', 'bh000002', 'PREVU');

-- Insérer absences
INSERT INTO absence (id_ds, code, absenceJustifie, rattrape) VALUES
    (1, 'ma000001', 0, 0),
    (1, 'db000002', 1, 0);

-- Insérer rattrapages
INSERT INTO rattrapage (id_ds, codeEnseignant, date_rattrapage, duree_minutes, heure_debut, etat, mail_envoye, salle, type_exam) VALUES
    (1, 'lp000001', '2025-11-15', 120, '09:00', 'EN ATTENTE', 0, 'S101', 'PAPIER');

-- Insérer rattrapants
INSERT INTO rattrapants (id_rattrapage, codeEtudiant) VALUES
    (1, 'ma000001');

-- Associer enseignant et ressources
INSERT INTO enseignant_ressource (codeEnseignant, codeRessource) VALUES
    ('lp000001', 'R1.01'),
    ('bh000002', 'R1.02');
