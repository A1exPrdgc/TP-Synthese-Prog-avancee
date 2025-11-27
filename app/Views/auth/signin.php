<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Création de compte</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="auth-container">
    <h1 class="logo-text">Créer votre compte</h1>
    <p class="subtitle">Entrez les informations demandés</p>

    <?php
    helper('form');
    // Message "générique" éventuel
    if (!empty($message)) : ?>
        <p class="alert-success"><?= esc($message) ?></p>
    <?php endif; ?>

    <?php
    // Erreurs de validation détaillées
    if (isset($validation)) : ?>
        <div class="alert-error">
            <?= $validation->listErrors(); ?>
        </div>
    <?php elseif (!empty($error)) : ?>
        <p class="alert-error"><?= esc($error) ?></p>
    <?php endif; ?>

    <?= form_open('signin'); ?>
        <table>
            <!-- Nom / Prénom sur la même ligne -->
            <tr class="inline-fields-row">
                <td>
                    <?= form_label('Nom', 'nom'); ?>
                    <?= form_input(
                        'nom',
                        set_value('nom'),
                        'id="nom" required'
                    ); ?>
                </td>

                <td>
                    <?= form_label('Prénom', 'prenom'); ?>
                    <?= form_input(
                        'prenom',
                        set_value('prenom'),
                        'id="prenom" required'
                    ); ?>
                </td>
            </tr>

            <!-- Email -->
            <tr>
                <th>
                    <?= form_label('Email', 'email'); ?>
                </th>
                <td>
                    <?= form_input(
                        'email',
                        set_value('email'),
                        'id="email" type="email" required'
                    ); ?>
                </td>
            </tr>

            <!-- Identifiant -->
            <tr>
                <th>
                    <?= form_label('Identifiant', 'username'); ?>
                </th>
                <td>
                    <?= form_input(
                        'username',
                        set_value('username'),
                        'id="username" required'
                    ); ?>
                </td>
            </tr>

            <!-- Mot de passe -->
            <tr>
                <th>
                    <?= form_label('Mot de passe (6 caractères minimum)', 'password'); ?>
                </th>
                <td>
                    <?= form_password(
                        'password',
                        '',
                        'id="password" required'
                    ); ?>
                </td>
            </tr>

            <!-- Confirmation mot de passe -->
            <tr>
                <th>
                    <?= form_label('Confirmation de mot de passe', 'password_confirm'); ?>
                </th>
                <td>
                    <?= form_password(
                        'password_confirm',
                        '',
                        'id="password_confirm" required'
                    ); ?>
                </td>
            </tr>

            <!-- Bouton -->
            <tr>
                <td colspan="2">
                    <?= form_submit('creer', 'Créer'); ?>
                </td>
            </tr>
        </table>
    <?= form_close(); ?>

    <div class="links">
        <p>Déjà un compte ?</p>
        <a href="<?= site_url('login'); ?>">Connectez-vous</a>
    </div>
</div>

</body>
</html>
