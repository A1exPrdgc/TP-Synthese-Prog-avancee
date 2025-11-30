<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>Gestion des ressources associées<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/gestion-ressources.css') ?>" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h2>Gérer vos ressources associées</h2>

<?php if(session()->getFlashdata('message')): ?>
<div class="alert alert-success">
    <?= session()->getFlashdata('message') ?>
</div>
<?php endif; ?>

<div class="form-group">
    <label for="semester-select">Sélectionnez un semestre</label>
    <select id="semester-select" class="form-select">
        <option value="S1">S1</option>
        <option value="S2">S2</option>
        <option value="S3">S3</option>
        <option value="S4">S4</option>
        <option value="S5">S5</option>
        <option value="S6">S6</option>
    </select>
</div>

<form method="post" action="<?= site_url('profil/update-ressources') ?>" id="resources-form">
    <ul id="resources-list">
        <!-- Ressources chargées dynamiquement par JS -->
    </ul>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
</form>

<script>
    const teacherResources = <?= json_encode($teacherResources) ?>;
</script>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.body.setAttribute('data-base-url', '<?= base_url() ?>');
</script>
<script src="<?= base_url('assets/js/gestion-ressources.js') ?>"></script>
<?= $this->endSection() ?>
