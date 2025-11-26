<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des rattrapages</title>
</head>
<body>

    <h1>Liste des rattrapages</h1>

    <p>Connecté en tant que : <?= esc($role) ?></p>

    <p><a href="<?= site_url('logout') ?>">Se déconnecter</a></p>

    <?php if (!empty($rattrapages)) : ?>
        <table border="1" cellpadding="4" cellspacing="0">
            <thead>
                <tr>
                    <th>Étudiant</th>
                    <th>Numéro</th>
                    <th>Matière</th>
                    <th>Semestre</th>
                    <th>Enseignant</th>
                    <th>Date</th>
                    <th>Durée (min)</th>
                    <th>État</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rattrapages as $r) : ?>
                    <tr>
                        <td><?= esc($r['prenom_etud'] . ' ' . $r['nom_etud']) ?></td>
                        <td><?= esc($r['numero_etudiant']) ?></td>
                        <td><?= esc($r['matiere']) ?></td>
                        <td><?= esc($r['semestre']) ?></td>
                        <td><?= esc($r['prenom_ens'] . ' ' . $r['nom_ens']) ?></td>
                        <td><?= esc($r['date_rattrapage']) ?></td>
                        <td><?= esc($r['duree_minutes']) ?></td>
                        <td><?= esc($r['libelle_etat']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Aucun rattrapage pour le moment.</p>
    <?php endif; ?>

</body>
</html>
