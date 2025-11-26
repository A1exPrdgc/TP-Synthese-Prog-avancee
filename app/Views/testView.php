<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>TestView<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/contact.css') ?>" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="success-container">
    <?php if (isset($success)) : ?>
        <div class="alert alert-success"> <?= esc($success) ?> </div>
    <?php endif; ?>
    <?php if (isset($error)) : ?>
        <div class="alert alert-danger"> <?= esc($error) ?> </div>
    <?php endif; ?>

    <h1>Merci, votre message a bien été envoyé !</h1>
    <h2>Récapitulatif :</h2>

    <table class="table table-bordered table-striped mx-auto" style="max-width: 600px;">
        <tbody>
            <tr>
                <th>Nom</th>
                <td><?= htmlspecialchars($contact['nom'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($contact['email'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Message</th>
                <td><?= nl2br(htmlspecialchars($contact['message'] ?? '')) ?></td>
            </tr>
            <?php if (!empty($_FILES['userfile']['name'])) : ?>
            <tr>
                <th>Pièce jointe</th>
                <td><?= htmlspecialchars($_FILES['userfile']['name']) ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="<?= base_url('contact') ?>" class="btn btn-primary mt-4">Retour au formulaire</a>
</div>

<?= $this->endSection() ?>
