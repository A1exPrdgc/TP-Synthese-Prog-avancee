<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'un DS</title>
</head>

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
            <?php echo form_input('date', set_value('date'), 'required'); ?>
            <?= validation_show_error('date') ?>
            <br>
            <?php echo form_label('Type', 'type'); ?>
            <?php echo form_dropdown('type', ["Machine" => "Machine", "Papier" => "Papier"], "Machine"); ?>
            <?= validation_show_error('type') ?>
            <br>
            <?php echo form_label('Durée', 'duration'); ?>
            <?php echo form_input('duration', set_value('duration'), 'required'); ?>
            <?= validation_show_error('duration') ?>
            <br>
            <div class="send-button">
                <?php echo form_submit('submit', 'Envoyer'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>

        <h1>Evaluation</h1>

        

    </body>
</body>

</html>