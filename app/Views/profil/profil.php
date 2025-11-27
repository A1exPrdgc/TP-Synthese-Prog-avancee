<?= $this->extend('layouts/default') ?>

<?= $this->section('pageType'); ?>profil<?= $this->endSection(); ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/profil.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content'); ?>

<div class="auth-container compte-card">
    <div class="compte-layout">
        
        <div class="compte-left">
            <div class="profile-photo-block">
                <?php if (!empty($user['photo'])) : ?>
                    <img src="<?= base_url($user['photo']); ?>" alt="Profil" class="profile-photo-round">
                <?php else : ?>
                     <img src="https://via.placeholder.com/150" alt="Profil" class="profile-photo-round">
                <?php endif; ?>
                <div style="position: absolute; top: 0; right: 0;">
                    <i class="icon-edit"></i>
                </div>
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
                    <i class="icon-edit"></i>
                </h2>
                <h2 class="compte-prenom">
                    <?= esc($user['prenom'] ?? 'Prénom'); ?>
                    <i class="icon-edit"></i>
                </h2>
            </div>

            <div class="compte-info-lines">
                <p class="compte-info">
                    <span><?= esc($user['email'] ?? 'email@test.com'); ?></span>
                     <i class="icon-edit"></i> 
                </p>
                <p class="compte-info">
                    <span><?= esc($user['code'] ?? 'ID12345'); ?></span>
                    <i class="icon-edit"></i>
                </p>
            </div>
        </div>
    </div>

    <div class="compte-buttons-row">
        <a href="<?= site_url('forgot-password'); ?>" class="profil-btn profil-btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;"><path d="M2.5 2v6h6M21.5 22v-6h-6"/><path d="M22 11.5A10 10 0 0 0 3.2 7.2M2 12.5a10 10 0 0 0 18.8 4.2"/></svg>
            Modifier le mot de passe
        </a>
        <a href="<?= site_url('logout'); ?>" class="profil-btn profil-btn-danger">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Déconnexion
        </a>
    </div>
</div>
<?= $this->endSection(); ?>