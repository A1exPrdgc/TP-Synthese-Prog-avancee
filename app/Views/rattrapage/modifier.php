<?= $this->extend('layouts/default') ?>
<?= $this->section('pageType'); ?>rattrapage<?= $this->endSection(); ?>

<?= $this->section('title') ?>
Modifier Rattrapage
<?= $this->endSection() ?>

<?= $this->section('backUrl') ?><?= base_url('rattrapage/detail/' . $rattrapage['id_rattrapage']) ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/ds-ajout.css') ?>" rel="stylesheet" />
<link href="<?= base_url('assets/css/ds-detail.css') ?>" rel="stylesheet" />
<style>
.form-group-row {
    display: flex;
    gap: 15px;
}
.form-group.half {
    flex: 1;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('navbarTitle') ?>
MySGRDS | Modifier Rattrapage
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="ds-ajout-container">
    <?php echo form_open('rattrapage/modifier/' . $rattrapage['id_rattrapage']); ?>
    
    <div class="ds-ajout-layout">
        <!-- Colonne gauche: Évaluation -->
        <div class="evaluation-section">
            <h2 class="section-title">Modifier le rattrapage</h2>
            
            <div class="form-group">
                <label>Semestre</label>
                <div class="info-value"><?= esc($rattrapage['semestre_code']) ?></div>
            </div>

            <div class="form-group">
                <label>Ressource</label>
                <div class="info-value info-value-long"><?= esc($rattrapage['coderessource']) ?> <?= esc($rattrapage['nomressource']) ?></div>
            </div>

            <div class="form-group">
                <label>Professeur</label>
                <div class="info-value"><?= esc($rattrapage['enseignant_complet']) ?></div>
            </div>

            <div class="form-group">
                <label>Date du DS initial</label>
                <div class="info-value"><?= esc(date('d/m/Y', strtotime($rattrapage['date_ds']))) ?></div>
            </div>

            <div class="form-group-row">
                <div class="form-group half">
                    <label for="date">Date</label>
                    <?php echo form_input([
                        'name' => 'date',
                        'id' => 'date',
                        'type' => 'date',
                        'class' => 'form-input',
                        'value' => set_value('date', $rattrapage['date_rattrapage']),
                        'required' => true
                    ]); ?>
                    <?= validation_show_error('date') ?>
                </div>

                <div class="form-group half">
                    <label for="hour">Heure</label>
                    <?php echo form_input([
                        'name' => 'hour',
                        'id' => 'hour',
                        'type' => 'time',
                        'class' => 'form-input',
                        'value' => set_value('hour', substr($rattrapage['heure_debut'], 0, 5)),
                        'required' => true
                    ]); ?>
                    <?= validation_show_error('hour') ?>
                </div>
            </div>

            <div class="form-group-row">
                <div class="form-group half">
                    <label for="type">Type</label>
                    <?php echo form_dropdown('type', $types, set_value('type', $rattrapage['type_exam']), 'class="form-select" id="type"'); ?>
                    <?= validation_show_error('type') ?>
                </div>

                <div class="form-group half">
                    <label for="room">Salle</label>
                    <?php echo form_input([
                        'name' => 'room',
                        'id' => 'room',
                        'type' => 'text',
                        'class' => 'form-input',
                        'value' => set_value('room', $rattrapage['salle']),
                        'required' => true,
                        'maxlength' => 3
                    ]); ?>
                    <?= validation_show_error('room') ?>
                </div>
            </div>

            <div class="form-group">
                <label for="duration">Durée de l'évaluation</label>
                <?php 
                    // Convertir les minutes en format HH:MM
                    $heures = floor($rattrapage['duree_minutes'] / 60);
                    $mins = $rattrapage['duree_minutes'] % 60;
                    $dureeFomatee = sprintf('%02d:%02d', $heures, $mins);
                ?>
                <?php echo form_input([
                    'name' => 'duration',
                    'id' => 'duration',
                    'type' => 'time',
                    'class' => 'form-input',
                    'value' => set_value('duration', $dureeFomatee),
                    'required' => true
                ]); ?>
                <?= validation_show_error('duration') ?>
            </div>
        </div>

        <!-- Colonne droite: Étudiants -->
        <div class="students-section">
            <h2 class="section-title">Étudiants absents</h2>
            
            <div class="search-input-group">
                <input 
                    type="text" 
                    placeholder="Rechercher un étudiant..." 
                    id="student-search"
                    value="<?= esc($keyword) ?>"
                    onkeyup="filterStudents()"
                >
            </div>

            <div class="students-table-container">
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>Identifiant</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Classe</th>
                            <th>Justifié</th>
                            <th>Rattrapage</th>
                        </tr>
                    </thead>
                    <tbody id="students-tbody">
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= esc($student['id']) ?></td>
                                <td><?= esc(strtoupper($student['nom'])) ?></td>
                                <td><?= esc(ucfirst($student['prenom'])) ?></td>
                                <td><?= esc($student['classe']) ?></td>
                                <td><?= $student['justifie'] ? 'Oui' : 'Non' ?></td>
                                <td>
                                    <?php echo form_checkbox(
                                        'rattrape[' . esc($student['id']) . ']',
                                        '1',
                                        set_checkbox('rattrape[' . esc($student['id']) . ']', '1', $student['rattrape']),
                                        'id="rattrape_' . esc($student['id']) . '"'
                                    ); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="empty-row">
                                <td colspan="6" style="text-align: center;">Aucun étudiant absent</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="pagination-container">
                    <?= $pager->links('default', 'default_full') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Boutons -->
    <div class="action-buttons" style="justify-content: space-between;">
        <div style="display: flex; gap: 10px;">
            <a href="<?= base_url('rattrapage/detail/' . $rattrapage['id_rattrapage']) ?>" class="btn-action btn-refuse" style="background-color: #6c757d; box-shadow: -4px -4px 0 #495057;">
                <span class="btn-icon">←</span> Annuler
            </a>
            <a href="<?= base_url('rattrapage/refuser/' . $rattrapage['id_rattrapage']) ?>" 
               class="btn-action btn-refuse" 
               style="background-color: #dc3545; box-shadow: -4px -4px 0 #c82333;"
               onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rattrapage ?');">
                <span class="btn-icon">x</span> Annuler le rattrapage
            </a>
        </div>
        <?php echo form_submit('submit', '✓ Enregistrer les modifications', 'class="btn-action btn-validate"'); ?>
    </div>
    
    <?php echo form_close(); ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/ds-detail.js') ?>"></script>
<?= $this->endSection() ?>
