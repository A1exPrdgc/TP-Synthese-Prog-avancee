<?= $this->extend('layouts/default') ?>
<?= $this->section('title') ?>Accueil<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="text-center mb-5">
    <h1 class="display-4">Bienvenue sur mon Portfolio</h1>
    <p class="lead">Étudiant en 3ᵉ année de BUT Informatique, je développe mes compétences en alternance chez DroneXTR.</p>
</div>

<div class="row justify-content-center mb-4">
    <div class="col-md-8">
        <div class="card border-primary shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-4">Présentation rapide</h2>
                <p>Ce portfolio met en lumière mes acquis, mes projets et mes objectifs professionnels.</p>
                <p>N'hésitez pas à parcourir les différentes sections pour en savoir plus sur mon parcours et mes compétences.</p>
                <a href="<?= base_url('infos') ?>" class="btn btn-primary mt-3">En savoir plus</a>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-success shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-4">Mes compétences</h2>
                <p>Découvrez mes compétences principales développées au cours de ma formation en BUT Informatique et en entreprise.</p>
                <a href="<?= base_url('competences') ?>" class="btn btn-success mt-3">Voir mes compétences</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
