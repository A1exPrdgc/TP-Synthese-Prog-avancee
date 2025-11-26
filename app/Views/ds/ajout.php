<?= $this->extend('layouts/default') ?>
<?= $this->section('pageType'); ?>ds<?= $this->endSection(); ?>

<?= $this->section('title') ?>
Ajout d'un DS
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Style spécifique si besoin -->
<?= $this->endSection() ?>

<?= $this->section('navbarTitle') ?>
MySGRDS | Ajout d'un DS
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
                <?php echo form_dropdown('type', ["Machine" => "Machine", "Papier" => "Papier"], "Machine"); ?>
                <?= validation_show_error('type') ?>
                <br>
                <?php echo form_label('Durée', 'duration'); ?>
                <?php echo form_input('duration', set_value('duration'), 'required placeholder="01:30"'); ?>
                <?= validation_show_error('duration') ?>
                <br>
            </div>

            <h1>Etudiant</h1>

            <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
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

                <?php
                    for ($i=0; $i < count($students); $i++) 
                    { 
                        echo "<tr>";
                        echo "<td>" . esc($students[$i]['id']) . "</td>";
                        echo "<td>" . esc($students[$i]['nom']) . "</td>";
                        echo "<td>" . esc($students[$i]['prenom']) . "</td>";
                        echo "<td>" . esc($students[$i]['classe']) . "</td>";
                        echo "<td>" . form_checkbox('absent', 'absent', $students[$i]['absent']) . "</td>";
                        echo "<td>" . form_checkbox('justifie', 'justifie', $students[$i]['justifie']) . "</td>";
                        echo "</tr>";
                    }
                ?>
                </tbody>
            </table>
            </div>
            <nav aria-label="Pagination">
            <ul class="pagination justify-content-center">
                <li class="page-item"><a class="page-link btn-custom" href="#">«</a></li>
                <li class="page-item active"><span class="page-link">1</span></li>
                <li class="page-item"><a class="page-link btn-custom" href="#">2</a></li>
                <li class="page-item"><a class="page-link btn-custom" href="#">3</a></li>
                <li class="page-item"><a class="page-link btn-custom" href="#">»</a></li>
            </ul>
            </nav>

            <div class="send-button">
                <?php echo form_submit('submit', 'Envoyer'); ?>
                <?php echo form_close(); ?>
            </div>

            <div class="mb-4">
                <form class="form">
                    <input type="text" class="form-control" placeholder="Rechercher" name="keyword" id="keyword" value="<?= esc($keyword) ?>">
                    <button type="submit" class="btn btn-custom">Rechercher</button>
                </form>
            </div>

        </body>
    </body>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Scripts JS éventuels -->
<?= $this->endSection() ?>