<?= $this->extend('layouts/default') ?>

<?= $this->section('pageType'); ?>profil<?= $this->endSection(); ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
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
                </div>

            <div class="compte-left-labels">
                <p class="compte-label">Email</p>
                <p class="compte-label">Identifiant</p>
            </div>
        </div>

        <div class="compte-separator"></div>

        <div class="compte-right">
            <div class="compte-name-block">
                <h2 class="compte-nom"><?= esc($user['nom'] ?? 'NOM'); ?></h2>
                <h2 class="compte-prenom"><?= esc($user['prenom'] ?? 'Prénom'); ?></h2>
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
            Modifier le mot de passe
        </a>
        <a href="<?= site_url('logout'); ?>" class="profil-btn profil-btn-danger">
            Déconnexion
        </a>
    </div>
</div>
<?= $this->endSection(); ?>