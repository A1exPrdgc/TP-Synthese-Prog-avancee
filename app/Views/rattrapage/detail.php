<?= $this->extend('layouts/default') ?>
<?= $this->section('pageType'); ?>rattrapage<?= $this->endSection(); ?>

<?= $this->section('title') ?>
Détail Rattrapage
<?= $this->endSection() ?>

<?= $this->section('navbarTitle') ?>
MySGRDS | Visionner Rattrapage
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
                <div class="info-value"><?= esc($rattrapage['semestre_code']) ?></div>
            </div>

            <div class="info-group">
                <label>Ressource</label>
                <div class="info-value info-value-long"><?= esc($rattrapage['coderessource']) ?> <?= esc($rattrapage['nomressource']) ?></div>
            </div>

            <div class="info-group">
                <label>Professeur</label>
                <div class="info-value"><?= esc($rattrapage['enseignant_complet']) ?></div>
            </div>

            <div class="info-group-row">
                <div class="info-group half">
                    <label>Date</label>
                    <div class="info-value"><?= esc(date('d/m/y', strtotime($rattrapage['date_rattrapage']))) ?></div>
                </div>

                <div class="info-group half">
                    <label>Heure</label>
                    <div class="info-value"><?= esc(substr($rattrapage['heure_debut'], 0, 5)) ?></div>
                </div>
            </div>

            <div class="info-group-row">
                <div class="info-group half">
                    <label>Type</label>
                    <div class="info-value"><?= esc(ucfirst(strtolower($rattrapage['type_exam']))) ?></div>
                </div>

                <div class="info-group half">
                    <label>Salle</label>
                    <div class="info-value"><?= esc($rattrapage['salle']) ?></div>
                </div>
            </div>

            <div class="info-group">
                <label>Durée de l'évaluation</label>
                <div class="info-value"><?= esc($rattrapage['duree_formatee']) ?></div>
            </div>

            <div class="info-group">
                <label>État</label>
                <div class="info-value etat-badge <?= $rattrapage['etat'] === 'EN ATTENTE' ? 'etat-rattraper' : ($rattrapage['etat'] === 'REFUSE' ? 'etat-refuse' : 'etat-termine') ?>">
                    <?= esc(strtolower($rattrapage['etat'])) ?>
                </div>
            </div>
        </div>

        <!-- Colonne droite: Étudiants -->
        <div class="students-section">
            <h2 class="section-title">Étudiants</h2>
            
            <div class="search-input-group">
                <?php echo form_open('Rattrapage/detail/' . $rattrapage['id_rattrapage'], ['method' => 'get']); ?>
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
                            <th>Absence</th>
                            <th>Rattrapage</th>
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
                                    <td><?= $student['justifie'] ? 'Justifié' : 'Non justifié' ?></td>
                                    <td>
                                        <input type="checkbox" checked disabled>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <!-- Lignes vides pour maintenir la structure -->
                        <?php for ($i = count($students ?? []); $i < 6; $i++): ?>
                            <tr class="empty-row">
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

                <div class="pagination-container">
                    <?= $pager->links('default', 'default_full') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton d'action -->
    <div class="action-buttons">
        <a href="<?= base_url('Rattrapage/modifier/' . $rattrapage['id_rattrapage']) ?>" class="btn-action btn-validate">
            <span class="btn-icon">✎</span> Modifier le rattrapage
        </a>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= $this->endSection() ?>

