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
    <?php echo form_open('DS/save'); ?>
    
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
function filterStudents() {
    const searchInput = document.getElementById('student-search').value.toLowerCase();
    const tbody = document.getElementById('students-tbody');
    const rows = tbody.getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();
        
        if (text.includes(searchInput)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

function toggleJustified(checkbox) {
    const studentId = checkbox.id.replace('absent_', '');
    const justifiedCheckbox = document.getElementById('justifie_' + studentId);
    
    if (checkbox.checked) {
        justifiedCheckbox.disabled = false;
    } else {
        justifiedCheckbox.disabled = true;
        justifiedCheckbox.checked = false;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('students-tbody');
    const absentCheckboxes = tbody.querySelectorAll('[id^="absent_"]');
    
    absentCheckboxes.forEach(function(checkbox) {
        const studentId = checkbox.id.replace('absent_', '');
        const justifiedCheckbox = document.getElementById('justifie_' + studentId);
        
        if (checkbox.checked) {
            justifiedCheckbox.disabled = false;
        } else {
            justifiedCheckbox.disabled = true;
        }
    });
});

document.getElementById('semester').addEventListener('change', function() {
    const semester = this.value;
    
    // Mettre à jour les ressources
    fetch('<?= base_url('DS/getResourcesBySemester') ?>?semester=' + encodeURIComponent(semester))
        .then(response => response.json())
        .then(data => {
            const resourceSelect = document.getElementById('resource');
            resourceSelect.innerHTML = '';
            
            if (data.length > 0) {
                data.forEach(resource => {
                    const option = document.createElement('option');
                    option.value = resource.code;
                    option.textContent = resource.nom;
                    resourceSelect.appendChild(option);
                });
                
                resourceSelect.dispatchEvent(new Event('change'));
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Aucune ressource disponible';
                resourceSelect.appendChild(option);
                
                const teacherSelect = document.getElementById('teacher');
                teacherSelect.innerHTML = '<option value="">Aucun professeur disponible</option>';
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des ressources:', error);
        });

    // Mettre à jour les étudiants
    const keyword = document.getElementById('student-search').value;
    fetch('<?= base_url('DS/getStudentsBySemester') ?>?semester=' + encodeURIComponent(semester) + '&keyword=' + encodeURIComponent(keyword))
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('students-tbody');
            tbody.innerHTML = '';
            
            if (data.length > 0) {
                data.forEach(student => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${escapeHtml(student.id)}</td>
                        <td>${escapeHtml(student.nom)}</td>
                        <td>${escapeHtml(student.prenom)}</td>
                        <td>${escapeHtml(student.classe)}</td>
                        <td>
                            <input type="checkbox" name="absent[${escapeHtml(student.id)}]" value="1" id="absent_${escapeHtml(student.id)}" onchange="toggleJustified(this)">
                        </td>
                        <td>
                            <input type="checkbox" name="justifie[${escapeHtml(student.id)}]" value="1" id="justifie_${escapeHtml(student.id)}" disabled>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="6" style="text-align:center;">Aucun étudiant trouvé</td>';
                tbody.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des étudiants:', error);
        });
});

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

document.getElementById('resource').addEventListener('change', function() {
    const resource = this.value;
    
    if (!resource) {
        const teacherSelect = document.getElementById('teacher');
        teacherSelect.innerHTML = '<option value="">Aucun professeur disponible</option>';
        return;
    }
    
    fetch('<?= base_url('DS/getTeachersByResource') ?>?resource=' + encodeURIComponent(resource))
        .then(response => response.json())
        .then(data => {
            const teacherSelect = document.getElementById('teacher');
            teacherSelect.innerHTML = '';
            
            if (data.length > 0) {
                data.forEach(teacher => {
                    const option = document.createElement('option');
                    option.value = teacher.nom;
                    option.textContent = teacher.nom;
                    teacherSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Aucun professeur disponible';
                teacherSelect.appendChild(option);
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des professeurs:', error);
        });
});
</script>
<?= $this->endSection() ?>