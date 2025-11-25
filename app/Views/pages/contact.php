<?= $this->extend('layouts/default') ?>
<?= $this->section('title') ?>Contact<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="mb-4 text-center">Contact</h1>
    
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="card shadow-sm border-info">
                <div class="card-body">
                    <h2 class="card-title">Mes coordonnées</h2>
                    <ul>
                        <li>Email : <a href="mailto:votremail@example.com">votremail@example.com</a></li>
                        <li>Téléphone : 06 12 34 56 78</li>
                        <li>LinkedIn : <a href="https://linkedin.com/in/votrenom" target="_blank" rel="noopener">linkedin.com/in/votrenom</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-info">
                <div class="card-body">
                    <h2 class="card-title mb-4">Formulaire de contact</h2>
                    <form action="<?= base_url('contact/send') ?>" method="post">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" id="nom" name="nom" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
