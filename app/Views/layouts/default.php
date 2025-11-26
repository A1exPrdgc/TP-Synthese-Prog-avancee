<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ?> | Mon Portfolio</title>
    <meta name="description" content="Portfolio CodeIgniter 4 avec Bootstrap">
    <link rel="icon" href="<?= base_url('favicon.ico') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">Mon Portfolio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsPortfolio" aria-controls="navbarsPortfolio" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsPortfolio">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item me-2">
                        <a class="btn <?= url_is('/') ? 'btn-light' : 'btn-outline-light' ?>" href="<?= base_url('/') ?>">Accueil</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="btn <?= url_is('infos') ? 'btn-light' : 'btn-outline-light' ?>" href="<?= base_url('infos') ?>">Infos</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="btn <?= url_is('competences') ? 'btn-light' : 'btn-outline-light' ?>" href="<?= base_url('competences') ?>">Compétences</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="btn <?= url_is('contact') ? 'btn-light' : 'btn-outline-light' ?>" href="<?= base_url('contact') ?>">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Contenu principal -->
    <main class="container my-5 flex-grow-1">
        <?= $this->renderSection('content') ?>
    </main>
    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y') ?> - Mon Portfolio CodeIgniter 4</p>
        </div>
    </footer>
    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
