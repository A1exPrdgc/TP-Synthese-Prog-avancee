<?= $this->extend('layouts/default') ?>
<?= $this->section('pageType'); ?>rattrapage<?= $this->endSection(); ?>

<?= $this->section('title') ?>
Ajouter Rattrapage
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/ds-ajout.css') ?>" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('navbarTitle') ?>
MySGRDS | Ajouter Rattrapage
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="ds-ajout-container">
    <?php echo form_open('Rattrapage/save/' . $DSInformation['idDs']); ?>
    <?php echo form_hidden('codeEnseignant', $DSInformation['codeEnseignant']); ?>
    
    <div class="ds-ajout-layout">
        <!-- Colonne gauche: Évaluation -->
        <div class="evaluation-section">
            <h2 class="section-title">Évaluation</h2>
            
            <div class="form-group">
                <label>Semestre</label>
                <div class="info-value"><?= esc($DSInformation['semester']) ?></div>
                <?php echo form_hidden('semester', $DSInformation['semester']); ?>
            </div>

            <div class="form-group">
                <label>Ressource</label>
                <div class="info-value info-value-long"><?= esc($DSInformation['resource']) ?></div>
                <?php echo form_hidden('resource', $DSInformation['resource']); ?>
            </div>

            <div class="form-group">
                <label>Professeur</label>
                <div class="info-value"><?= esc($DSInformation['teacher']) ?></div>
                <?php echo form_hidden('teacher', $DSInformation['teacher']); ?>
            </div>

            <div class="form-group-row">
                <div class="form-group half">
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

                <div class="form-group half">
                    <label for="hour">Heure</label>
                    <?php echo form_input([
                        'name' => 'hour',
                        'id' => 'hour',
                        'type' => 'time',
                        'class' => 'form-input',
                        'value' => set_value('hour'),
                        'required' => true,
                        'placeholder' => '13:45'
                    ]); ?>
                    <?= validation_show_error('hour') ?>
                </div>
            </div>

            <div class="form-group-row">
                <div class="form-group half">
                    <label for="type">Type</label>
                    <?php echo form_dropdown('type', $types, set_value('type', $DSInformation['type']), 'class="form-select" id="type"'); ?>
                    <?= validation_show_error('type') ?>
                </div>

                <div class="form-group half">
                    <label for="room">Salle</label>
                    <?php echo form_input([
                        'name' => 'room',
                        'id' => 'room',
                        'type' => 'text',
                        'class' => 'form-input',
                        'value' => set_value('room'),
                        'required' => true,
                        'placeholder' => '707',
                        'maxlength' => 3
                    ]); ?>
                    <?= validation_show_error('room') ?>
                </div>
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
                    value="<?= esc($keyword ?? '') ?>"
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
                            <th>Absence</th>
                            <th>Rattrapage</th>
                        </tr>
                    </thead>
                    <tbody id="students-tbody">
                        <?php 
                        $oldJustify = $old_justify ?? [];
                        foreach ($students as $student): 
                            $sid = esc($student['id']);
                            $checked = isset($oldJustify[$sid]) && $oldJustify[$sid] === 'on';
                        ?>
                        <tr>
                            <td><?= esc($student['id']) ?></td>
                            <td><?= esc(strtoupper($student['nom'])) ?></td>
                            <td><?= esc(ucfirst($student['prenom'])) ?></td>
                            <td><?= esc($student['classe']) ?></td>
                            <td><?= $student['justifie'] ? 'Justifié' : 'Non justifié' ?></td>
                            <td>
                                <?php echo form_hidden("justify[{$sid}]", 'off'); ?>
                                <?php echo form_checkbox("justify[{$sid}]", 'on', $checked); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php for ($i = count($students); $i < 6; $i++): ?>
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
                    <?php if (isset($pager)): ?>
                        <?= $pager->links('default', 'default_full') ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton Submit -->
    <div class="submit-container">
        <?php echo form_submit('submit', '+ Ajouter le Rattrapage', 'class="btn-submit"'); ?>
    </div>
    
    <?php echo form_close(); ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/rattrapage-ajout.js') ?>"></script>
<?= $this->endSection() ?>
