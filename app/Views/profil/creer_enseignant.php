<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un enseignant</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
        /* Petit ajout pour centrer le bouton retour */
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            color: #555;
            text-decoration: none;
        }
        .btn-back:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="auth-container">
    <h1 class="logo-text">Ajouter un Enseignant</h1>
    <p class="subtitle">Le mot de passe sera : <strong>changeme123</strong></p>

    <?php
    helper('form');
    // Gestion des erreurs
    if (isset($validation)) : ?>
        <div class="alert-error" style="color: red; margin-bottom: 15px;">
            <?= $validation->listErrors(); ?>
        </div>
    <?php endif; ?>

    <?= form_open('profil/sauvegarder-enseignant'); ?>
        <table>
            <tr class="inline-fields-row">
                <td>
                    <?= form_label('Nom', 'nom'); ?>
                    <?= form_input(
                        'nom',
                        set_value('nom'),
                        'id="nom" required placeholder="Nom"'
                    ); ?>
                </td>

                <td>
                    <?= form_label('Prénom', 'prenom'); ?>
                    <?= form_input(
                        'prenom',
                        set_value('prenom'),
                        'id="prenom" required placeholder="Prénom"'
                    ); ?>
                </td>
            </tr>

            <tr>
                <th>
                    <?= form_label('Email', 'email'); ?>
                </th>
                <td>
                    <?= form_input(
                        'email',
                        set_value('email'),
                        'id="email" type="email" required placeholder="email@univ.fr"'
                    ); ?>
                </td>
            </tr>

            <tr>
                <th>
                    <?= form_label('Identifiant (Code Enseignant)', 'username'); ?>
                </th>
                <td>
                    <?= form_input(
                        'username',
                        set_value('username'),
                        'id="username" required placeholder="Ex: de000001"'
                    ); ?>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="padding-top: 20px;">
                    <?= form_submit('creer', 'Créer l\'enseignant'); ?>
                </td>
            </tr>
        </table>
    <?= form_close(); ?>

    <div class="links">
        <a href="<?= site_url('profil'); ?>" class="btn-back">← Retour au profil</a>
    </div>
</div>

</body>
</html>