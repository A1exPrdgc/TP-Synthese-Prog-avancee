<?= $this->extend('layouts/default') ?>
<?= $this->section('title') ?>Compétences<?= $this->endSection() ?>
<?= $this->section('content') ?>
<h1>Compétences BUT 3</h1><br>

<!-- Compétence 1 -->
<div class="row justify-content-center mb-4">
    <div class="col-md-8">
        <div class="card border-primary shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-4">Compétence 1 : <br> Réaliser un Développement d'application</h2>
                <p>	Développer c'est-à-dire concevoir, coder, tester et intégrer une solution informatique pour un client. </p>
                <a href="<?= base_url('comp1') ?>" class="btn btn-primary mt-3">Compétence 1</a>
            </div>
        </div>
    </div>
</div>

<!-- Compétence 2 -->
<div class="row justify-content-center mb-4">
    <div class="col-md-8">
        <div class="card border-success shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-4">Compétence 2 : <br> Optimiser des applications informatiques</h2>
                <p>Proposer des applications informatiques optimisées en fonction de critères spécifiques : temps d'exécution, précision, consommation de ressources. </p>
                <a href="<?= base_url('comp2') ?>" class="btn btn-success mt-3">Compétence 2</a>
            </div>
        </div>
    </div>
</div>

<!-- Compétence 6 -->
<div class="row justify-content-center mb-4">
    <div class="col-md-8">
        <div class="card border-warning shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-4">Compétence 6 : <br> Travailler dans une équipe informatique</h2>
                <p>	Acquérir, développer et exploiter les aptitudes nécessaires pour travailler efficacement dans une équipe informatique. </p>
                <a href="<?= base_url('comp6') ?>" class="btn btn-warning mt-3">Compétence 6</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
