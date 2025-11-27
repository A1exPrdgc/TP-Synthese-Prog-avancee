-- ATTENTION : Décalage entre les ds et les abscence à cause du serial

-- DELETE FROM semestre;
-- DELETE FROM Ressource;
-- DELETE FROM enseignant;
-- DELETE FROM ds;
-- DELETE FROM etudiant;
-- DELETE FROM absence;

-- INSERT INTO semestre (id_semestre, code, annee) VALUES
--     (1, 'S1', '2025-2026'),
--     (2, 'S2', '2025-2026'),
--     (3, 'S3', '2025-2026'),
--     (4, 'S4', '2025-2026'),
--     (5, 'S5', '2025-2026'),
--     (6, 'S6', '2025-2026');

-- INSERT INTO Ressource (codeRessource, nomRessource) VALUES
--     ('R1.01', 'Initiation au développement'),
--     ('R1.02', 'Développement interfaces Web'),
--     ('R1.03', 'Introduction Architecture'),
--     ('R1.04', 'Introduction Système'),
--     ('R1.05', 'Introduction Base de données'),
--     ('R1.06', 'Mathématiques discrètes'),
--     ('R1.07', 'Outils mathématiques fondamentaux'),
--     ('R1.08', 'Gestion de projet et des organisations'),
--     ('R1.09', 'Économie durable et numérique'),
--     ('R1.10', 'Anglais Technique'),
--     ('R1.11', 'Bases de la communication'),
--     ('R1.12', 'Projet Professionnel et Personnel');

-- INSERT INTO enseignant (code, nom, prenom, email, fonction, password) VALUES
--     ('lp000001', 'Le Pivert', 'Phillipe', 'lp000001@etu.univ-lehavre.fr', 'DE', '$2y$10$CZZsWaiOfLpNJMzyKlIRJObrtaSGJ4aTOh4w/CefwFj6Ojln0gJxi'),
--     ('bh000002', 'Boukachour', 'Hadhoum', 'bh000002@etu.univ-lehavre.fr', 'ENS', '$2y$10$CZZsWaiOfLpNJMzyKlIRJObrtaSGJ4aTOh4w/CefwFj6Ojln0gJxi'),
--     ('za000003', 'Zahour', 'Abderrazak', 'za000003@etu.univ-lehavre.fr', 'ENS', '$2y$10$CZZsWaiOfLpNJMzyKlIRJObrtaSGJ4aTOh4w/CefwFj6Ojln0gJxi'),
--     ('bj000004', 'Boukachour', 'Jaouad', 'bj000004@etu.univ-lehavre.fr', 'ENS', '$2y$10$CZZsWaiOfLpNJMzyKlIRJObrtaSGJ4aTOh4w/CefwFj6Ojln0gJxi'),
--     ('lb000005', 'Legrix', 'Bruno', 'lb000005@etu.univ-lehavre.fr', 'ENS', '$2y$10$CZZsWaiOfLpNJMzyKlIRJObrtaSGJ4aTOh4w/CefwFj6Ojln0gJxi'),
--     ('gq000006', 'Griette', 'Quentin', 'gq000006@etu.univ-lehavre.fr', 'ENS', '$2y$10$CZZsWaiOfLpNJMzyKlIRJObrtaSGJ4aTOh4w/CefwFj6Ojln0gJxi'),
--     ('lq000007', 'Laffeach', 'Quentin', 'lq000007@etu.univ-lehavre.fr', 'ENS', '$2y$10$CZZsWaiOfLpNJMzyKlIRJObrtaSGJ4aTOh4w/CefwFj6Ojln0gJxi'),
--     ('nl000008', 'Nivet', 'Laurence', 'nl000008@etu.univ-lehavre.fr', 'ENS', '$2y$10$CZZsWaiOfLpNJMzyKlIRJObrtaSGJ4aTOh4w/CefwFj6Ojln0gJxi');

-- INSERT INTO ds (id_semestre, date_ds, duree_minutes, type_exam, coderessource, codeenseignant) VALUES
--     (1, '2025-10-15 09:00:00', 120, 'PAPIER', 'R1.01', 'lp000001'),
--     (1, '2025-10-20 14:00:00', 90, 'ORAL', 'R1.02', 'bh000002'),
--     (1, '2026-01-10 10:00:00', 180, 'PAPIER', 'R1.03', 'za000003'),
--     (1, '2026-01-15 13:30:00', 60, 'ORAL', 'R1.04', 'bj000004'),
--     (1, '2026-05-05 09:00:00', 150, 'PAPIER', 'R1.05', 'lb000005'),
--     (1, '2026-05-10 11:00:00', 120, 'ORAL', 'R1.06', 'gq000006');

-- INSERT INTO etudiant (code, nom, prenom, email, classe) VALUES
--     ('ma000001', 'Martin', 'Alice', 'ma000001@etu.univ-lehavre.fr', 'A1'),
--     ('db000002', 'Dufour', 'Bob', 'db000002@etu.univ-lehavre.fr', 'A1'),
--     ('cc000003', 'Clément', 'Claire', 'cc000003@etu.univ-lehavre.fr', 'B1'),
--     ('rs000004', 'Rousseau', 'Samuel', 'rs000004@etu.univ-lehavre.fr', 'B1'),
--     ('ee000005', 'Evans', 'Eva', 'ee000005@etu.univ-lehavre.fr', 'C1'),
--     ('tg000006', 'Tanguy', 'Guillaume', 'tg000006@etu.univ-lehavre.fr', 'C2'),
--     ('ms000007', 'Moreau', 'Sophie', 'ms000007@etu.univ-lehavre.fr', 'B2'),
--     ('ph000008', 'Petit', 'Hugo', 'ph000008@etu.univ-lehavre.fr', 'A2'),
--     ('li000009', 'Leroy', 'Isabelle', 'li000009@etu.univ-lehavre.fr', 'B2'),
--     ('cj000010', 'Carpentier', 'Julien', 'cj000010@etu.univ-lehavre.fr', 'A2');

INSERT INTO absence (code, id_ds, absenceJustifie) VALUES
    ('ma000001', 25, 0),
    ('db000002', 25, 1),
    ('cc000003', 26, 0),
    ('rs000004', 26, 0),
    ('ee000005', 27, 1),
    ('tg000006', 27, 0),
    ('ms000007', 28, 0),
    ('ph000008', 28, 1),
    ('li000009', 29, 0),
    ('cj000010', 29, 0);