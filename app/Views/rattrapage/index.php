<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des rattrapages</title>
</head>
<body>

<h1>Liste des rattrapages</h1>

<?php
    $role = $user['role'] ?? 'UTIL';
?>

<p>
    Bonjour
    <?php if (!empty($user['prenom']) || !empty($user['nom'])) : ?>
        <?= esc(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?>
    <?php else : ?>
        <?= esc($user['username'] ?? '') ?>
    <?php endif; ?>
    (rôle : <?= esc($role) ?>)
</p>

<p>
    <a href="<?= site_url('logout') ?>">Se déconnecter</a>
</p>

<!-- Barre de filtre / tri -->
<h2>Filtres et tri</h2>

<form method="get" action="<?= site_url('rattrapage') ?>">
    <table>
        <tr>
            <th><label for="id_semestre">Semestre :</label></th>
            <td>
                <select name="id_semestre" id="id_semestre">
                    <option value="">-- Tous --</option>
                    <?php if (!empty($semestres)) : ?>
                        <?php foreach ($semestres as $sem) : ?>
                            <option value="<?= esc($sem['id_semestre']) ?>"
                                <?= (isset($currentSemestre) && $currentSemestre == $sem['id_semestre']) ? 'selected' : '' ?>>
                                <?= esc($sem['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th><label for="tri">Trier par :</label></th>
            <td>
                <select name="tri" id="tri">
                    <option value="">-- Aucun --</option>
                    <option value="date"      <?= (isset($tri) && $tri === 'date') ? 'selected' : '' ?>>Date</option>
                    <option value="etudiant"  <?= (isset($tri) && $tri === 'etudiant') ? 'selected' : '' ?>>Étudiant</option>
                    <option value="matiere"   <?= (isset($tri) && $tri === 'matiere') ? 'selected' : '' ?>>Enseignement</option>
                    <option value="enseignant"<?= (isset($tri) && $tri === 'enseignant') ? 'selected' : '' ?>>Enseignant</option>
                    <option value="etat"      <?= (isset($tri) && $tri === 'etat') ? 'selected' : '' ?>>État</option>
                </select>
            </td>
        </tr>

        <tr>
            <th><label for="sens">Ordre :</label></th>
            <td>
                <select name="sens" id="sens">
                    <option value="asc"  <?= (isset($sens) && $sens === 'asc')  ? 'selected' : '' ?>>Croissant</option>
                    <option value="desc" <?= (isset($sens) && $sens === 'desc') ? 'selected' : '' ?>>Décroissant</option>
                </select>
            </td>
        </tr>

        <tr>
            <th>Filtre étudiant :</th>
            <td>
                <input type="text" name="filtre_etudiant" value="<?= esc($filtre_etudiant ?? '') ?>" placeholder="Nom / numéro">
            </td>
        </tr>

        <tr>
            <th>Filtre enseignant :</th>
            <td>
                <input type="text" name="filtre_enseignant" value="<?= esc($filtre_enseignant ?? '') ?>" placeholder="Nom enseignant">
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <button type="submit">Appliquer</button>
                <a href="<?= site_url('rattrapage') ?>">Réinitialiser</a>
            </td>
        </tr>
    </table>
</form>

<?php if ($role === 'DE') : ?>
    <p>
        <a href="<?= site_url('rattrapage/create') ?>">Ajouter un rattrapage</a>
    </p>
<?php endif; ?>

<h2>Rattrapages</h2>

<?php if (!empty($rattrapages)) : ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Semestre</th>
            <th>Ressource</th>
            <th>Date</th>
            <th>Durée (min)</th>
            <th>Enseignant</th>
            <th>État</th>
            <th>Détails</th>
            <?php if ($role === 'DE') : ?>
                <th>Modifier</th>
                <th>Supprimer</th>
            <?php endif; ?>
        </tr>

        <?php foreach ($rattrapages as $rat) : ?>
            <tr>
                <td><?= esc($rat['id_rattrapage']) ?></td>
                <td><?= esc($rat['semestre'] ?? '') ?></td>
                <td><?= esc($rat['matiere'] ?? '') ?></td>
                <td><?= esc($rat['date_rattrapage']) ?></td>
                <td><?= esc($rat['duree_minutes']) ?></td>
                <td>
                    <?= esc(($rat['enseignant_nom'] ?? '') . ' ' . ($rat['enseignant_prenom'] ?? '')) ?>
                </td>
                <td><?= esc($rat['etat'] ?? '') ?></td>
                <td>
                    <a href="<?= site_url('rattrapage/show/' . $rat['id_rattrapage']) ?>">Voir</a>
                </td>

                <?php if ($role === 'DE') : ?>
                    <td>
                        <a href="<?= site_url('rattrapage/edit/' . $rat['id_rattrapage']) ?>">Modifier</a>
                    </td>
                    <td>
                        <a href="<?= site_url('rattrapage/delete/' . $rat['id_rattrapage']) ?>"
                           onclick="return confirm('Confirmer la suppression de ce rattrapage ?');">
                            Supprimer
                        </a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else : ?>
    <p>Aucun rattrapage trouvé.</p>
<?php endif; ?>

</body>
</html>
