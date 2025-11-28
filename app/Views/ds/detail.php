<?= $this->extend('layouts/default') ?>
<?= $this->section('pageType'); ?>ds<?= $this->endSection(); ?>

<?= $this->section('title') ?>
Visionner DS
<?= $this->endSection() ?>

<?= $this->section('navbarTitle') ?>
MySGRDS | Visionner DS
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/ds-detail.css') ?>" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="ds-detail-container">
    <div class="ds-detail-layout">
        <!-- Colonne gauche: Évaluation -->
        <div class="evaluation-section">
            <h2 class="section-title">Évaluation</h2>
            
            <div class="info-group">
                <label>Semestre</label>
                <div class="info-value"><?= esc($ds['semestre_code']) ?></div>
            </div>

            <div class="info-group">
                <label>Ressource</label>
                <div class="info-value info-value-long"><?= esc($ds['coderessource']) ?> <?= esc($ds['nomressource']) ?></div>
            </div>

            <div class="info-group">
                <label>Professeur</label>
                <div class="info-value"><?= esc($ds['enseignant_complet']) ?></div>
            </div>

            <div class="info-group">
                <label>Date</label>
                <div class="info-value"><?= esc(date('d/m/y', strtotime($ds['date_ds']))) ?></div>
            </div>

            <div class="info-group">
                <label>Type</label>
                <div class="info-value"><?= esc(ucfirst(strtolower($ds['type_exam']))) ?></div>
            </div>

            <div class="info-group">
                <label>Durée de l'évaluation</label>
                <div class="info-value"><?= esc($ds['duree_formatee']) ?></div>
            </div>
        </div>

        <!-- Colonne droite: Étudiants -->
        <div class="students-section">
            <h2 class="section-title">Etudiants</h2>
            
            <div class="search-input-group">
                <?php echo form_open('DS/detail/' . $ds['id_ds'], ['method' => 'get']); ?>
                <input type="text" name="keyword" placeholder="Rechercher" value="<?= esc($keyword) ?>" onchange="this.form.submit()">
                <?php echo form_close(); ?>
            </div>

            <div class="students-table-container">
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>Identifiant</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Classe</th>
                            <th>Absent</th>
                            <th>Justifié</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?= esc($student['id']) ?></td>
                                    <td><?= esc(strtoupper($student['nom'])) ?></td>
                                    <td><?= esc(ucfirst($student['prenom'])) ?></td>
                                    <td><?= esc($student['classe']) ?></td>
                                    <td>
                                        <input type="checkbox" <?= $student['absent'] ? 'checked' : '' ?> disabled>
                                    </td>
                                    <td>
                                        <input type="checkbox" <?= $student['justifie'] ? 'checked' : '' ?> disabled>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <?php for ($i = count($students ?? []); $i < 10; $i++): ?>
                            <tr class="empty-row">
                                <?php for ($j = 0; $j < count($students[0]); $j++): ?>
                                    <td></td>
                                <?php endfor; ?>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>

                <div class="pagination-container">
                    <?= $pager->links('default', 'default_full') ?>
                </div>
            </div>
        </div>
    </div>

    <?php $role = session()->get('fonction');?>
    <!-- Boutons d'action -->
    <?php if ($role === 'ENS'): ?>
        <div class="action-buttons">
            <a href="<?= base_url('DS/refuserRattrapage/' . $ds['id_ds']) ?>" class="btn-action btn-refuse">
                <span class="btn-icon">✕</span> Refuser le rattrapage
            </a>
            <a href="<?= base_url('Rattrapage/Ajout/' . $ds['id_ds']) ?>" class="btn-action btn-validate">
                <span class="btn-icon">✓</span> Valider le rattrapage
            </a>
        </div>
    <?php endif; ?>

    <?php if ($role === 'DE'): ?>
        <div class="action-buttons">
            <a href="<?= base_url('DS/Modifier/' . $ds['id_ds']) ?>" class="btn-action btn-validate">
                <span class="btn-icon">✓</span> Modifier le DS
            </a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/ds-detail.js') ?>"></script>
<?= $this->endSection() ?>
