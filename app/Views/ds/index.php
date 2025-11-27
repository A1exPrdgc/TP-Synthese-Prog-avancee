<?= $this->extend('layouts/default') ?>
<?= $this->section('pageType'); ?>ds<?= $this->endSection(); ?>

<?= $this->section('title') ?>
Liste des DS
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/ds-index.css') ?>" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('navbarTitle') ?>
MySGRDS | DS
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="ds-index-container">
    <!-- Section Filtres -->
    <div class="filters-section">
        <?php echo form_open('DS', ['method' => 'get', 'class' => 'filters-form']); ?>
        <div class="filters-row">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" name="keyword" placeholder="Rechercher" value="<?= esc($filters['keyword'] ?? '') ?>">
            </div>
            <div class="date-filters">
                <label>Debut :</label>
                <input type="date" name="date_debut" value="<?= esc($filters['date_debut'] ?? '') ?>">
            </div>
        </div>
        <div class="filters-row">
            <div class="dropdown-filters">
                <select name="resource" class="filter-select">
                    <option value="">▼Ressource</option>
                    <?php foreach ($resources as $code => $nom): ?>
                        <option value="<?= esc($code) ?>" <?= ($filters['resource'] ?? '') === $code ? 'selected' : '' ?>><?= esc($nom) ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="semester" class="filter-select">
                    <option value="">▼Semestre</option>
                    <?php foreach ($semesters as $code => $label): ?>
                        <option value="<?= esc($code) ?>" <?= ($filters['semester'] ?? '') === $code ? 'selected' : '' ?>><?= esc($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="teacher" class="filter-select">
                    <option value="">▼Enseignant</option>
                    <?php foreach ($teachers as $nom => $fullname): ?>
                        <option value="<?= esc($nom) ?>" <?= ($filters['teacher'] ?? '') === $nom ? 'selected' : '' ?>><?= esc($fullname) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="date-filters">
                <label>Fin :</label>
                <input type="date" name="date_fin" value="<?= esc($filters['date_fin'] ?? '') ?>">
            </div>
            <div class="filter-buttons">
                <a href="<?= base_url('DS') ?>" class="btn-filter btn-reset">Retirer les filtres</a>
                <button type="submit" class="btn-filter btn-search">Rechercher</button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>

    <!-- Section Tableau -->
    <div class="table-section">
        <table class="ds-table">
            <thead>
                <tr>
                    <th>Ressource</th>
                    <th>Enseignant</th>
                    <th>Date</th>
                    <th>Durée</th>
                    <th>Type</th>
                    <th>Nombre absences</th>
                    <th>État</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dsList)): ?>
                    <?php foreach ($dsList as $ds): ?>
                        <tr onclick="window.location='<?= base_url('DS/detail/' . $ds['id_ds']) ?>'" style="cursor: pointer;">
                            <td><?= esc($ds['coderessource']) ?></td>
                            <td><?= esc(strtoupper($ds['enseignant_nom']) . ' ' . ucfirst($ds['enseignant_prenom'])) ?></td>
                            <td><?= esc(date('d/m/Y', strtotime($ds['date_ds']))) ?></td>
                            <td><?= esc($ds['duree_formatee']) ?></td>
                            <td><?= esc(ucfirst(strtolower($ds['type_exam']))) ?></td>
                            <td><?= esc($ds['nb_absences']) ?></td>
                            <td>
                                <?php
                                // Traduire l'état pour afficher un texte compréhensible et associer une classe CSS
                                switch ($ds['etat']) {
                                    case 'PREVU':
                                        $etatTexte = 'Rattrapage organisé';
                                        $etatClass = 'etat-prevu';
                                        break;
                                    case 'REFUSE':
                                        $etatTexte = 'Non-rattrapé';
                                        $etatClass = 'etat-refuse';
                                        break;
                                    case 'TERMINE':
                                        $etatTexte = 'Rattrapé';
                                        $etatClass = 'etat-termine';
                                        break;
                                    case 'EN ATTENTE':
                                    default:
                                        $etatTexte = 'En attente';
                                        $etatClass = 'etat-en-attente';
                                        break;
                                }
                                ?>
                                <span class="etat-badge <?= $etatClass ?>">
                                    <?= esc($etatTexte) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="no-data">Aucun DS trouvé</td>
                    </tr>
                <?php endif; ?>

                <!-- Lignes vides pour maintenir la structure -->
                <?php for ($i = count($dsList ?? []); $i < 8; $i++): ?>
                    <tr class="empty-row">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        <?= $pager->links('default', 'default_full') ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Highlight row on hover
document.querySelectorAll('.ds-table tbody tr:not(.empty-row)').forEach(row => {
    row.addEventListener('mouseenter', function() {
        this.style.backgroundColor = '#e8f5e9';
    });
    row.addEventListener('mouseleave', function() {
        this.style.backgroundColor = '';
    });
});
</script>
<?= $this->endSection() ?>
