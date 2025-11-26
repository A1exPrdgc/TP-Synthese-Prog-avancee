<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>

<h1>Connexion</h1>

<?php if (!empty($error)) : ?>
    <p style="color:red;"><?= esc($error) ?></p>
<?php endif; ?>

<?php if (!empty($message)) : ?>
    <p style="color:green;"><?= esc($message) ?></p>
<?php endif; ?>

<?php helper('form'); ?>

<?= form_open('login'); ?>

    <table>
        <tr>
            <th>
                <?= form_label('Identifiant', 'username'); ?>
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
                <?= form_label('Se souvenir de moi', 'remember'); ?>
            </th>
            <td>
                <?= form_checkbox(
                    'remember',
                    '1',
                    set_checkbox('remember', '1', false),
                    'id="remember"'
                ); ?>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <?= form_submit('connexion', 'Se connecter'); ?>
            </td>
        </tr>
    </table>

<?= form_close(); ?>

<p>
    <a href="<?= site_url('forgot-password'); ?>">Mot de passe oublié ?</a><br>
    <a href="<?= site_url('signin'); ?>">Créer un compte</a>
</p>

</body>
</html>
