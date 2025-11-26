<?= $this->extend('layouts/default') ?>
<?= $this->section('pageType'); ?>ds<?= $this->endSection(); ?>

<?= $this->section('title') ?>
Ajouter DS
<?= $this->endSection() ?>

<?= $this->section('navbarTitle') ?>
MySGRDS | Ajouter DS
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/ds-ajout.css') ?>" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <body>

        <body>
            <?php echo form_open('DS/Ajout/save'); ?>

            <h1>Evaluation</h1>

            <div class="form">
                <?php echo form_label('Semestre', 'semester'); ?>
                <?php echo form_dropdown('semester', ["S1" => "S1", "S2" => "S2"], "S1"); ?>
                <?= validation_show_error('semester') ?>
                <br>
                <?php echo form_label('Ressource', 'resource'); ?>
                <?php echo form_dropdown('resource', ["R1.05 blabla" => "R1.05 blabla", "R1.02 blibli" => "R1.02 blibli"], "R1.05 blabla"); ?>
                <?= validation_show_error('resource') ?>
                <br>
                <?php echo form_label('Professeur', 'teacher'); ?>
                <?php echo form_dropdown('teacher', ["Legrix" => "Legrix", "Thorel" => "Thorel"], "Legrix"); ?>
                <?= validation_show_error('teacher') ?>
                <br>
                <?php echo form_label('Date', 'date'); ?>
                <?php echo form_input('date', set_value('date'), 'required placeholder="01/01/26"'); ?>
                <?= validation_show_error('date') ?>
                <br>
                <?php echo form_label('Type', 'type'); ?>
                <?php echo form_dropdown('type', ["Machine" => "MACHINE", "Papier" => "PAPIER", "Oral" => "ORAL"], "Machine"); ?>
                <?= validation_show_error('type') ?>
                <br>
                <?php echo form_label('Durée', 'duration'); ?>
                <?php echo form_input('duration', set_value('duration'), 'required placeholder="01:30"'); ?>
                <?= validation_show_error('duration') ?>
                <br>
            </div>

            <div class="form-group">
                <label>Ressource</label>
                <select class="form-select">
                    <option>R5.01 Initiation au management d'une équipe de projet informatique</option>
                </select>
            </div>

            <div class="form-group">
                <label>Professeur</label>
                <select class="form-select">
                    <option>Isuvi Myra Ondiso</option>
                </select>
            </div>

            <div class="form-group">
                <label>Date</label>
                <input type="text" class="form-input" placeholder="01/01/25">
            </div>

            <div class="form-group">
                <label>Type</label>
                <select class="form-select">
                    <option>Machine</option>
                    <option>Papier</option>
                </select>
            </div>

            <div class="form-group">
                <label>Durée de l'évaluation</label>
                <input type="text" class="form-input" placeholder="01:30">
            </div>
        </div>

        <!-- Colonne droite: Étudiants -->
        <div class="students-section">
            <h2 class="section-title">Étudiants</h2>
            
            <div class="search-input-group">
                <input type="text" placeholder="Rechercher">
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
                        <tr>
                            <td>gc230811</td>
                            <td>GUELLE</td>
                            <td>Clément</td>
                            <td>L2</td>
                            <td><input type="checkbox"></td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <tr>
                            <td>vr232906</td>
                            <td>VIEZ</td>
                            <td>Rémi</td>
                            <td>L2</td>
                            <td><input type="checkbox"></td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <tr>
                            <td>pa230262</td>
                            <td>PRADIGNAC</td>
                            <td>Alexandre</td>
                            <td>L2</td>
                            <td><input type="checkbox"></td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <!-- Lignes vides pour l'exemple -->
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                <div class="pagination-container">
                    <a href="#" class="pagination-btn"><<</a>
                    <a href="#" class="pagination-btn"><</a>
                    <span style="font-weight:bold; font-size:1.2rem; margin:0 5px;">1</span>
                    <span style="font-weight:bold; font-size:1.2rem; margin:0 5px;">2</span>
                    <span style="font-weight:bold; font-size:1.2rem; margin:0 5px;">3</span>
                    <span style="font-weight:bold; font-size:1.2rem; margin:0 5px;">4</span>
                    <span style="font-weight:bold; font-size:1.2rem; margin:0 5px;">5</span>
                    <a href="#" class="pagination-btn">></a>
                    <a href="#" class="pagination-btn">>></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton Ajouter le DS -->
    <div class="submit-container">
        <button type="submit" class="btn-submit">+ Ajouter le DS</button>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Scripts JS éventuels -->
<?= $this->endSection() ?>
