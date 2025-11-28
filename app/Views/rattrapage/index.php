<?= $this->extend('layouts/default') ?>
<?= $this->section('pageType'); ?>rattrapage<?= $this->endSection(); ?>

<?= $this->section('title') ?>
Liste des Rattrapages
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/ds-index.css') ?>" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('navbarTitle') ?>
MySGRDS | Rattrapage
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="ds-index-container">
    <!-- Section Filtres -->
    <div class="filters-section">
        <?php echo form_open('rattrapage', ['method' => 'get', 'class' => 'filters-form']); ?>
        
        <div class="filters-row">
            <div class="search-box">
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
                    <option value="">Ressource</option>
                    <?php foreach ($resources as $code => $nom): ?>
                        <option value="<?= esc($code) ?>" <?= ($filters['resource'] ?? '') === $code ? 'selected' : '' ?>><?= esc($nom) ?></option>
                    <?php endforeach; ?>
                </select>
                
                <select name="semester" class="filter-select">
                    <option value="">Semestre</option>
                    <?php foreach ($semesters as $code => $label): ?>
                        <option value="<?= esc($code) ?>" <?= ($filters['semester'] ?? '') === $code ? 'selected' : '' ?>><?= esc($label) ?></option>
                    <?php endforeach; ?>
                </select>
                
                <select name="teacher" class="filter-select">
                    <option value="">Enseignant</option>
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
                <a href="<?= base_url('rattrapage') ?>" class="btn-filter btn-reset">Retirer les filtres</a>
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
                    <th>Date DS</th>
                    <th>Date rattrapage</th>
                    <th>Horaire</th>
                    <th>Durée</th>
                    <th>Salle</th>
                    <th>Type</th>
                    <th>Etat</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rattrapagesList)): ?>
                    <?php foreach ($rattrapagesList as $rattrapage): ?>
                        <tr onclick="window.location='<?= base_url('rattrapage/detail/' . $rattrapage['id_rattrapage']) ?>'" style="cursor: pointer;">
                            <td><?= esc($rattrapage['coderessource']) ?></td>
                            <td><?= esc(strtoupper($rattrapage['enseignant_nom']) . ' ' . ucfirst($rattrapage['enseignant_prenom'])) ?></td>
                            <td><?= esc(date('d/m/Y', strtotime($rattrapage['date_ds']))) ?></td>
                            <td><?= esc(date('d/m/Y', strtotime($rattrapage['date_rattrapage']))) ?></td>
                            <td><?= esc(substr($rattrapage['heure_debut'], 0, 5)) ?></td>
                            <td><?= esc($rattrapage['duree_formatee']) ?></td>
                            <td><?= esc($rattrapage['salle']) ?></td>
                            <td><?= esc(ucfirst(strtolower($rattrapage['type_exam']))) ?></td>
                            <td>
                                <?php
                                switch ($rattrapage['etat']) {
                                    case 'PREVU':
                                        $etatTexte = 'Prévu';
                                        $etatClass = 'etat-prevu';
                                        break;
                                    case 'REFUSE':
                                        $etatTexte = 'Annulé';
                                        $etatClass = 'etat-refuse';
                                        break;
                                    case 'TERMINE':
                                        $etatTexte = 'Terminé';
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

                    <?php for ($i = count($rattrapagesList ?? []); $i < 10; $i++): ?>
                        <tr class="empty-row">
                            <?php for ($j = 0; $j < 8; $j++): ?>
                                <td></td>
                            <?php endfor; ?>
                        </tr>
                    <?php endfor; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="no-data">Aucun rattrapage trouvé</td>
                    </tr>
                <?php endif; ?>
                

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
<script src="<?= base_url('assets/js/rattrapage-index.js') ?>"></script>
<?= $this->endSection() ?>

