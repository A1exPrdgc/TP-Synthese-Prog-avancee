<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Création de compte</title>
</head>
<body>

<h1>Créer un compte</h1>

<?php if (!empty($error)) : ?>
    <p style="color:red;"><?= esc($error) ?></p>
<?php endif; ?>

<?php if (!empty($message)) : ?>
    <p style="color:green;"><?= esc($message) ?></p>
<?php endif; ?>

<?php helper('form'); ?>
<?= form_open('signin'); ?>

    <table>
        <tr>
            <th>
                <?= form_label('Nom', 'nom'); ?>
            </th>
            <td>
                <?= form_input('nom', set_value('nom'), 'id="nom" required'); ?>
            </td>
        </tr>

        <tr>
            <th>
                <?= form_label('Prénom', 'prenom'); ?>
            </th>
            <td>
                <?= form_input('prenom', set_value('prenom'), 'id="prenom" required'); ?>
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
                    'id="email" type="email" required'
                ); ?>
            </td>
        </tr>

        <tr>
            <th>
                <?= form_label('Identifiant universitaire', 'username'); ?>
            </th>
            <td>
                <?= form_input('username', set_value('username'), 'id="username" required'); ?>
            </td>
        </tr>

        <tr>
            <th>
                <?= form_label('Mot de passe', 'password'); ?>
            </th>
            <td>
                <?= form_password('password', '', 'id="password" required'); ?>
            </td>
        </tr>

        <tr>
            <th>
                <?= form_label('Confirmation du mot de passe', 'password_confirm'); ?>
            </th>
            <td>
                <?= form_password('password_confirm', '', 'id="password_confirm" required'); ?>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <?= form_submit('creer', 'Valider'); ?>
            </td>
        </tr>
    </table>

<?= form_close(); ?>

<p>
    <a href="<?= site_url('login'); ?>">Retour au login</a>
</p>

</body>
</html>
