<?= $this->extend('layouts/default') ?>

<?= $this->section('pageType'); ?>profil<?= $this->endSection(); ?>

<?= $this->section('backUrl') ?><?= base_url('profil') ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/profil.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/profil_modification.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('navbarTitle') ?>
MySGRDS | Modification Compte
<?= $this->endSection() ?>

<?= $this->section('content'); ?>

<div class="auth-container compte-card">

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php endif; ?>

    <?= validation_list_errors() ?>

    <?= form_open_multipart('profil/mettre-a-jour', ['class' => 'form-modification']) ?>

    <div class="compte-layout">

        <div class="compte-left">
            <div class="profile-photo-block">
                <?php if (!empty($user['photo'])) : ?>
                    <img src="<?= base_url($user['photo']); ?>" alt="Profil" class="profile-photo-round">
                <?php else : ?>
                    <img src="https://via.placeholder.com/180" alt="Profil" class="profile-photo-round">
                <?php endif; ?>

                <div class="photo-input-group">
                    <?php echo form_upload('photo', '', 'id="photo"'); ?>

                    <?php
                    echo form_label('Changer la photo', 'photo', [
                        'class' => 'btn-action btn-primary btn-photo'
                    ]);
                    ?>
                    <?= validation_show_error('photo') ?>
                    <p class="photo-help-text"><small>Max 2 Mo (JPG, PNG)</small></p>
                </div>
            </div>

        </div>

        <div class="compte-separator"></div>

        <div class="compte-right">
            <div class="compte-name-block">

                <div class="form-group">
                    <?php echo form_label('Nom :', 'nom', ['class' => 'form-label-bold']); ?>
                    <?php echo form_input('nom', set_value('nom', $user['nom']), 'id="nom" class="form-control" required placeholder="Votre nom"'); ?>
                    <?= validation_show_error('nom') ?>
                </div>

                <div class="form-group mt-15">
                    <?php echo form_label('Prénom :', 'prenom', ['class' => 'form-label-bold']); ?>
                    <?php echo form_input('prenom', set_value('prenom', $user['prenom']), 'id="prenom" class="form-control" required placeholder="Votre prénom"'); ?>
                    <?= validation_show_error('prenom') ?>
                </div>

            </div>

            <div class="compte-info-lines mt-20">

                <div class="form-group">
                    <?php echo form_input('email', set_value('email', $user['email']), 'id="email" class="form-control" type="email" required placeholder="email@exemple.com"'); ?>
                    <?= validation_show_error('email') ?>
                </div>

                <p class="compte-info mt-15">
                    <span>Identifiant : <?= esc($user['code']); ?></span>
                </p>
            </div>
        </div>

    </div>

    <div class="compte-buttons-row">
        <a href="<?= site_url('profil'); ?>" class="btn-action btn-secondary">
            Annuler
        </a>

        <?php echo form_submit('submit', 'Enregistrer', ['class' => 'btn-action btn-primary']); ?>
    </div>

    <?= form_close() ?>
</div>
<?= $this->endSection(); ?>