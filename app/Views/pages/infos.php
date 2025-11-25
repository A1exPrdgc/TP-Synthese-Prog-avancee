<?= $this->extend('layouts/default') ?>
<?= $this->section('title') ?>Infos<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="mb-4 text-center">À propos de moi</h1>
    
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="card shadow-sm border-info">
                <div class="card-body">
                    <h2 class="card-title">Présentation</h2>
                    <p>Je suis actuellement en 3ᵉ année de BUT Informatique à l'IUT du Havre, en contrat d’apprentissage chez DroneXTR. Cette entreprise est spécialisée dans la détection antidrone pour les sites à risque.</p>
                    <p>Mon objectif est de poursuivre mes études tout en développant mes compétences pratiques, afin de me spécialiser dans un secteur informatique qui me passionne.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="card shadow-sm border-info">
                <div class="card-body">
                    <h2 class="card-title">Expériences et missions</h2>
                    <ul>
                        <li>Gestion réseau et installation de systèmes</li>
                        <li>Développement d'applications web et scripting</li>
                        <li>Participation à la mise en place d’équipements de sécurité antidrone</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-info">
                <div class="card-body">
                    <h2 class="card-title">Liens utiles</h2>
                    <p>Voici quelques liens pour en savoir plus :</p>
                    <ul>
                        <li><a href="<?= base_url('files/CV.pdf') ?>" target="_blank" rel="noopener">Mon CV (PDF)</a></li>
                        <li><a href="https://linkedin.com/in/votrenom" target="_blank" rel="noopener">Mon profil LinkedIn</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>
