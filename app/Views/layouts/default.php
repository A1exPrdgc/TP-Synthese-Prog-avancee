<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>MySGRDS | <?= $this->renderSection('title') ?></title>

    <meta name="description" content="Application MySGRDS" />

    <link rel="icon" href="<?= base_url('Logo.ico') ?>" />
     
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= base_url('assets/css/header.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/footer.css') ?>" rel="stylesheet" />
    <?= $this->renderSection('styles') ?>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg my-navbar">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a class="btn btn-square me-2" href="#"><span class="arrow-left">&#8592;</span></a>
                <a class="btn btn-custom me-2" href="#">🏛 Rattrapage</a>
                <a class="btn btn-custom me-2" href="#">📝 DS</a>
            </div>
            <div class="mx-auto navbar-title">
                MySGRDS | DS
            </div>
            <div class="d-flex align-items-center">
                <a class="btn btn-custom me-2" href="#">+ Ajouter</a>
                <a class="btn btn-custom-profile" href="#"><span class="navbar-profile-img"></span></a>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="container my-5 flex-grow-1 shadow-sm rounded bg-white p-4">
        <?= $this->renderSection('content') ?>
    </main>
    <!-- Footer -->
    <footer class="footer-iae">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y') ?> MySGRDS</p>
        </div>
    </footer>

    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
