<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MySGRDS | <?= $this->renderSection('title') ?></title>
    <meta name="description" content="Application MySGRDS" />
    <link rel="icon" href="<?= base_url('Logo.ico') ?>" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= base_url('assets/css/default.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/header.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/footer.css') ?>" rel="stylesheet" />
    <?= $this->renderSection('styles') ?>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg my-navbar">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a class="btn btn-square me-2" href=""><span class="arrow-left">&#8592;</span></a>
                <a class="btn btn-custom me-2" href="<?= base_url('rattrapage') ?>">Rattrapage</a>
                <a class="btn btn-custom me-2" href="<?= base_url('ds') ?>">DS</a>
            </div>
            <div class="navbar-title">
                <?= $this->renderSection('navbarTitle') ?>
            </div>
            <?php
                $role = session()->get('fonction');
                $pageType = $this->renderSection('pageType');
            ?>
            <div class="d-flex align-items-center">
                <?php if ($pageType === 'rattrapage' && $role === 'ENS'):   ?>
                    <a class="btn btn-custom me-2" href="<?= base_url('rattrapage/ajouter') ?>">+ Ajouter</a>
                <?php endif; ?>
                <?php if ($pageType === 'ds' && $role == 'DE'): ?>
                    <a class="btn btn-custom me-2" href="<?= base_url('ds/ajouter') ?>">+ Ajouter</a>
                <?php endif; ?>
                
                <a class="btn btn-custom-profile" href="<?= base_url('profil') ?>">
                    <?php 
                        // On récupère la photo en session, sinon on met l'image par défaut
                        $sessionPhoto = session()->get('photo');
                        $photoUrl = !empty($sessionPhoto) ? base_url($sessionPhoto) : 'https://cdn-icons-png.flaticon.com/512/1946/1946429.png';
                    ?>
                    <img class="navbar-profile-img" src="<?= $photoUrl ?>" alt="Profil"/>
                </a>
            </div>
                
                
        </div>
    </nav>
    <!-- Contenu principal -->
    <main class="container my-4 flex-grow-1">
        <?= $this->renderSection('content') ?>
    </main>>
    <!-- Footer -->
    <footer class="footer-iae">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y') ?> MySGRDS</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
