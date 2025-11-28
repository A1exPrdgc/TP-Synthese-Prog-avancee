<?= $this->extend('layouts/default') ?>
<?= $this->section('pageType'); ?>ds<?= $this->endSection(); ?>

<?= $this->section('title') ?>
Modifier DS
<?= $this->endSection() ?>

<?= $this->section('backUrl') ?><?= base_url('ds/detail/' . $ds['id_ds']) ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/ds-ajout.css') ?>" rel="stylesheet" />
<link href="<?= base_url('assets/css/ds-detail.css') ?>" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('navbarTitle') ?>
MySGRDS | Modifier DS
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="ds-ajout-container">
    <?php echo form_open('ds/modifier/' . $ds['id_ds']); ?>

    <div class="ds-ajout-layout">
        <!-- Colonne gauche: Évaluation -->
        <div class="evaluation-section">
            <h2 class="section-title">Modifier le DS</h2>

            <div class="form-group">
                <label>Semestre</label>
                <div class="info-value"><?= esc($ds['semestre_code']) ?></div>
            </div>

            <div class="form-group">
                <label>Ressource</label>
                <div class="info-value info-value-long"><?= esc($ds['coderessource']) ?> <?= esc($ds['nomressource']) ?></div>
            </div>

            <div class="form-group">
                <label>Professeur</label>
                <div class="info-value"><?= esc($ds['enseignant_complet']) ?></div>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <?php echo form_input([
                    'name' => 'date',
                    'id' => 'date',
                    'type' => 'date',
                    'class' => 'form-input',
                    'value' => set_value('date', $ds['date_ds']),
                    'required' => true
                ]); ?>
                <?= validation_show_error('date') ?>
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <?php echo form_dropdown('type', $types, set_value('type', $ds['type_exam']), 'class="form-select" id="type"'); ?>
                <?= validation_show_error('type') ?>
            </div>

            <div class="form-group">
                <label for="duration">Durée de l'évaluation</label>
                <?php 
                    // Convertir les minutes en format HH:MM
                    $heures = floor($ds['duree_minutes'] / 60);
                    $mins = $ds['duree_minutes'] % 60;
                    $dureeFormatee = sprintf('%02d:%02d', $heures, $mins);
                ?>
                <?php echo form_input([
                    'name' => 'duration',
                    'id' => 'duration',
                    'type' => 'time',
                    'class' => 'form-input',
                    'value' => set_value('duration', $dureeFormatee),
                    'required' => true
                ]); ?>
                <?= validation_show_error('duration') ?>
            </div>
        </div>

        <!-- Colonne droite: Étudiants -->
        <div class="students-section">
            <h2 class="section-title">Étudiants</h2>

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
                            <th>Absent</th>
                            <th>Justifié</th>
                        </tr>
                    </thead>
                    <tbody id="students-tbody">
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= esc($student['id']) ?></td>
                            <td><?= esc(strtoupper($student['nom'])) ?></td>
                            <td><?= esc(ucfirst($student['prenom'])) ?></td>
                            <td><?= esc($student['classe']) ?></td>
                            <td>
                                <?php echo form_checkbox(
                                    'absent[' . esc($student['id']) . ']',
                                    '1',
                                    set_checkbox('absent[' . esc($student['id']) . ']', '1', $student['absent']),
                                    'id="absent_' . esc($student['id']) . '" onchange="toggleJustified(this)"'
                                ); ?>
                            </td>
                            <td>
                                <?php 
                                    $isAbsent = $student['absent'];
                                    $isDisabled = !$isAbsent ? 'disabled' : '';
                                    echo form_checkbox(
                                        'justifie[' . esc($student['id']) . ']',
                                        '1',
                                        set_checkbox('justifie[' . esc($student['id']) . ']', '1', $student['justifie']),
                                        'id="justifie_' . esc($student['id']) . '" ' . $isDisabled
                                    ); 
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
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
        <a href="<?= base_url('ds/detail/' . $ds['id_ds']) ?>" class="btn-action btn-refuse" style="background-color: #6c757d; box-shadow: -4px -4px 0 #495057;">
            <span class="btn-icon">←</span> Annuler
        </a>
        <?php echo form_submit('submit', '✓ Enregistrer les modifications', 'class="btn-action btn-validate"'); ?>
    </div>

    <?php echo form_close(); ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.body.setAttribute('data-base-url', '<?= base_url() ?>');
</script>
<script src="<?= base_url('js/ds-ajout-common.js') ?>"></script>
<?= $this->endSection() ?>