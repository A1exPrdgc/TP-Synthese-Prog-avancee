<?= $this->extend('layouts/default') ?>

<?= $this->section('pageType'); ?>profil<?= $this->endSection(); ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/profil.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/profil_modifier_mdp.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('navbarTitle') ?>
    MySGRDS | Modification mot de passe
<?= $this->endSection() ?>

<?= $this->section('content'); ?>

<div class="auth-container compte-card mdp-card">
    
    <div class="mdp-header">
        <h2>Modification de mot de passe</h2>
        <p class="subtitle">Entrez les informations demandées</p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?= implode('<br>', session()->getFlashdata('errors')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <?php helper('form'); ?>
    <?= form_open('profil/sauvegarder', ['class' => 'form-mdp']); ?>

        <div class="form-group">
            <?= form_label('Mot de passe actuel', 'current_password', ['class' => 'mdp-label']); ?>
            <?= form_password([
                'name' => 'current_password', 
                'id' => 'current_password', 
                'class' => 'mdp-input',
                'required' => 'required'
            ]); ?>
        </div>

        <div class="form-group">
            <?= form_label('Nouveau mot de passe', 'new_password', ['class' => 'mdp-label']); ?>
            <?= form_password([
                'name' => 'new_password', 
                'id' => 'new_password', 
                'class' => 'mdp-input',
                'required' => 'required'
            ]); ?>
        </div>

        <div class="form-group">
            <?= form_label('Confirmation nouveau mot de passe', 'confirm_password', ['class' => 'mdp-label']); ?>
            <?= form_password([
                'name' => 'confirm_password', 
                'id' => 'confirm_password', 
                'class' => 'mdp-input',
                'required' => 'required'
            ]); ?>
        </div>

        <div class="form-buttons">
            <a href="<?= site_url('profil') ?>" class="btn-annuler">Annuler</a>

            <?= form_submit('submit', 'Créer', ['class' => 'btn-creer']); ?>
        </div>
        <?= form_close(); ?>

</div>
<?= $this->endSection(); ?>