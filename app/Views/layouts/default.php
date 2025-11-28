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
                <?php $backUrl = $this->renderSection('backUrl'); ?>
                <?php if (!empty(trim($backUrl))): ?>
                    <a class="btn btn-square me-2" href="<?= esc($backUrl) ?>"><span class="arrow-left">←</span></a>
                <?php endif; ?>
                <?php
                $uri = service('uri');
                $segment1 = $uri->getTotalSegments() > 0 ? $uri->getSegment(1) : '';
                ?>
                <a class="btn btn-custom me-2 <?= ($segment1 == 'rattrapage') ? 'active' : '' ?>" href="<?= base_url('rattrapage') ?>">Rattrapage</a>
                <a class="btn btn-custom me-2 <?= ($segment1 == 'ds') ? 'active' : '' ?>" href="<?= base_url('ds') ?>">DS</a>
            </div>
            <div class="navbar-title">
                <?= $this->renderSection('navbarTitle') ?>
            </div>
            <?php
            $role = session()->get('fonction');
            $pageType = $this->renderSection('pageType');
            ?>
            <div class="d-flex align-items-center">

                <?php if ($pageType === 'ds' && $role == 'DE'): ?>
                    <a class="btn btn-custom me-2" href="<?= base_url('ds/ajouter') ?>">+ Ajouter</a>
                <?php endif; ?>

                <a class="btn btn-custom-profile" href="<?= base_url('profil') ?>">
                    <?php
                    $photoUrl = session()->get('photo');
                    if (empty($photoUrl) && session()->get('connected')) {
                        $model = new \App\Models\TeachersModel();
                        $user = $model->find(session()->get('code'));

                        if (!empty($user['photo'])) {
                            $photoUrl = $user['photo'];
                            session()->set('photo', $photoUrl);
                        }
                    }
                    $finalUrl = !empty($photoUrl) ? base_url($photoUrl) : 'https://cdn-icons-png.flaticon.com/512/1946/1946429.png';
                    ?>
                    <img class="navbar-profile-img" src="<?= $finalUrl ?>" alt="Profil" />
                </a>
            </div>


        </div>
    </nav>
    <!-- Contenu principal -->
    <main class="container my-4 flex-grow-1">
        <?= $this->renderSection('content') ?>
    </main>
    <!-- Footer -->
    <footer class="footer-iae">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y') ?> MySGRDS</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Gestion des messages flash en pop-up -->
    <script>
        // Messages d'erreur
        <?php if (session()->getFlashdata('error')): ?>
            alert('<?= addslashes(session()->getFlashdata('error')) ?>');
        <?php endif; ?>
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>

</html>