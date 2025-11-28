<?= $this->extend('layouts/default') ?>
<?= $this->section('pageType'); ?>ds<?= $this->endSection(); ?>

<?= $this->section('title') ?>
Ajout d'un DS
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/ds-ajout.css') ?>" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('navbarTitle') ?>
MySGRDS | Ajout d'un DS
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="ds-ajout-container">
    <?php echo form_open('ds/sauvegarder'); ?>
    
    <div class="ds-ajout-layout">
        <!-- Colonne gauche: Évaluation -->
        <div class="evaluation-section">
            <h2 class="section-title">Évaluation</h2>
            
            <div class="form-group">
                <label for="semester">Semestre</label>
                <?php echo form_dropdown('semester', $semesters, set_value('semester', $semester[0] ?? ''), 'class="form-select" id="semester"'); ?>
                <?= validation_show_error('semester') ?>
            </div>

            <div class="form-group">
                <label for="resource">Ressource</label>
                <?php echo form_dropdown('resource', $resources, set_value('resource', $resource[0] ?? ''), 'class="form-select" id="resource"'); ?>
                <?= validation_show_error('resource') ?>
            </div>

            <div class="form-group">
                <label for="teacher">Professeur</label>
                <?php echo form_dropdown('teacher', $teachers, set_value('teacher', $teacher[0] ?? ''), 'class="form-select" id="teacher"'); ?>
                <?= validation_show_error('teacher') ?>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <?php echo form_input([
                    'name' => 'date',
                    'id' => 'date',
                    'type' => 'date',
                    'class' => 'form-input',
                    'value' => set_value('date'),
                    'required' => true
                ]); ?>
                <?= validation_show_error('date') ?>
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <?php echo form_dropdown('type', $types, set_value('type', $type[0] ?? ''), 'class="form-select" id="type"'); ?>
                <?= validation_show_error('type') ?>
            </div>

            <div class="form-group">
                <label for="duration">Durée de l'évaluation</label>
                <?php echo form_input([
                    'name' => 'duration',
                    'id' => 'duration',
                    'type' => 'time',
                    'class' => 'form-input',
                    'value' => set_value('duration'),
                    'required' => true,
                    'placeholder' => '01:30'
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
                            <td><?= esc($student['nom']) ?></td>
                            <td><?= esc($student['prenom']) ?></td>
                            <td><?= esc($student['classe']) ?></td>
                            <td>
                                <?php echo form_checkbox(
                                    'absent[' . esc($student['id']) . ']',
                                    '1',
                                    set_checkbox('absent[' . esc($student['id']) . ']', '1', isset($student['absent']) && $student['absent']),
                                    'id="absent_' . esc($student['id']) . '" onchange="toggleJustified(this)"'
                                ); ?>
                            </td>
                            <td>
                                <?php 
                                    $isAbsent = set_checkbox('absent[' . esc($student['id']) . ']', '1', isset($student['absent']) && $student['absent']);
                                    $isDisabled = !$isAbsent ? 'disabled' : '';
                                    echo form_checkbox(
                                        'justifie[' . esc($student['id']) . ']',
                                        '1',
                                        set_checkbox('justifie[' . esc($student['id']) . ']', '1', isset($student['justifie']) && $student['justifie']),
                                        'id="justifie_' . esc($student['id']) . '" ' . $isDisabled
                                    ); 
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>

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

    <!-- Bouton Submit -->
    <div class="submit-container">
        <?php echo form_submit('submit', '+ Ajouter le DS', 'class="btn-submit"'); ?>
    </div>
    
    <?php echo form_close(); ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.body.setAttribute('data-base-url', '<?= base_url() ?>');
</script>
<script src="<?= base_url('js/ds-ajout-common.js') ?>"></script>
<script src="<?= base_url('js/ds-ajout-dynamic.js') ?>"></script>
<?= $this->endSection() ?>
