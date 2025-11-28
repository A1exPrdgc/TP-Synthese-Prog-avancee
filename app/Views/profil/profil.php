<?php 
helper(['html', 'url']); 
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('pageType'); ?>profil<?= $this->endSection(); ?>

<?= $this->section('backUrl') ?><?= base_url('rattrapage') ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/profil.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('navbarTitle') ?>
    MySGRDS | Page Compte
<?= $this->endSection() ?>

<?= $this->section('content'); ?>

<div class="auth-container compte-card">
    
    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success" style="text-align: center; margin-bottom: 30px; border-radius: 10px;">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <div class="compte-layout">
        
        <div class="compte-left">
            <div class="profile-photo-block">
                <?php 
                    $photoPath = !empty($user['photo']) ? base_url($user['photo']) : 'https://via.placeholder.com/180';
                    echo img([
                        'src'   => $photoPath,
                        'alt'   => 'Profil',
                        'class' => 'profile-photo-round'
                    ]);
                ?>
            </div>

            <div class="compte-left-labels">
                <p class="compte-label">Email</p>
                <p class="compte-label">Identifiant</p>
            </div>
        </div>

        <div class="compte-separator"></div>

        <div class="compte-right">
            <div class="compte-name-block">
                <h2 class="compte-nom">
                    <?= esc(strtoupper($user['nom'] ?? 'NOM')); ?> 
                </h2>
                
                <h2 class="compte-prenom">
                    <?= esc($user['prenom'] ?? 'Prénom'); ?>
                </h2>
            </div>

            <div class="compte-info-lines">
                <p class="compte-info">
                    <span><?= esc($user['email'] ?? 'email@test.com'); ?></span>
                </p>
                
                <p class="compte-info">
                    <span><?= esc($user['code'] ?? 'ID12345'); ?></span>
                </p>
            </div>
        </div>
    </div>

    <div class="compte-buttons-row">
        
        <?php
            $iconUser = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>';
            $iconKey = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 2v6h6M21.5 22v-6h-6"/><path d="M22 11.5A10 10 0 0 0 3.2 7.2M2 12.5a10 10 0 0 0 18.8 4.2"/></svg>';
            $iconLogout = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>';
        ?>

        <?= anchor('profil/modifier', $iconUser . 'Modifier Profil', ['class' => 'profil-btn profil-btn-primary']) ?>
        <?= anchor('profil/modifier-mot-de-passe', $iconKey . 'Changer mot de passe', ['class' => 'profil-btn profil-btn-secondary']) ?>
        <?= anchor('deconnecter', $iconLogout . ' Déconnexion', ['class' => 'profil-btn profil-btn-danger']) ?>

    </div>
</div>
<?= $this->endSection(); ?>