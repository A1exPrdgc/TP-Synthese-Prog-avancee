<?= $this->extend('layouts/default') ?>
<?= $this->section('pageType'); ?>ds<?= $this->endSection(); ?>

<?= $this->section('title') ?>
Ajout d'un Rattrapage
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Style spécifique si besoin -->
<?= $this->endSection() ?>

<?= $this->section('navbarTitle') ?>
MySGRDS | Ajout d'un Rattrapage
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<body>

    <body>
        <?php echo form_open('Rattrapage/save'); ?>

        <h1>Evaluation</h1>

        <div class="form">
            <?php echo form_label('Semestre', 'semester'); ?>
            <?php echo form_input('semester', set_value('semester', $DSInformation['semester']), 'required readonly'); ?>
            <?= validation_show_error('semester') ?>
            <br>
            <?php echo form_label('Ressource', 'resource'); ?>
            <?php echo form_input('resource', set_value('resource', $DSInformation['resource']), 'required readonly'); ?>
            <?= validation_show_error('resource') ?>
            <br>
            <?php echo form_label('Professeur', 'teacher'); ?>
            <?php echo form_input('teacher', set_value('teacher', $DSInformation['teacher']), 'required readonly'); ?>
            <?= validation_show_error('teacher') ?>
            <br>
            <?php echo form_label('Date', 'date'); ?>
            <?php echo form_input('date', set_value('date'), 'required placeholder="01/01/26"'); ?>
            <?= validation_show_error('date') ?>
            <br>
            <?php echo form_label('Heure', 'hour'); ?>
            <?php echo form_input('hour', set_value('hour'), 'required placeholder="13:30"'); ?>
            <?= validation_show_error('hour') ?>
            <br>
            <?php echo form_label('Type', 'type'); ?>
            <?php echo form_dropdown('type', $types, $DSInformation['type']); ?>
            <?= validation_show_error('type') ?>
            <br>
            <?php echo form_label('Salle', 'room'); ?>
            <?php echo form_input('room', set_value('room'), 'required placeholder="707"'); ?>
            <?= validation_show_error('room') ?>
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
                        <th>Absence</th>
                        <th>Rattrapage</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    for ($i = 0; $i < count($students); $i++) {
                        echo "<tr>";
                        echo "<td>" . esc($students[$i]['id']) . "</td>";
                        echo "<td>" . esc($students[$i]['nom']) . "</td>";
                        echo "<td>" . esc($students[$i]['prenom']) . "</td>";
                        echo "<td>" . esc($students[$i]['classe']) . "</td>";
                        echo "<td>" . ($students[$i]['absent'] ? 'Justifié' : 'Non Justifié') . "</td>";
                        echo "<td>" . form_checkbox('justify', 'justify', false) . "</td>";
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
                <input type="text" class="form-control" placeholder="Rechercher" name="keyword" id="keyword"
                    value="<?= esc($keyword) ?>">
                <button type="submit" class="btn btn-custom">Rechercher</button>
            </form>
        </div>

    </body>
</body>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Scripts JS éventuels -->
<?= $this->endSection() ?>